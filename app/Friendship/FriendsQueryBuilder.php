<?php

namespace App\Friendship;

use App\User;

class FriendsQueryBuilder {
    /**
     * @type \Illuminate\Database\Eloquent\Collection $friendships
     */
    private $friendships;

    /**
     * @type \Illuminate\Database\Eloquent\Model
     */
    private $model;


    /**
     * @param \Illuminate\Database\Eloquent\Collection $friendships
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function __construct ($friendships, $model = User::class) {
        $this->friendships = $friendships;
        $this->model = resolve($model);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection $friendships
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getFriendsFrom ($friendships, $model = User::class) {
        return (new static($friendships, $model))->selectFriends();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function selectFriends () {
        $keys = $this->extractFriendKeys($this->friendships);
        
        return $this->selectFriendsByKeys($keys);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function extractFriendKeys () {
        $senders = $this->friendships->pluck('requester_id');
        $recipients = $this->friendships->pluck('requested_id');
        
        return $senders->merge($recipients)->unique();        
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection $keys
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function selectFriendsByKeys ($keys) {
        return $this->model->whereIn($this->model->getKeyName(), $keys);
    }
}