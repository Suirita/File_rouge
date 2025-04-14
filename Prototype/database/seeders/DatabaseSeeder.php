<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Modules\Branch\app\Models\Branch;
use Spatie\Permission\Models\Permission;
use Modules\Core\database\seeders\CoreDatabaseSeeder;
use Modules\Branch\database\seeders\BranchDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CoreDatabaseSeeder::class,
            BranchDatabaseSeeder::class,
        ]);
    }
}
