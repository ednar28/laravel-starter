<?php

namespace Tests\Feature\Controller;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * Test AuthController@login
     *
     * @return void
     */
    public function testLogin()
    {
        $user = User::factory()->create();

        $form = [
            'email' => $user->email,
            'password' => 'password',
            'remember' => true,
        ];

        $this->postJson(route('login'), $form)->assertOk()->assertJson(fn (AssertableJson $json) =>
            $json->has('token')
                ->has('user', fn (AssertableJson $json) =>
                    $json->where('id', $user->id)
                        ->where('name', $user->name)
                        ->where('email', $user->email)
                        ->where('email_verified_at', $user->email_verified_at->toJSON())
                        ->where('created_at', $user->created_at->toJSON())
                        ->where('updated_at', $user->updated_at->toJSON())
                        ->where('deleted_at', null)
            )
        );

        $this->assertAuthenticatedAs($user);
    }
}
