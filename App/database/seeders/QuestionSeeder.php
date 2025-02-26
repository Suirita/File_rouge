<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $questions = [
      [
        'title' => 'Question 1',
        'description' => 'Description 1',
        'branch_id' => 1,
      ],
      [
        'title' => 'Question 2',
        'description' => 'Description 2',
        'branch_id' => 1,
      ],
      [
        'title' => 'Question 3',
        'description' => 'Description 3',
        'branch_id' => 2,
      ],
      [
        'title' => 'Question 4',
        'description' => 'Description 4',
        'branch_id' => 2,
      ],
      [
        'title' => 'Question 5',
        'description' => 'Description 5',
        'branch_id' => 3,
      ],
      [
        'title' => 'Question 6',
        'description' => 'Description 6',
        'branch_id' => 4,
      ],
      [
        'title' => 'Question 7',
        'description' => 'Description 7',
        'branch_id' => 5,
      ],
    ];

    foreach ($questions as $question) {
      Question::create($question);
    }
  }
}
