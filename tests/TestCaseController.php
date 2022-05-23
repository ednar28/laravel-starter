<?php

namespace Tests;

use App\Enums\Roles;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

class TestCaseController extends TestCase
{
    protected User $user;

    /**
     * Setup environment testing.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->user = User::factory()->create();
        $this->user->assignRole(Roles::SUPERADMIN->value);
        $this->actingAs($this->user);
    }
}
