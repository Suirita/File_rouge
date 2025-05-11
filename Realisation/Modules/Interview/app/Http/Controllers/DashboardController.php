<?php

namespace Modules\Interview\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Modules\Interview\app\Http\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard) {}

    public function index()
    {
        $metrics = $this->dashboard->getMetrics();
        $avgScoreByBranch = $this->dashboard->getAvgScoreByBranch();
        $lastInterviews = $this->dashboard->getLastInterviews();

        return Inertia::render('Dashboard', [
            'metrics'             => $metrics,
            'avgScoreByBranch'  => $avgScoreByBranch,
            'lastInterviews' => $lastInterviews,
        ]);
    }
}
