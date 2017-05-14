<?php

namespace Tests\Feature\Friendship;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RemoveFriendTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_remove_friend_from_friend_list() {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->invite($bobby);
        $bobby->beFriend($john);

        $response = $this->json('delete', '/friends/{$john->id}');

        $response->assertStatus(401);
        $this->assertTrue($john->isFriendWith($bobby));
    }

    /** @test */
    public function authenticated_user_can_remove_friend_from_friend_list () {
        [$john, $bobby] = factory(User::class, 2)->create();
        $john->invite($bobby);
        $bobby->beFriend($john);

        $response = $this->actingAs($bobby)->json('delete', "/friends/{$john->id}");

        $response->assertStatus(200);
        $this->assertFalse($john->isFriendWith($bobby));
    }
}
