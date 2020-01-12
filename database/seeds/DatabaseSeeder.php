<?php

use App\Models\User;
use Illuminate\Database\Seeder;

use App\Models\Role_Permission\Acl;
use Illuminate\Support\Facades\Hash;
use App\Models\Role_Permission\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@project.dev',
            'password' => Hash::make('123456'),
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@project.dev',
            'password' => Hash::make('123456'),
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@project.dev',
            'password' => Hash::make('123456'),
        ]);
        $user = User::create([
            'name' => 'User',
            'email' => 'user@project.dev',
            'password' => Hash::make('123456'),
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@project.dev',
            'password' => Hash::make('123456'),
        ]);

        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(Acl::ROLE_EDITOR);
        $userRole = Role::findByName(Acl::ROLE_USER);
        $visitorRole = Role::findByName(Acl::ROLE_VISITOR);
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
        $visitor->syncRoles($visitorRole);
        $this->call(UsersTableSeeder::class);
    }
}
