<?php

namespace Modules\Interview\app\Http\Services;

use Modules\Interview\app\Models\Branch;
use Modules\Interview\app\Models\Participation;
use Modules\Interview\app\Models\QuestionEvaluation;

class DashboardService
{
    public function getMetrics(): array
    {
        $total = Participation::count();
        $completed = Participation::where('status', 'completed')->count();
        $avgScore = QuestionEvaluation::avg('score') ?? 0;
        $AbsentRate = Participation::where('status', 'absent')->count() / $total * 100;

        return [
            'totalInterviews'      => $total,
            'completedInterviews'  => $completed,
            'averageScore'         => round($avgScore, 1),
            'AbsentRate'       => $AbsentRate,
        ];
    }

    public function getAvgScoreByBranch(): array
    {
        $branches = Branch::with([
            'questions.responses.evaluation',
        ])->get();

        return $branches
            ->mapWithKeys(function (Branch $branch) {
                $scores = $branch->questions
                    ->flatMap(
                        fn($q) =>
                        $q->responses
                            ->pluck('evaluation.score')
                            ->filter()
                    );

                $avg = $scores->isEmpty()
                    ? null
                    : $scores->avg();

                return [
                    $branch->id => [
                        'title'         => $branch->title,
                        'average_score' => $avg,
                    ],
                ];
            })
            ->toArray();
    }




    public function getLastInterviews(): array
    {
        $lastInterviews = Participation::with('interview', 'candidate', 'trainer')
            ->where('status', 'completed')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();


        return $lastInterviews->toArray();
    }
}
