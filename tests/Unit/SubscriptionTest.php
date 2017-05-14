<?php

namespace Tests\Unit\Relationship;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function user_can_follow_another_user () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->follow($emma);

        $this->assertTrue($leon->isFollowerOf($emma));
        $this->assertTrue($emma->isFollowedBy($leon));
        $this->assertTrue($emma->hasSubscriptionWith($leon));
        $this->assertTrue($leon->hasSubscriptionWith($emma));
    }

    /** @test */
    public function user_can_unfollow_another_user () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->follow($emma);
        $leon->unfollow($emma);

        $this->assertFalse($leon->isFollowerOf($emma));
    }

    /** @test */
    public function recipient_can_cancel_subscription_of_the_sender () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->follow($emma);
        $emma->unfollow($leon);

        $this->assertFalse($leon->isFollowerOf($emma));
    }

    /** @test */
    public function user_can_not_follow_himself () {
        $leon = factory(User::class)->create();

        $leon->follow($leon);

        $this->assertCount(0, $leon->followers);
    }

    /** @test */
    public function user_can_not_follow_of_another_user_twice () {
        [$emma, $leon] = factory(User::class, 2)->create();

        $leon->follow($emma);
        $leon->follow($emma);

        $this->assertCount(1, $emma->followers);
        $this->assertTrue($leon->isFollowerOf($emma));
    }

    /** @test */
    public function it_can_count_number_of_followers () {
        [$emma, $leon, $john] = factory(User::class, 3)->create();

        $leon->follow($emma);
        $john->follow($emma);

        $this->assertEquals(2, $emma->selectFollowersCount());
        $this->assertEquals(0, $leon->selectFollowersCount());
        $this->assertEquals(0, $john->selectFollowersCount());
    }

    /** @test */
    public function it_can_count_number_of_followed_users () {
        [$leon, $emma] = factory(User::class, 2)->create();

        $leon->follow($emma);
        $emma->follow($leon);

        $this->assertEquals(1, $leon->selectFollowedUsersCount());
        $this->assertEquals(0, $emma->selectFollowedUsersCount());
    }

    /** @test */
    public function it_can_count_number_of_all_subscription_partners () {
        [$leon, $emma, $john] = factory(User::class, 3)->create();

        $leon->follow($emma);
        $emma->follow($leon);
        $emma->follow($john);

        $this->assertEquals(1, $leon->selectSubcriptionPartnersCount());
        $this->assertEquals(2, $emma->selectSubcriptionPartnersCount());
        $this->assertEquals(1, $john->selectSubcriptionPartnersCount());
    }

    /** @test */
    public function followed_users_list_contains_users_that_have_been_subcribed () {
        [$leon, $emma] = factory(User::class, 2)->create();

        $leon->follow($emma);

        $this->assertTrue($leon->followedUsers->contains($emma));
    }

    /** @test */
    public function followers_contain_users_that_follow_another_users () {
        [$leon, $emma] = factory(User::class, 2)->create();

        $leon->follow($emma);

        $this->assertTrue($emma->followers->contains($leon));
    }

    /** @test */
    public function subcription_partners_contain_both_followers_and_followed_users () {
        [$leon, $emma, $john] = factory(User::class, 3)->create();

        $leon->follow($emma);
        $emma->follow($john);

        $this->assertCount(2, $partners = $emma->subscriptionPartners()->get());
        $this->assertTrue($partners->contains($john));
        $this->assertTrue($partners->contains($leon));
    }
}
