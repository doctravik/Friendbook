<?php

namespace Tests\Unit\Subscription;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function user_can_be_follower_of_another_user () {
        [$john, $emma] = factory(User::class, 2)->create();

        $john->follow($emma);

        $this->assertTrue($john->isFollowerOf($emma));
        $this->assertTrue($emma->isFollowedBy($john));
    }

    /** @test */
    public function user_cannot_be_follower_of_another_user_twice () {
        [$john, $emma] = factory(User::class, 2)->create();
        $john->follow($emma);

        $john->follow($emma);

        $this->assertCount(1, $emma->followers);
    }

    /** @test */
    public function follower_can_cancel_his_subscription () {
        [$john, $emma] = factory(User::class, 2)->create();

        $john->follow($emma);
        $john->unfollow($emma);

        $this->assertFalse($john->isFollowerOf($emma));
        $this->assertFalse($emma->isFollowedBy($john));
    }

    /** @test */
    public function it_can_count_number_of_followers () {
        [$leon, $emma, $charles] = factory(User::class, 3)->create();

        $leon->follow($emma);
        $emma->follow($leon);
        $charles->follow($emma);

        $this->assertEquals(1, $leon->selectFollowersCount());
        $this->assertEquals(2, $emma->selectFollowersCount());
        $this->assertEquals(0, $charles->selectFollowersCount());
    }

    /** @test */
    public function it_can_count_number_of_followed_users () {
        [$leon, $emma, $charles] = factory(User::class, 3)->create();

        $leon->follow($emma);
        $emma->follow($leon);
        $charles->follow($emma);

        $this->assertCount(1, $leon->followedUsers);
        $this->assertCount(1, $emma->followedUsers);
        $this->assertCount(1, $charles->followedUsers);
    }
}
