<?php

namespace Modules\Core\database\seeders;

use Modules\Core\app\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Role::create(['name' => 'admin']);

        $adminUser = User::create([
            'name' => 'Fahd Suirita',
            'email' => 'fahd.suirita123@gmail.com',
            'password' => 'fahdsuirita26092004',
        ]);
        $adminUser->assignRole('admin');


        Role::create(['name' => 'evaluator']);

        $evaluatorUser1 = User::create([
            'name' => 'Alice Martin',
            'email' => 'alice.martin@gmail.com',
            'password' => 'alicemartin',
        ]);
        $evaluatorUser1->assignRole('evaluator');

        $evaluatorUser2 = User::create([
            'name' => 'Bob Dupont',
            'email' => 'bob.dupont@gmail.com',
            'password' => 'bobdupont',
        ]);
        $evaluatorUser2->assignRole('evaluator');

        $evaluatorUser3 = User::create([
            'name' => 'Charlie Durand',
            'email' => 'charlie.durand@gmail.com',
            'password' => 'charliedurand',
        ]);
        $evaluatorUser3->assignRole('evaluator');

        $evaluatorUser4 = User::create([
            'name' => 'Dana Leroy',
            'email' => 'dana.leroy@gmail.com',
            'password' => 'danaleroy',
        ]);
        $evaluatorUser4->assignRole('evaluator');
    }
}
