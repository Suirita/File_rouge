<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Interview\app\Models\Branch;
use Modules\Interview\app\Models\Interview;

class InterviewBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();
        $interviews = Interview::all();

        for ($i = 0; $i < $branches->count(); $i++) {
            $branches[$i]->interviews()->attach($interviews[0]->id);
        }
    }
}
