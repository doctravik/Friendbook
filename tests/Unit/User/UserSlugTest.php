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
        $user = $this->createUser();

        $this->assertNotNull($slug = $user->fresh()->slug);
        $this->assertEquals('john-doe', $slug);
    }

    /** @test */
    public function it_can_create_unique_slug_for_users_with_the_same_names () {
        $userOne = $this->createUser();
        $userTwo = $this->createUser();

        $this->assertEquals('john-doe', $userOne->slug);
        $this->assertEquals('john-doe-1', $userTwo->slug);
    }

    /** @test */
    public function it_can_create_unique_slug_if_user_with_the_same_slug_was_removed_from_db () {
        $userOne = $this->createUser();
        $userTwo = $this->createUser();
        $userThree = $this->createUser();

        $userTwo->delete();
        $userForth = $this->createUser();

        $this->assertEquals('john-doe', $userOne->slug);
        $this->assertEquals('john-doe-2', $userThree->slug);
        $this->assertEquals('john-doe-3', $userForth->slug);
    }

    /** @test */
    public function it_doesnt_update_slug_if_user_update_email () {
        $user = $this->createUser();

        $user->update(['email' => 'new@email']);

        $this->assertEquals('john-doe', $user->slug);
    }

    /** @test */
    public function it_updates_slug_if_user_update_first_name () {
        $user = $this->createUser();

        $user->update(['first_name' => 'bobby']);

        $this->assertEquals('bobby-doe', $user->slug);
    }

    /** @test */
    public function it_updates_slug_if_user_update_last_name () {
        $user = $this->createUser();

        $user->update(['last_name' => 'smith']);

        $this->assertEquals('john-smith', $user->slug);
    }

    /** @test */
    public function it_updates_slug_if_user_updates_name_with_an_existing_name () {
        $userOne = factory(User::class)->create(['first_name' => 'john', 'last_name' => 'doe']);
        $userTwo = factory(User::class)->create(['first_name' => 'bobby', 'last_name' => 'doe']);

        $userTwo->update(['first_name' => 'john']);

        $this->assertEquals('john-doe', $userOne->slug);
        $this->assertEquals('john-doe-1', $userTwo->slug);
    }

    /** @test */
    public function it_can_find_user_by_slug () {
        $user = $this->createUser();

        $user = User::findBySlug($user->fresh()->slug);

        $this->assertNotNull($user);
    }

    private function createUser() {
        return factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);
    }
}
