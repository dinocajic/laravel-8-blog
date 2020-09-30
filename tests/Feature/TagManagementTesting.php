<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagManagementTesting extends TestCase
{
    /**
     * The tags that you would find on a post: #laravel #laravel-8, etc
     *
     * @todo CRUD testing
     * @todo Tag just has a string('name') column
     * @todo Need pivot table to create association between posts and tags: post_tag
     *       Pivot table needs to have post_id and tag_id columns
     *       Setup a unique key so that you don't have duplicates: $this->unique(['post_id', 'tag_id']);
     *       Setup foreign key constraints: $this->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')
     *                                      $this->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade')
     * @todo Tag model needs to have posts() { belongsToMany('Post::class') } and the Post model needs to have tags() too
     */
}
