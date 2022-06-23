<?php

namespace Tests\Feature\Controller;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCaseController;

class UserControllerTest extends TestCaseController
{
    /**
     * Setup environment testing.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->url = route('user.index');
    }

    /**
     * Test UserController@index. Should has these attributes.
     *
     * @return void
     */
    public function testIndexAttributes()
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::SUPERADMIN->value);

        $this->jsonGet()->assertOk()->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has(
                    'data',
                    1,
                    fn ($json) =>
                    $json->where('id', $user->id)
                        ->where('name', $user->name)
                        ->where('email', $user->email)
                        ->has(
                            'role',
                            fn (AssertableJson $json) =>
                            $json->where('id', $user->roles[0]->id)
                                ->where('name', $user->roles[0]->name)
                        )
                        ->missing('password')
                        ->where('email_verified_at', $user->email_verified_at->toJSON())
                        ->where('created_at', $user->created_at->toJSON())
                        ->where('updated_at', $user->created_at->toJSON())
                        ->where('deleted_at', null)
                )
                ->has(
                    'meta',
                    fn ($json) =>
                    $json->where('current_page', 1)
                        ->where('from', 1)
                        ->where('last_page', 1)
                        ->has('links')
                        ->where('path', $this->url)
                        ->where('per_page', 15)
                        ->where('to', 1)
                        ->where('total', 1)
                )
                ->has('links')
        );
    }

    /**
     * Test UserController@index. Should ordered by name ascending.
     *
     * @return void
     */
    public function testIndexOrder()
    {
        $users = User::factory()->count(3)->state(new Sequence(
            ['name' => 'Cindy'],
            ['name' => 'Anita'],
            ['name' => 'Bella'],
        ))->create();

        $users->each(function (User $user) {
            $user->assignRole(Roles::SUPERADMIN->value);
        });

        $this->jsonGet()->assertOk()->assertJson([
            'data' => [
                ['name' => 'Anita'],
                ['name' => 'Bella'],
                ['name' => 'Cindy'],
            ],
        ])->assertJsonCount(3, 'data');
    }

    /**
     * Test UserController@store.
     *
     * @return void
     */
    public function testStore()
    {
        $form = [
            'name' => 'Rizky',
            'email' => 'rizky@example.com',
            'role' => Roles::SUPERADMIN->value,
        ];

        $response = $this->jsonPost($form)->assertCreated();
        $user = User::orderByDesc('id')->first();
        $response->assertJson([
            'id' => $user->id,
            'name' => $form['name'],
            'email' => $form['email'],
            'role' => [
                'id' => $user->roles[0]->id,
                'name' => $form['role'],
            ],
            'created_at' => now()->toJSON(),
            'updated_at' => now()->toJSON(),
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $form['name'],
            'email' => $form['email'],
        ]);

        // check role
        $this->assertTrue($user->hasRole($form['role']));

        // check default password
        $this->assertTrue(Hash::check('123456789', $user->password));
    }

    /**
     * Test UserController@show.
     *
     * @return void
     */
    public function testShow()
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::SUPERADMIN->value);

        $this->jsonGet($user->id)->assertOk()->assertJson(
            fn (AssertableJson $json) =>
            $json->where('id', $user->id)
                ->where('name', $user->name)
                ->where('email', $user->email)
                ->where('email_verified_at', $user->email_verified_at->toJSON())
                ->where('created_at', $user->created_at->toJSON())
                ->where('updated_at', $user->updated_at->toJSON())
                ->where('deleted_at', null)
                ->has(
                    'role',
                    fn ($json) =>
                    $json->where('id', $user->roles[0]->id)
                        ->where('name', $user->roles[0]->name)
                )
        );
    }

    /**
     * Test UserController@update
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::SUPERADMIN->value);

        $form = [
            'name' => 'Rizky',
            'email' => 'rizky@example.com',
            'role' => Roles::SUPERADMIN->value,
        ];

        $this->jsonPut($form, $user->id)->assertOk()->assertJson([
            'id' => $user->id,
            'name' => $form['name'],
            'email' => $form['email'],
            'role' => [
                'id' => $user->roles[0]->id,
                'name' => $form['role'],
            ],
            'created_at' => $user->created_at->toJSON(),
            'updated_at' => now()->toJSON(),
        ]);

        $this->assertTrue($user->hasRole($form['role']));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $form['name'],
            'email' => $form['email'],
        ]);
    }

    /**
     * Test UserController@destroy
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::SUPERADMIN->value);

        $this->jsonDelete($user->id)->assertOk()->assertJson(
            fn (AssertableJson $json) =>
            $json->where('id', $user->id)
                ->where('deleted_at', now()->toJSON())
        );

        $this->assertSoftDeleted($user);
    }
}
