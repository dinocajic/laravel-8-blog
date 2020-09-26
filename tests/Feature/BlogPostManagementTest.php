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
