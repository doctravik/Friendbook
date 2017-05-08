<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserSlugTest extends TestCase {
    use DatabaseTransactions;

    /** @test */
    public function slug_is_created_after_new_user_is_registered () {
        $user = factory(User::class)->create(['id' => 1, 'name' => 'John Doe']);

        $this->assertNotNull($slug = $user->fresh()->slug);
        $this->assertEquals('john-doe-1', $slug);
    }

    /** @test */
    public function it_can_create_unique_slug_for_users_with_the_same_names () {
        $userOne = factory(User::class)->create(['name' => 'John Doe']);
        $userTwo = factory(User::class)->create(['name' => 'John Doe']);

        $this->assertNotNull($slugOne = $userOne->fresh()->slug);
        $this->assertNotNull($slugTwo = $userTwo->fresh()->slug);
        $this->assertNotEquals($slugOne, $slugTwo);
    }

    /** @test */
    public function it_can_find_user_by_slug () {
        $user = factory(User::class)->create();

        $user = User::findBySlug($user->fresh()->slug);

        $this->assertNotNull($user);
    }
}
