<?php

namespace Modules\Interview\app\Http\Services;

use Modules\Interview\app\Models\Branch;
use Modules\Interview\app\Models\Participation;
use Modules\Interview\app\Models\Evaluation;

class DashboardService
{
    public function getMetrics(int $year): array
    {
        $query = Participation::whereYear('created_at', $year);

        $total     = $query->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        $absent    = (clone $query)->where('status', 'absent')->count();
        $avgScore  = Evaluation::whereYear('created_at', $year)->avg('score') ?? 0;

        return [
            'totalInterviews'     => $total,
            'completedInterviews' => $completed,
            'averageScore'        => round($avgScore, 1),
            'AbsentRate'          => $total ? $absent / $total * 100 : 0,
        ];
    }


    public function getAvgScoreByBranch(int $year): array
    {
        // Eager-load only evaluations from the given year
        $branches = Branch::with([
            'questions.answers.evaluation' => function ($query) use ($year) {
                $query->whereYear('created_at', $year);
            },
        ])->get();

        return $branches
            ->mapWithKeys(function (Branch $branch) {
                // Collect all scores from the filtered evaluations
                $scores = $branch->questions
                    ->flatMap(
                        fn($question) =>
                        $question
                            ->answers
                            ->pluck('evaluation.score')
                            ->filter() // drop nulls
                    );

                $avg = $scores->isEmpty() ? null : $scores->avg();

                return [
                    $branch->id => [
                        'title'         => $branch->title,
                        'average_score' => $avg !== null ? round($avg, 1) : null,
                    ],
                ];
            })
            ->toArray();
    }





    public function getLastInterviews(int $year): array
    {
        $lastInterviews = Participation::with('interview', 'candidate')
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();


        return $lastInterviews->toArray();
    }
}
