<?php

namespace App\Friendship;

use App\User;
use App\Friendship;
use App\Friendship\Status;
use App\Friendship\FriendsQueryBuilder;

trait FriendshipTrait {
    /**
     * @param User $user
     * @return boolean
     */
    public function isFriendWith (User $user) {
        return $this->findFriendshipsWith($user)->where('status', Status::ACCEPTED)->exists();
    }

    /**
     * @param User $user
     * @param int $status
     * @return boolean
     */
    public function hasFriendshipWith (User $user, $status) {
        return $this->findFriendshipsWith($user)->where('status', $status)->exists();
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function hasAnyFriendshipWith (User $user) {
        return $this->findFriendshipsWith($user)->exists();
    }

    public function selectFriendsCount () {
        return $this->findAcceptedFriendships()->count();        
    }

    /**
     * @param User $user
     * @return void
     */
    public function cancelFriendship (User $user) {
        $this->findFriendshipsWith($user)->delete();  
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function friends () {
        $friendships = $this->findAcceptedFriendships();
        
        $friends = FriendsQueryBuilder::getFriendsFrom($friendships);
        
        return $this->excludeMeFrom($friends);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findAcceptedFriendships () {
        return $this->findFriendships()->where('status', Status::ACCEPTED)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function findFriendships () {
        return Friendship::of($this);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function findFriendshipsWith (User $user) {
        return Friendship::between($this, $user);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Builder; 
     */
    private function excludeMeFrom($query) {
        return $query->where('id', '!=', $this->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    private function senders () {
        return $this->belongsToMany(static::class, 'friends', 'requested_id', 'requester_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    private function recipients () {
        return $this->belongsToMany(static::class, 'friends', 'requester_id', 'requested_id');
    }
}