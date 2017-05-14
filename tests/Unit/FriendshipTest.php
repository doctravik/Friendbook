<?php

namespace Tests\Unit\Relationship;

use App\User;
use Tests\TestCase;
use App\Relationship;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FriendshipTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function user_can_not_be_friend_of_he_has_not_invitation () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->beFriend($emma);

        $this->assertFalse($leon->isFriendWith($emma));
        $this->assertFalse($emma->isFriendWith($leon));
    }

    /** @test */
    public function sender_can_not_accept_friendship () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $leon->beFriend($emma);

        $this->assertFalse($leon->isFriendWith($emma));
    }

    /** @test */
    public function user_can_be_friend_of_another_user () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $emma->invite($leon);
        $leon->beFriend($emma);

        $this->assertTrue($emma->isFriendWith($leon));
        $this->assertTrue($leon->isFriendWith($emma));
    }

    /** @test */
    public function there_is_no_invitation_after_accepting_friendship () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $emma->invite($leon);
        $leon->beFriend($emma);

        $this->assertTrue($emma->isFriendWith($leon));
        $this->assertFalse($leon->hasSentRequestTo($emma));
    }

    /** @test */
    public function sender_can_cancel_friendship () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $emma->invite($leon);
        $leon->beFriend($emma);
        $emma->unfriend($leon);

        $this->assertFalse($leon->isFriendWith($emma));
    }

    /** @test */
    public function recipient_can_cancel_friendship () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $emma->beFriend($leon);
        $emma->unfriend($leon);

        $this->assertFalse($leon->isFriendWith($emma));
    }

    /** @test */
    public function user_can_not_be_friend_of_himself () {
        $leon = factory(User::class)->create();

        $leon->invite($leon);
        $leon->beFriend($leon);

        $this->assertEquals(0, $leon->selectFriendsCount());
    }

    /** @test */
    public function user_can_not_be_friend_of_another_user_twice () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $emma->beFriend($leon);
        $emma->invite($leon);
        $leon->beFriend($leon);

        $this->assertCount(0, $emma->inviters);
        $this->assertCount(0, $leon->inviters);
        $this->assertTrue($leon->isFriendWith($emma));
        $this->assertCount(1, Relationship::between($emma, $leon)->get());
    }

    /** @test */
    public function it_can_count_number_of_friends () {
        [$leon, $emma, $john] = factory(User::class, 3)->create();

        $leon->invite($emma);
        $emma->beFriend($leon);
        $emma->invite($john);
        $john->beFriend($emma);

        $this->assertEquals(1, $leon->selectFriendsCount());
        $this->assertEquals(2, $emma->selectFriendsCount());
        $this->assertEquals(1, $john->selectFriendsCount());
    }

    /** @test */
    public function friend_senders_contain_accepted_friends_that_have_been_invited () {
        [$leon, $emma] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $emma->beFriend($leon);

        $this->assertTrue($leon->friendsInvitedByMe->contains($emma));
    }

    /** @test */
    public function friend_senders_contain_accepted_friends_that_sent_request () {
        [$leon, $emma] = factory(User::class, 2)->create();

        $leon->invite($emma);
        $emma->beFriend($leon);

        $this->assertTrue($emma->friendsInvitedMe->contains($leon));
    }

    /** @test */
    public function friends_contain_both_senders_and_recipients () {
        [$leon, $emma, $john] = factory(User::class, 3)->create();

        $leon->invite($emma);
        $emma->beFriend($leon);
        $emma->invite($john);
        $john->beFriend($emma);

        $this->assertCount(2, $friends = $emma->friends()->get());
        $this->assertTrue($friends->contains($john));
        $this->assertTrue($friends->contains($leon));
    }
}
