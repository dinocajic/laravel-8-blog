<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class BlogPostManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_blog_post_can_be_created_by_authenticated_user_only()
    {
        $response = $this->actingAs($user = User::factory()->create())
             ->post('/posts', $this->data());

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
                $this->data(),
                ['title' => '']
            ));

        $response->assertSessionHasErrors('title');
        $this->assertCount(0, Post::all());
    }

    public function test_the_post_excerpt_is_required()
    {
        $response = $this->actingAs($user = User::factory()->create())
            ->post('/posts', array_merge(
                $this->data(),
                ['excerpt' => '']
            ));

        $response->assertSessionHasErrors('excerpt');
        $this->assertCount(0, Post::all());
    }

    public function test_the_post_body_is_required()
    {
        $response = $this->actingAs($user = User::factory()->create())
            ->post('/posts', array_merge(
                $this->data(),
                ['body' => '']
            ));

        $response->assertSessionHasErrors('body');
        $this->assertCount(0, Post::all());
    }

    public function test_the_title_must_be_unique()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['title' => 'Not a unique title']
             ))
             ->assertOk();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['title' => 'Not a unique title']
             ))
             ->assertSessionHasErrors('title');
    }

    public function test_the_title_must_be_at_least_five_characters_in_length()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['title' => 'Hey']
             ))
             ->assertSessionHasErrors('title');

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['title', 'Hello']
             ))
             ->assertSessionHasNoErrors();
    }

    public function test_the_title_must_be_less_than_256_characters_in_length()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['title' => Str::random(255)]
             ))
             ->assertSessionHasNoErrors();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['title' => Str::random(256)]
             ))
             ->assertSessionHasErrors('title');
    }

    public function test_the_excerpt_must_be_at_least_100_characters_in_length()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['excerpt' => Str::random(99)]
             ))
             ->assertSessionHasErrors('excerpt');

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['excerpt' => Str::random(100)]
             ))
             ->assertSessionHasNoErrors();
    }

    public function test_the_excerpt_must_be_less_then_500_characters_in_length()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['excerpt' => Str::random(500)]
             ))
             ->assertSessionHasNoErrors();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['excerpt' => Str::random(501)]
             ))
             ->assertSessionHasErrors('excerpt');
    }

    public function test_the_body_must_be_at_least_100_characters_in_length()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['body' => Str::random(100)]
             ))
             ->assertSessionHasNoErrors();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['body' => Str::random(99)]
             ))
             ->assertSessionHasErrors('body');
    }

    public function test_the_body_must_be_less_then_50000_characters_in_length()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['body' => Str::random(50000)]
             ))
             ->assertSessionHasNoErrors();

        $this->actingAs($user)
             ->post('/posts', array_merge(
                 $this->data(),
                 ['body' => Str::random(50001)]
             ))
             ->assertSessionHasErrors('body');
    }

    private function data()
    {
        return [
            'title' => Str::random(120),
            'excerpt' => Str::random(120),
            'body' => Str::random(500),
        ];
    }
}
