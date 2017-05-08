<?php

namespace App\Friendship;

use App\User;
use App\Friendship\Status;

trait FriendRequestTrait {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function friendRequestsSent () {
        return $this->recipients()->wherePivot('status', Status::PENDING)
            ->withPivot('status');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function friendRequestsReceived () {
        return $this->senders()->wherePivot('status', Status::PENDING)
            ->withPivot('status');
    }

    /**
     * @param User $recipient
     * @return boolean
     */
    public function hasSentFriendRequestTo (User $recipient) {
        return $this->friendRequestsSent()->where('requested_id', $recipient->id)->exists();
    }

    /**
     * @param User $recipient
     * @return void
     */
    public function sendFriendRequestTo (User $recipient) {
        if ($this->canBeFriendOf($recipient))
            $this->friendRequestsSent()->attach($recipient);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function canBeFriendOf(User $user)
    {
        if ($this->id == $user->id)
            return false;

        if ($this->hasAnyFriendshipWith($user))
            return false;
        
        return true;
    }

    /**
     * @param User $seder
     * @return void
     */
    public function acceptFriendRequest (User $sender) {
        if ($this->hasFriendshipWith($sender, Status::PENDING))
            $this->addFriend($sender);
    }

    /**
     * @param User $user
     * @return void
     */
    public function rejectFriendRequest (User $user) {
        $this->friendRequestsReceived()->detach($user);
    }

    /**
     * @param User $user
     * @return void
     */
    private function addFriend (User $user) {
        $this->friendRequestsReceived()->updateExistingPivot($user->id, [ 
            'status' => Status::ACCEPTED
        ]);
    }
}