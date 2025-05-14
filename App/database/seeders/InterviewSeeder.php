<?php

namespace Database\Seeders;

use App\Models\Interview;
use Illuminate\Database\Seeder;

class InterviewSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    for ($i = 1; $i <= 200; $i++) {
      Interview::create([
        'enrollee_id' => $i,
      ]);
    }
  }
}
