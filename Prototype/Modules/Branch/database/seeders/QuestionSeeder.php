<?php

namespace Modules\Branch\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\app\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Présente-vous !',
                'branch_id' => 1,
            ],
            [
                'question' => 'Comment avez-vous connu l\'institut ?',
                'branch_id' => 1,
            ],
            [
                'question' => 'Introduce yourself !',
                'branch_id' => 2,
            ],
            [
                'question' => 'Quelle est la différence entre une balise <a> et une balise <b> ?',
                'branch_id' => 3,
            ],
            [
                'question' => 'Comment centrer un div ?',
                'branch_id' => 3,
            ],
            [
                'question' => 'Comment gérez-vous le stress ?',
                'branch_id' => 4,
            ],
            [
                'question' => 'Aimez-vous travailler en équipe ?',
                'branch_id' => 5,
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
