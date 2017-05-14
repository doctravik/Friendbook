<?php

namespace App\Relationship;

use App\User;
use App\Relationship\State;
use App\Relationship\Friendship;
use App\Relationship\Invitation;
use App\Relationship\Subscription;

trait Friendable {
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers () {
        return $this->subcription()->senders();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inviters () {
        return $this->invitation()->senders();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function friendsInvitedMe () {
        return $this->friendship()->senders();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followedUsers () {
        return $this->subcription()->recipients();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function invitedUsers () {
        return $this->invitation()->recipients();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function friendsInvitedByMe () {
        return $this->friendship()->recipients();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function subscriptionPartners () {
        return $this->subcription()->partners();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function invitationPartners () {
        return $this->invitation()->partners();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function friends () {
        return $this->friendship()->partners();
    }

    /**
     * @param User $user
     * @return void
     */
    public function follow (User $user) {
        return $this->subcription()->create($user);
    }

    /**
     * @param User $user
     * @return void
     */
    public function invite (User $user) {
        return $this->invitation()->create($user);
    }

    /**
     * @param User $user
     * @return void
     */
    public function beFriend (User $user) {
        return $this->friendship()->create($user);
    }

    /**
     * @param User $user
     * @return void
     */
    public function unfollow (User $user) {
        return $this->subcription()->remove($user);
    }

    /**
     * @param User $user
     * @return void
     */
    public function reject (User $user) {
        return $this->invitation()->remove($user);
    }

    /**
     * @param User $user
     * @return void
     */
    public function unfriend (User $user) {
        return $this->friendship()->remove($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function isFollowedBy (User $user) {
        return $this->subcription()->hasSender($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function hasReceivedRequestFrom (User $user) {
        return $this->invitation()->hasSender($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function isFollowerOf (User $user) {
        return $this->subcription()->hasRecepient($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function hasSentRequestTo (User $user) {
        return $this->invitation()->hasRecepient($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function hasSubscriptionWith (User $user) {
        return $this->subcription()->hasRelationsWith($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function hasPendingRelation (User $user) {
        return $this->invitation()->hasRelationsWith($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function isFriendWith (User $user) {
        return $this->friendship()->hasRelationsWith($user);
    }

    public function selectFollowersCount () {
        return $this->subcription()->selectSendersCount();
    }

    public function selectInvitersCount () {
        return $this->invitation()->selectSendersCount();
    }

    public function selectFriendSendersCount () {
        return $this->friendship()->selectSendersCount();
    }

    public function selectFollowedUsersCount () {
        return $this->subcription()->selectRecipientsCount();
    }

    public function selectInvitedUsersCount () {
        return $this->invitation()->selectRecipientsCount();
    }

    public function selectFriendRecipientsCount () {
        return $this->friendship()->selectRecipientsCount();
    }

    public function selectSubcriptionPartnersCount () {
        return $this->subcription()->selectPartnersCount();
    }

    public function selectPendingPartnersCount () {
        return $this->invitation()->selectPartnersCount();
    }

    public function selectFriendsCount () {
        return $this->friendship()->selectPartnersCount();
    }

    /**
     * @param User $user
     * @return int
     */
    public function getFriendButtonStateFor (User $user) {
        return FriendButton::for($this, $user)->getState();
    }

    /**
     * @param User $user
     * @return int
     */
    public function findRelationsWith (User $user) {
        return \App\Relationship::between($this, $user);
    }

    /**
     * Initialize Subcription class.
     * 
     * @return Subcription
     */
    protected function subcription () {
        return (new Subscription($this));
    }

    /**
     * Initialize Invitation class.
     * 
     * @return Invitation
     */
    protected function invitation () {
        return new Invitation($this);
    }

    /**
     * Initialize Friendship class.
     * 
     * @return Invitation
     */
    protected function friendship () {
        return new Friendship($this);
    }
}