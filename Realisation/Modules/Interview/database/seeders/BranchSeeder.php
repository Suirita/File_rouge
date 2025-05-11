<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run()
    {
        DB::table('branches')->insert([
            ['interview_id' => 1, 'title' => 'French', 'created_at' => now(), 'updated_at' => now()],
            ['interview_id' => 1, 'title' => 'English', 'created_at' => now(), 'updated_at' => now()],
            ['interview_id' => 1, 'title' => 'SoftSkills', 'created_at' => now(), 'updated_at' => now()],
            ['interview_id' => 1, 'title' => 'Programming', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
