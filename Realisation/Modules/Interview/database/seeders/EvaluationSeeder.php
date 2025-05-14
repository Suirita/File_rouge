<?php

namespace Modules\Interview\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Interview\app\Models\Evaluation;

class EvaluationSeeder extends Seeder
{
    public function run()
    {
        $answers = DB::table('answers')
            ->join('participations', 'answers.participation_id', '=', 'participations.id')
            ->join('trainers_participations', 'participations.id', '=', 'trainers_participations.participation_id')
            ->select([
                'answers.id as answer_id',
                'trainers_participations.trainer_id',
            ])
            ->get();

        $evaluations = [];

        foreach ($answers as $r) {
            $evaluations[] = [
                'answer_id'  => $r->answer_id,
                'trainer_id' => $r->trainer_id,
                'score'      => $this->makeDummyScore(),
                'remarks'    => $this->makeDummyRemark(),
            ];
        }

        foreach ($evaluations as $evaluation) {
            Evaluation::create($evaluation);
        }
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
