<?php

namespace App\Relationship;

use App\User;
use App\Relationship\State;
use App\Relationship\Relation;
use App\Relationship\RelationInterface;

class Invitation extends Relation implements RelationInterface {
    /**
     * @type integer
     */
    protected $state = State::INVITATION;


    /**
     * @param User $user
     * @return boolean
     */
    protected function cannotBeFriendOf (User $user) {
        return  $this->user->is($user) || $this->hasRelationsWith($user)
            || $this->user->isFriendWith($user);
    }
}