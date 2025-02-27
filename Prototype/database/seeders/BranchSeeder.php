<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BranchSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $branches = [
      [
        'name' => 'Francer',
      ],
      [
        'name' => 'English',
      ],
      [
        'name' => 'Programming',
      ],
      [
        'name' => 'Soft skills',
      ],
      [
        'name' => 'Travail en équipe',
      ],
    ];

    foreach ($branches as $branch) {
      \App\Models\Branch::create($branch);
    }
  }
}
