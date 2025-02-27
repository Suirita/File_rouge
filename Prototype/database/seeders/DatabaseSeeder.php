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
      'name' => 'fahd suirita',
      'email' => 'fahd.suirita123@gmail.com',
      'password' => 'fahdsuirita26092004',
    ]);

    $this->call(BranchSeeder::class);
    $this->call(QuestionSeeder::class);
  }
}
