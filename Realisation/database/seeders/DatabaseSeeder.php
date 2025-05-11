<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\database\seeders\CoreDatabaseSeeder;
use Modules\Interview\database\seeders\InterviewDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CoreDatabaseSeeder::class,
            InterviewDatabaseSeeder::class,
        ]);
    }
}
