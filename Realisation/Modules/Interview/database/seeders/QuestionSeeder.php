<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        DB::table('questions')->insert([
            ['branch_id' => 1, 'text' => 'présentez-vous en français',   'created_at' => now(), 'updated_at' => now()],
            ['branch_id' => 2, 'text' => 'introduce yourself in french',      'created_at' => now(), 'updated_at' => now()],
            ['branch_id' => 3, 'text' => 'how do you manage your time?',      'created_at' => now(), 'updated_at' => now()],
            ['branch_id' => 3, 'text' => 'how do you manage Stress?',      'created_at' => now(), 'updated_at' => now()],
            ['branch_id' => 4, 'text' => 'how to loop around array in JavaScript?',      'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
