<?php

namespace Modules\Interview\app\Http\Services;

use Modules\Interview\app\Models\Type;
use Modules\Interview\app\Models\Interview;
use Modules\Interview\app\Models\Evaluation;

class DashboardService
{
    public function getMetrics(int $year): array
    {
        $query = Interview::whereYear('date', '=', $year);

        $total     = $query->count();
        $completed = (clone $query)->whereIn('status', ['completed', 'absent'])->count();
        $avgScore  = Evaluation::whereYear('created_at', $year)->avg('score') ?? 0;
        $absent    = (clone $query)->where('status', 'absent')->count();

        return [
            'totalInterviews'     => $total,
            'completedInterviews' => $completed,
            'averageScore'        => round($avgScore, 1),
            'AbsentRate'          => $total ? $absent / $total * 100 : 0,
        ];
    }


    public function getAvgScoreByQuestionType(int $year): array
    {
        $types = Type::whereHas('templates', function ($query) use ($year) {
            $query->whereYear('templates.created_at', $year);
        })
            ->with([
                'questions.evaluations' => function ($query) use ($year) {
                    $query->whereYear('created_at', $year);
                },
            ])->get();

        return $types
            ->mapWithKeys(function (Type $type) {
                // Collect all scores from the filtered evaluations
                $scores = $type->questions
                    ->flatMap(
                        fn($question) =>
                        $question
                            ->evaluations
                            ->pluck('score')
                            ->filter()
                    );

                $avg = $scores->isEmpty() ? null : $scores->avg();

                return [
                    $type->id => [
                        'title'          => $type->title,
                        'average_score' => ($avg !== null) ? round($avg, 1) : null,
                    ],
                ];
            })
            ->toArray();
    }

    public function getYears(): array
    {
        return Interview::selectRaw("strftime('%Y', date) as year")
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn($y) => (string) $y)
            ->toArray();
    }

    public function getLastInterviews(int $year): array
    {
        $lastInterviews = Interview::with('evaluations', 'template', 'candidate')
            ->whereIn('status', ['completed', 'absent'])
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();


        return $lastInterviews->toArray();
    }
}
