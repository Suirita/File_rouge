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
            CandidateSeeder::class,
            EvaluatorSeeder::class,
            TemplateSeeder::class,
            InterviewSeeder::class,
            TypeSeeder::class,
            QuestionSeeder::class,
            TemplateTypeSeeder::class,
            EvaluationSeeder::class,
        ]);
    }
}
