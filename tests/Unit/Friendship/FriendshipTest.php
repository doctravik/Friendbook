<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FriendshipTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function user_can_accept_friend_request_of_the_another_user () {
        [ $john, $bobby ] = factory(User::class, 2)->create();

        $bobby->sendFriendRequestTo($john);
        $john->acceptFriendRequest($bobby);

        $this->assertTrue($john->isFriendWith($bobby));
        $this->assertTrue($bobby->isFriendWith($john));
    }

    /** @test */
    public function user_can_reject_friend_request_of_the_another_user () {
        [ $john, $bobby ] = factory(User::class, 2)->create();

        $bobby->sendFriendRequestTo($john);
        $john->rejectFriendRequest($bobby);

        $this->assertFalse($john->isFriendWith($bobby));
    }

    /** @test */
    public function user_can_cancel_friendship_with_existing_friend () {
        [ $john, $bobby ] = factory(User::class, 2)->create();

        $bobby->sendFriendRequestTo($john);
        $john->acceptFriendRequest($bobby);
        $john->cancelFriendship($bobby);

        $this->assertFalse($john->hasAnyFriendshipWith($bobby));
    }

    /** @test */
    public function it_can_count_number_of_friends () {
        [$leon, $emma, $charles] = factory(User::class, 3)->create();

        $leon->sendFriendRequestTo($emma);
        $emma->acceptFriendRequest($leon);

        $charles->sendFriendRequestTo($emma);
        $emma->acceptFriendRequest($charles);

        $this->assertEquals(1, $leon->selectFriendsCount());
        $this->assertEquals(2, $emma->selectFriendsCount());
        $this->assertEquals(1, $charles->selectFriendsCount());
    }

    /** @test */
    public function it_can_return_all_friends_of_the_user () {
        [$leon, $emma, $charles] = factory(User::class, 3)->create();

        $leon->sendFriendRequestTo($emma);
        $emma->acceptFriendRequest($leon);

        $emma->sendFriendRequestTo($charles);
        $charles->acceptFriendRequest($emma);

        $this->assertCount(2, $emma->friends()->get());
        $this->assertCount(1, $leon->friends()->get());
        $this->assertCount(1, $leon->friends()->get());
    }
}
