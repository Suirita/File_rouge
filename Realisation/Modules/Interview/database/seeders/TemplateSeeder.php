<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'title' => 'Interview 2023/2024',
                'created_at' => '2023-09-01',
            ],
            [
                'title' => 'Interview 2024/2025',
                'created_at' => '2024-09-01',
            ],
        ];

        DB::table('templates')->insert($templates);
    }
}
