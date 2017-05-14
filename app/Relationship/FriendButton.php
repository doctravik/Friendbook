<?php

namespace App\Relationship;

use App\User;
use App\Relationship;
use App\Relationship\State;

class FriendButton {
    const NOT_FRIEND_STATE = 0;
    const REQUEST_SENT_STATE = 1;
    const REQUEST_RECEIVED_STATE = 2;
    const FRIEND_STATE = 3;


    /**
     * @type User $currentUser
     */
    private $currentUser;

    /**
     * @type User $profileUser
     */
    private $profileUser;

    /**
     * @type int $state
     */
    private $state = null;


    /**
     * @param User $currentUser
     * @param User $profileUser
     * @return static             
     */
    public static function for (User $currentUser, User $profileUser) {
        return (new static($currentUser, $profileUser))->defineState();
    }
    
    /**
     * @param User $currentUser
     * @param User $profileUser
     * @return void
     */
    public function __construct (User $currentUser, User $profileUser) {
        $this->currentUser = $currentUser;
        $this->profileUser = $profileUser;
    }

    /**
     * @return int
     */
    public function getState () {
        return $this->state;
    }

    /**
     * @return this
     */
    private function defineState () {
        $relationship = $this->currentUser->findRelationsWith($this->profileUser)
            ->where('state', '<>', State::SUBSCRIPTION)->first();

        $this->parse($relationship);

        return $this;
    }

    /**
     * @param Relationship|null $relationship
     * @return void
     */
    private function parse ($relationship) {
        if ($relationship == null) {
            $this->state = self::NOT_FRIEND_STATE;
        } elseif ($this->hasPendingState($relationship->state)) {
            $this->setPendingState($relationship);
        } else {
            $this->state = $this->map()->get($relationship->state);
        }
    }
    
    /**
     * @return boolean
     */
    private function hasPendingState ($state) {
        return $state == State::INVITATION;
    }

    /**
     * @param Relationship $relationship
     */
    private function setPendingState (Relationship $relationship) {
        if ($this->isSender($relationship->sender_id)) {
            $this->state = self::REQUEST_SENT_STATE;
        } else {
            $this->state = self::REQUEST_RECEIVED_STATE;
        }
    }

    /**
     * @param int $senderId
     * @return boolean
     */
    private function isSender ($senderId) {
        return $this->currentUser->id == $senderId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function map () {
        return collect([
            State::FRIENDSHIP => self::FRIEND_STATE
        ]);
    }
}