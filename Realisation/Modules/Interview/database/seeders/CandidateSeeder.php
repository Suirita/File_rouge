<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateSeeder extends Seeder
{
    public function run()
    {
        DB::table('candidates')->insert(
            [
                ['name' => 'Alice Martin',    'email' => 'alice.martin@example.com',    'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Bob Dupont',       'email' => 'bob.dupont@example.com',       'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Charlie Durand',   'email' => 'charlie.durand@example.com',   'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Dana Leroy',       'email' => 'dana.leroy@example.com',       'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Evelyn Smith',     'email' => 'evelyn.smith@example.com',     'created_at' => now(), 'updated_at' => now()],
                ['name' => 'François Moreau',  'email' => 'francois.moreau@example.com',  'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Gabriel Bernard',  'email' => 'gabriel.bernard@example.com',  'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Hannah Leroy',     'email' => 'hannah.leroy@example.com',     'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Isabelle Doe',     'email' => 'isabelle.doe@example.com',     'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Julien Petit',     'email' => 'julien.petit@example.com',     'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Karim Haddad',     'email' => 'karim.haddad@example.com',     'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Laura Dubois',     'email' => 'laura.dubois@example.com',     'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Marc Fontaine',    'email' => 'marc.fontaine@example.com',    'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Nadia Roux',       'email' => 'nadia.roux@example.com',       'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Olivier Blanc',    'email' => 'olivier.blanc@example.com',    'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Pauline Roy',      'email' => 'pauline.roy@example.com',      'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Quentin Rousseau', 'email' => 'quentin.rousseau@example.com', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Rania Kheir',      'email' => 'rania.kheir@example.com',      'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Samuel Girard',    'email' => 'samuel.girard@example.com',    'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Tara Michel',      'email' => 'tara.michel@example.com',      'created_at' => now(), 'updated_at' => now()],
            ]
        );
    }
}
