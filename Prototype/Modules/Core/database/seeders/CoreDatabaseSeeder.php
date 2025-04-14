<?php

namespace Modules\Core\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'modify post']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('modify post');

        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo('modify post');

        Role::create(['name' => 'user']);

        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'test12345',
        ]);

        User::create([
            'name' => 'Fahd Suirita',
            'email' => 'fahd.suirita123@gmail.com',
            'password' => 'fahdsuirita26092004',
        ]);

        $testUser->assignRole('admin');
    }
}
