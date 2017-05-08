<?php

namespace App\Subscription;

use App\User;

trait SubscriptionTrait {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers () {
        return $this->belongsToMany(static::class, 'followers', 'followed_id', 'follower_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followedUsers () {
        return $this->belongsToMany(static::class, 'followers', 'follower_id', 'followed_id');
    }

    /**
     * @param User $user
     * @return void
     */
    public function follow (User $user) {
        if(! $this->isFollowerOf($user))
            $this->followedUsers()->attach($user);
    }

    /**
     * @param User $user
     * @return void
     */
    public function unfollow (User $user) {
        $this->followedUsers()->detach($user);
    }
    
    /**
     * @param User $user
     * @return boolean
     */
    public function isFollowerOf (User $user) {
        return $this->followedUsers()->where('followed_id', $user->id)->exists();
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function isFollowedBy (User $user) {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    public function selectFollowersCount () {
        return $this->followers()->count();
    }
}