<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerSeeder extends Seeder
{
    public function run()
    {
        DB::table('trainers')->insert([
            ['name' => 'Alice Martin', 'email' => 'alice@example.com', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bob Dupont',   'email' => 'bob@example.com',   'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Charlie Durand', 'email' => 'charlie@example.com', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dana Leroy',     'email' => 'dana@example.com',    'created_at' => now(), 'updated_at' => now()],

        ]);
    }
}
