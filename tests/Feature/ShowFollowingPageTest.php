<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowFollowingPageTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function guest_can_see_followed_users_of_the_given_user () {
        $john = factory(User::class)->create();

        $response = $this->get("/$john->slug/following");

        $response->assertStatus(200);
        $response->assertViewHas(['user', 'followedUsers']);
    }
}
