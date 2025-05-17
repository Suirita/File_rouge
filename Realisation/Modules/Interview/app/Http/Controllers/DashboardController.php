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
        $year = $request->input('year', now()->year);

        $metrics          = $this->dashboard->getMetrics($year);
        $avgScoreByBranch = $this->dashboard->getAvgScoreByBranch($year);
        $lastInterviews   = $this->dashboard->getLastInterviews($year);

        return Inertia::render('Dashboard', [
            'metrics'            => $metrics,
            'avgScoreByBranch'   => $avgScoreByBranch,
            'lastInterviews'     => $lastInterviews,
            'selectedYear'       => (string) $year,
        ]);
    }
}
