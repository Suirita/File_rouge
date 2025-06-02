<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'title' => 'Francais',
                'created_at' => '2023-01-01 00:00:00',
            ],
            [
                'title' => 'Anglais',
                'created_at' => '2023-01-01 00:00:00',
            ],
            [
                'title' => 'Soft Skills',
                'created_at' => '2023-01-01 00:00:00',
            ],
            [
                'title' => 'Teamwork',
                'created_at' => '2023-02-01 00:00:00',
            ],
            [
                'title' => 'Technique',
                'created_at' => '2023-03-01 00:00:00',
            ],
        ];

        DB::table('types')->insert($types);
    }
}
