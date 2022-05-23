<?php

namespace Tests\Feature\Request;

use App\Enums\Roles;
use App\Models\User;
use Tests\TestCaseController;

class UserRequestTest extends TestCaseController
{
    private User $admin;

    /**
     * Setup environment testing.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->url = route('user.index');
        $this->admin = User::factory()->create();

        $this->admin->assignRole(Roles::SUPERADMIN->value);
    }

    /**
     * Test error message when field is not provided or empty.
     *
     * @return void
     */
    public function testRequired()
    {
        $form = [];

        $this->jsonPost($form)->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'role' => 'The role field is required.',
        ]);
        $this->jsonPut($form, $this->admin->id)->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'email' => 'The email field is required.',
            'role' => 'The role field is required.',
        ]);
    }

    /**
     * Test error message when field is not a string.
     *
     * @return void
     */
    public function testString()
    {
        $form = [
            'name' => ['key' => 'value'],
            'email' => ['key' => 'value'],
            'role' => ['key' => 'value'],
        ];

        $this->jsonPost($form)->assertJsonValidationErrors([
            'name' => 'The name must be a string.',
            'email' => 'The email must be a string.',
        ]);
        $this->jsonPut($form, $this->admin->id)->assertJsonValidationErrors([
            'name' => 'The name must be a string.',
            'email' => 'The email must be a string.',
        ]);
    }

    /**
     * Test error message when field is not a email.
     *
     * @return void
     */
    public function testEmail()
    {
        $form = [
            'email' => 'some text',
        ];

        $this->jsonPost($form)->assertJsonValidationErrors([
            'email' => 'The email must be a valid email address.',
        ]);
        $this->jsonPut($form, $this->admin->id)->assertJsonValidationErrors([
            'email' => 'The email must be a valid email address.',
        ]);
    }

    /**
     * Test error message when field is too long.
     *
     * @return void
     */
    public function testMaxLength()
    {
        $randomString = \Illuminate\Support\Str::random(500);
        $form = [
            'name' => $randomString,
            'email' => $randomString,
        ];

        $this->jsonPost($form)->assertJsonValidationErrors([
            'name' => 'The name must not be greater than 255 characters.',
            'email' => 'The email must not be greater than 255 characters.',
        ]);
        $this->jsonPut($form, $this->admin->id)->assertJsonValidationErrors([
            'name' => 'The name must not be greater than 255 characters.',
            'email' => 'The email must not be greater than 255 characters.',
        ]);
    }

    /**
     * Test error message when field is not exist in database.
     *
     * @return void
     */
    public function testExist()
    {
        $form = [
            'role' => 'some string',
        ];

        $this->jsonPost($form)->assertJsonValidationErrors([
            'role' => 'The selected role is invalid.',
        ]);
        $this->jsonPut($form, $this->admin->id)->assertJsonValidationErrors([
            'role' => 'The selected role is invalid.',
        ]);
    }
}
