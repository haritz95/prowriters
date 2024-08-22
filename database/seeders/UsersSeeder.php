<?php

namespace Database\Seeders;

use App\Enums\PermissionType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = PermissionType::getRoles();

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        User::factory()->admin()->state([
            'email'      => 'admin@demo.com',
            'first_name' => 'Super',
            'last_name'  => 'Admin',
        ])->create();        

    }
}
