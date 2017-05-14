<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SelectFriendRequestsTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function guest_cannot_see_received_friend_requests () {
        $user = factory(User::class)->create();

        $response = $this->json('get', '/friends/requests/received');

        $response->assertStatus(401);
    }

    /** @test */
    public function guest_cannot_see_sent_friend_requests () {
        $user = factory(User::class)->create();

        $response = $this->json('get', '/friends/requests/sent');

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_see_friend_requests_received_from_another_user () {
        [$emma, $leon] = factory(User::class, 2)->create();
        $leon->invite($emma);

        $response = $this->actingAs($emma)->json('get', '/friends/requests/received');

        $response->assertStatus(200);
        $response->assertJsonFragment(
            array_only($leon->toArray(), ['id', 'first_name', 'last_name'])
        );
    }

    /** @test */
    public function authenticated_user_can_see_friend_requests_sent_to_another_user () {
        [$emma, $leon] = factory(User::class, 2)->create();
        $leon->invite($emma);

        $response = $this->actingAs($leon)->json('get', '/friends/requests/sent');

        $response->assertStatus(200);
        $response->assertJsonFragment(
            array_only($emma->toArray(), ['id', 'first_name', 'last_name'])
        );
    }
}
