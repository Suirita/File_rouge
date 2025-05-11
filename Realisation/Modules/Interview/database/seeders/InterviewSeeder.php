<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterviewSeeder extends Seeder
{
    public function run()
    {
        DB::table('interviews')->insert([
            ['date' => '2025-05-15', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
