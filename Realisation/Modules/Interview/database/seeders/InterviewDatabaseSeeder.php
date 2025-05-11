<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;

class InterviewDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            TrainerSeeder::class,
            CandidateSeeder::class,
            InterviewSeeder::class,
            BranchSeeder::class,
            QuestionSeeder::class,
            ParticipationSeeder::class,
            QuestionResponseSeeder::class,
            QuestionEvaluationSeeder::class,
        ]);
    }
}
