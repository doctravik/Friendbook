<?php

namespace App\Relationship;

use App\User;

class PartnerQueryBuilder {
    /**
     * @type \Illuminate\Database\Eloquent\Collection $relationships
     */
    private $relationships;

    /**
     * @type \Illuminate\Database\Eloquent\Model
     */
    private $model;


    /**
     * @param  \Illuminate\Database\Eloquent\Collection $relationships
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function prepare($relationships, $model = User::class) {
        return (new static($relationships, $model))->selectFriends();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $relationships
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function __construct ($relationships, $model = User::class) {
        $this->relationships = $relationships;
        $this->model = resolve($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function selectFriends () {
        $keys = $this->extractFriendKeys();
        
        return $this->selectFriendsByKeys($keys);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function extractFriendKeys () {
        $senders = $this->relationships->pluck('sender_id');
        $recipients = $this->relationships->pluck('recipient_id');
        
        return $senders->merge($recipients)->unique();        
    }

    /**
     * @param  \Illuminate\Support\Collection $keys
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function selectFriendsByKeys ($keys) {
        return $this->model->whereIn($this->model->getKeyName(), $keys);
    }
}