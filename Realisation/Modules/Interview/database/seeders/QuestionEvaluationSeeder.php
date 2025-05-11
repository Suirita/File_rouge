<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionEvaluationSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $responseRows = DB::table('question_responses')
            ->join('participations', 'question_responses.participation_id', '=', 'participations.id')
            ->where('participations.status', 'completed')
            ->select([
                'question_responses.id as question_response_id',
                'participations.trainer_id',
            ])
            ->get();

        $rows = [];

        foreach ($responseRows as $r) {
            $rows[] = [
                'question_response_id' => $r->question_response_id,
                'trainer_id'           => $r->trainer_id,
                'score'                => $this->makeDummyScore(),
                'remarks'              => $this->makeDummyRemark(),
                'created_at'           => $now,
                'updated_at'           => $now,
            ];
        }

        DB::table('question_evaluations')->insert($rows);
    }

    /**
     * Dummy score generator (1–5).
     */
    protected function makeDummyScore(): int
    {
        return rand(1, 5);
    }

    /**
     * Dummy remarks generator.
     */
    protected function makeDummyRemark(): string
    {
        $remarks = [
            'Very clear and concise.',
            'Good depth of knowledge.',
            'Could elaborate more on edge cases.',
            'Strong conceptual understanding.',
            'Answers were too brief.',
        ];
        return $remarks[array_rand($remarks)];
    }
}
