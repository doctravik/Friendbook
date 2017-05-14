<?php

namespace App\Relationship;

use App\User;
use App\Relationship;
use App\Relationship\PartnerQueryBuilder;

abstract class Relation {
    /**
     * @type integer
     */
    protected $state;

    /**
     * @type User
     */
    protected $user;


    /**
     * @param User $user
     * @return void
     */
    public function __construct (User $user) {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function senders () {
        return $this->user->belongsToMany(User::class, 'friends', 'recipient_id', 'sender_id')
            ->wherePivot('state', $this->state)
            ->withPivot('state');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipients () {
        return $this->user->belongsToMany(User::class, 'friends', 'sender_id', 'recipient_id')
            ->wherePivot('state', $this->state)
            ->withPivot('state');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function partners () {
        $relations = $this->find($this->user)->get();

        $partners = PartnerQueryBuilder::prepare($relations);

        return $this->excludeMeFrom($partners);
    }

    /**
     * @param User $user
     * @return void
     */
    public function create (User $user) {
        if ($this->canBeFriendOf($user))
            $this->updateState($user);
    }

    /**
     * @param User $user
     * @return void
     */
    public function remove (User $user) {
        $this->find($user, $this->user)->delete($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function hasSender (User $sender) {
        return $this->senders()->where('sender_id', $sender->id)->exists();
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function hasRecepient (User $recipient) {
        return $this->recipients()->where('recipient_id', $recipient->id)->exists();
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function hasRelationsWith (User $user) {
        return $this->find($user, $this->user)->exists();
    }

    public function selectRecipientsCount () {
       return $this->recipients()->count();
    }

    public function selectSendersCount () {
       return $this->senders()->count();
    }

    public function selectPartnersCount () {
        return $this->find($this->user)->count();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function excludeMeFrom($query) {
        return $query->where('id', '<>', $this->user->id);
    }

    /**
     * @param User $user
     * @return boolean
     */
    protected function canBeFriendOf (User $user) {
        return ! $this->cannotBeFriendOf($user);
    }

    /**
     * @param User $user
     * @return boolean
     */
    protected function cannotBeFriendOf (User $user) {
        return  $this->user->is($user) || $this->hasRelationsWith($user);
    }

    /**
     * @param User $user
     * @return void
     */
    protected function updateState (User $user) {
        $this->recipients()->attach($user, ['state' => $this->state]);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function find ($sideOne, $sideTwo = null) {
        if ($sideTwo) 
            $relationships = Relationship::between($sideOne, $sideTwo);
        else
            $relationships = Relationship::of($sideOne);

        return $relationships->where('state', $this->state);
    }
}