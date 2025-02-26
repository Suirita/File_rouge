<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $branches = [
      [
        'name' => 'Francais',
      ],
      [
        'name' => 'Englais',
      ],
      [
        'name' => 'Programmation',
      ],
      [
        'name' => 'Soft skills',
      ],
      [
        'name' => 'Travail en équipe',
      ],
    ];

    foreach ($branches as $branch) {
      Branch::create($branch);
    }
  }
}
