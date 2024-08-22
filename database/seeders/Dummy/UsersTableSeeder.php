<?php

namespace Database\Seeders\Dummy;

use App\Enums\PermissionType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        

        User::where('id', 1)->update(['photo' => 'uploads/avatars/XzA1MjM1MjEuanBn.jpg']);
        // $roles = PermissionType::getRoles();

        // foreach($roles as $role)
        // {
        //     Role::create(['name' => $role]);
        // }
        //Role::create(PermissionType::getRolesForDatabase());
        //Permission::insert(PermissionType::getListForDatabase());

        // User::factory()->admin()->state([
        //     'email' => 'admin@demo.com',
        //     'photo' => 'uploads/avatars/XzA1MjM1MjEuanBn.jpg',
        // ])->create();

        User::factory()->customer()->state([
            'email' => 'customer@demo.com',
            'photo' => 'uploads/avatars/XzA3Mjk0MjUuanBn.jpg',
        ])->create();

        User::factory()->author()->state([
            'email' => 'writer@demo.com',
            'photo' => 'uploads/avatars/5eac6d75500a4_1588358517.png',
        ])->create();
        
        User::factory()->author()->state([
            'email' => 'writer2@demo.com',
            'photo' => 'uploads/avatars/XzAzMTUyNzkuanBn.jpg',
        ])->create();


        User::factory()->admin()->state([
            'email' => 'editor@demo.com',
            'photo' => 'uploads/avatars/5eac6d75500a4_1588358517.png',
        ])->create();

        // User::factory()->count(2)->admin()->create();

        User::factory()->count(5)->customer()->create();

        User::factory()->count(5)->author()->create();
    }
}
