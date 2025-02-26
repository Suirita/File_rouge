<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    User::factory()->create([
      'name' => 'SUIRITA Fahd',
      'email' => 'fahd.suirita@gmail.com',
      'password' => '$2y$12$2qjhXHrkHMlJSjyahMoGWemjADgKuVoIW.dudChIHqgpuQzIT32.S',
    ]);

    $this->call(BranchSeeder::class);
    $this->call(QuestionSeeder::class);
    $this->call(EnrolleeSeeder::class);
    $this->call(AnswerSeeder::class);
    $this->call(InterviewSeeder::class);
  }
}
