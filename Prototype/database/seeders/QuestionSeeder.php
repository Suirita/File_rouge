<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $questions = [
      [
        'content' => 'Presentez-vous!',
        'branch_id' => 1,
      ],
      [
        'content' => 'Present your self in english!',
        'branch_id' => 2,
      ],
      [
        'content' => 'Quelle est la différence entre une balise a et une balise b?',
        'branch_id' => 3,
      ],
      [
        'content' => 'Comment gérez-vous le stress?',
        'branch_id' => 4,
      ],
      [
        'content' => 'Aimez-vous travailler en équipe',
        'branch_id' => 5,
      ],
    ];

    foreach ($questions as $question) {
      \App\Models\Question::create($question);
    }
  }
}
