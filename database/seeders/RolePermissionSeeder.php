<?php

namespace Database\Seeders;

use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->insertPermissions(Permissions::values());

        $this->createRoleIncludePermissions(
            Roles::SUPERADMIN->value,
            array_values(Permissions::values())
        );
    }

    /**
     * Insert permissions
     *
     * @param array $permissions
     * @return void
     */
    private function insertPermissions(array $permissions)
    {
        $permissions = array_map(function ($permission) {
            return [
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $permissions);

        Permission::insert($permissions);
    }

    /**
     * create role and give permissions
     *
     * @param string $name
     * @param array $permissions
     * @param string $guardName
     * @return void
     */
    private function createRoleIncludePermissions(
        string $name,
        array $permissions = [],
        string $guardName = 'web'
    ) {
        $role = Role::create(['name' => $name, 'guard_name' => $guardName]);

        // has roles
        if (count($permissions) > 0) {
            $role->givePermissionTo($permissions);
        }
    }
}
