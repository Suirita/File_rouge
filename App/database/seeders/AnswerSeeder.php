<?php

namespace Database\Seeders;

use App\Models\Answer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnswerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    for ($i = 1; $i <= 200; $i++) {
      for ($j = 1; $j <= 7; $j++) {
        Answer::create([
          'question_id' => $j,
          'enrollee_id' => $i,
          'score' => rand(1, 5),
        ]);
      }
    }
  }
}
