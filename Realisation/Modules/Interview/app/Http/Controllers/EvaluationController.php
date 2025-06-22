<?php

namespace Modules\Interview\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Interview\app\Http\Requests\EvaluationRequest;
use Modules\Interview\app\Http\Services\EvaluationService;


class EvaluationController extends Controller
{
    protected EvaluationService $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    public function store(EvaluationRequest $request)
    {
        $this->evaluationService->addEvaluation($request);

        return back();
    }
}
