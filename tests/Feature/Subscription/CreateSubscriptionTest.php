<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateSubscriptionTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function unauthenticated_user_cannot_follow_of_another_user () {
        [$charles, $emma] = factory(User::class, 2)->create();
        
        $response = $this->json('post', "/follow/{$emma->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_user_can_follow_of_another_user () {
        [$charles, $emma] = factory(User::class, 2)->create();

        $response = $this->actingAs($charles)->json('post', "/follow/{$emma->id}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('followers', [
            'follower_id' => $charles->id,
            'followed_id' => $emma->id
        ]);
    }
}
