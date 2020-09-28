<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    /**
     * Users are managed by Jetstream so no significant testing is necessary
     *
     * @todo Blog posts are deleted when user is deleted (foreign key restrictions)
     * @todo A welcome email is sent out when the user is created
     * @todo A closing email is sent out when the user is deleted
     */
}
