<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BlogPostManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_blog_post_can_be_created_by_authenticated_user_only()
    {
        $response = $this->actingAs($user = User::factory()->create())
             ->post('/posts', $this->data($user->id));

        $this->assertCount(1, Post::all());
        $response->assertOk();

        Auth::logout();

        $this->post('/posts', $this->data())
             ->assertRedirect('/login');
    }

    public function test_the_post_title_is_required()
    {
        $response = $this->actingAs($user = User::factory()->create())
            ->post('/posts', array_merge(
                $this->data($user->id),
                ['title' => '']
            ));

        $response->assertSessionHasErrors('title');
        $this->assertCount(0, Post::all());
    }

    public function test_the_post_excerpt_is_required()
    {
        $response = $this->actingAs($user = User::factory()->create())
            ->post('/posts', array_merge(
                $this->data($user->id),
                ['excerpt' => '']
            ));

        $response->assertSessionHasErrors('excerpt');
        $this->assertCount(0, Post::all());
    }

    public function test_the_post_body_is_required()
    {
        $response = $this->actingAs($user = User::factory()->create())
            ->post('/posts', array_merge(
                $this->data($user->id),
                ['body' => '']
            ));

        $response->assertSessionHasErrors('body');
        $this->assertCount(0, Post::all());
    }

    public function test_the_post_user_id_is_required()
    {
        $response = $this->actingAs($user = User::factory()->create())
            ->post('/posts', array_merge(
                $this->data($user->id),
                ['user_id' => '']
            ));

        $response->assertSessionHasErrors('user_id');
        $this->assertCount(0, Post::all());
    }

    public function test_the_title_must_be_unique()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data($user->id),
                 ['title' => 'Not a unique title']
             ))
             ->assertOk();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data($user->id),
                 ['title' => 'Not a unique title']
             ))
             ->assertSessionHasErrors('title');
    }

    public function test_the_title_must_be_at_least_five_characters_in_length()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data($user->id),
                 ['title' => 'Hey']
             ))
             ->assertSessionHasErrors('title');

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data($user->id),
                 ['title', 'Hello']
             ))
             ->assertSessionHasNoErrors();
    }

    private function data($user = 1)
    {
        return [
            'title' => $this->faker->unique()->sentence,
            'excerpt' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'user_id' => $user,
        ];
    }
}
