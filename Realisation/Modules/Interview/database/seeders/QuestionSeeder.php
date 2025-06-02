<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            [
                'type_id' => 1,
                'title' => 'Présentation en français',
            ],
            [
                'type_id' => 2,
                'title' => 'Introduction in english',
            ],
            [
                'type_id' => 3,
                'title' => 'Time management',
            ],
            [
                'type_id' => 3,
                'title' => 'Conflict resolution',
            ],
            [
                'type_id' => 4,
                'title' => 'Teamwork',
            ],
            [
                'type_id' => 5,
                'title' => 'JavaScript generale',
            ],
        ];

        DB::table('questions')->insert($questions);
    }
}
