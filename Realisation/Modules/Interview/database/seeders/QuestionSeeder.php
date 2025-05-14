<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Interview\app\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            [
                'branch_id' => 1,
                'text' => 'Présentation en français',
            ],
            [
                'branch_id' => 2,
                'text' => 'Introduction in english',
            ],
            [
                'branch_id' => 3,
                'text' => 'Time management',
            ],
            [
                'branch_id' => 3,
                'text' => 'Conflict resolution',
            ],
            [
                'branch_id' => 4,
                'text' => 'Teamwork',
            ],
            [
                'branch_id' => 5,
                'text' => 'JavaScript generale',
            ],
            [
                'branch_id' => 6,
                'text' => 'PHP generale',
            ],

        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
