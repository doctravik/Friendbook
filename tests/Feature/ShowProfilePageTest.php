<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowProfilePageTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function guest_can_see_profile_page_of_the_user () {
        $john = factory(User::class)->create();

        $response = $this->get("/$john->slug");

        $response->assertStatus(200);
    }
}
