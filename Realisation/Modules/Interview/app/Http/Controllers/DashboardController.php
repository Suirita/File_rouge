<?php

namespace Modules\Interview\app\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Interview\app\Http\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard) {}

    public function index(Request $request)
    {
        $selectedYear = $request->input('selectedYear', 2024);

        $metrics          = $this->dashboard->getMetrics($selectedYear);
        $avgScoreByQuestionType = $this->dashboard->getAvgScoreByQuestionType($selectedYear);
        $years            = $this->dashboard->getYears();
        $lastInterviews   = $this->dashboard->getLastInterviews($selectedYear);

        return Inertia::render('Dashboard', [
            'metrics'            => $metrics,
            'avgScoreByQuestionType'   => $avgScoreByQuestionType,
            'lastInterviews'     => $lastInterviews,
            'years'             => $years,
            'selectedYear'       => (string) $selectedYear,
        ]);
    }
}
