<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluatorSeeder extends Seeder
{
    public function run()
    {
        $evaluators = [
            [
                'name' => 'Alice Martin',
                'user_id' => '2'
            ],
            [
                'name' => 'Bob Dupont',
                'user_id' => '3'
            ],
            [
                'name' => 'Charlie Durand',
                'user_id' => '4'
            ],
            [
                'name' => 'Dana Leroy',
                'user_id' => '5'
            ],
        ];

        DB::table('evaluators')->insert($evaluators);
    }
}
