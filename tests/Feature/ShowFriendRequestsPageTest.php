<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowFriendRequestsPageTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function guest_can_see_received_friend_requests_of_the_given_user () {
        $user = factory(User::class)->create();

        $response = $this->get("{$user->slug}/friends/requests/received");

        $response->assertStatus(200);
        $response->assertViewHas(['user', 'friendRequestsReceived']);
    }

    /** @test */
    public function guest_can_see_friend_requests_sent_by_the_given_user () {
        $user = factory(User::class)->create();

        $response = $this->get("{$user->slug}/friends/requests/sent");

        $response->assertStatus(200);
        $response->assertViewHas(['user', 'friendRequestsSent']);
    }
}
