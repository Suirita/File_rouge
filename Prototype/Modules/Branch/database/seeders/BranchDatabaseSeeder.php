<?php

namespace Modules\Branch\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\database\seeders\BranchSeeder;
use Modules\Branch\database\seeders\QuestionSeeder;

class BranchDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(BranchSeeder::class);
        $this->call(QuestionSeeder::class);
    }
}
