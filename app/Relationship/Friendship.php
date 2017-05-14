<?php

namespace App\Relationship;

use App\User;
use App\Relationship;
use App\Relationship\State;
use App\Relationship\Relation;
use App\Relationship\RelationInterface;

class Friendship extends Relation implements RelationInterface {
    /**
     * @type integer
     */
    protected $state = State::FRIENDSHIP;


    /**
     * @param User $user
     * @return boolean
     */
    protected function cannotBeFriendOf (User $user) {
        return  $this->user->is($user) || $this->hasRelationsWith($user)
            || ! $this->user->hasReceivedRequestFrom($user);
    }

    /**
     * @param User $user
     * @return void
     */
    protected function updateState (User $user) {
        Relationship::between($user, $this->user)->where('state', State::INVITATION)
            ->update(['state' => $this->state]);
    }
}