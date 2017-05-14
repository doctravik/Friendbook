<?php

namespace App\Relationship;

use App\Relationship\State;
use App\Relationship\Relation;
use App\Relationship\RelationInterface;

class Subscription extends Relation implements RelationInterface {
    /**
     * @type integer
     */
    protected $state = State::SUBSCRIPTION;
}