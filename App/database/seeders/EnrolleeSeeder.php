<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enrollee;
use Database\Factories\EnrolleeFactory;

class EnrolleeSeeder extends Seeder
{

  public function run()
  {
    EnrolleeFactory::times(200)->create();
  }
}
