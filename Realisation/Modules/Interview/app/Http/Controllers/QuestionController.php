<?php

namespace Modules\Interview\app\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Interview\app\Models\Type;
use Modules\Interview\app\Exports\QuestionsExport;
use Modules\Interview\app\Imports\QuestionsImport;
use Modules\Interview\app\Http\Requests\ImportRequest;
use Modules\Interview\app\Http\Requests\QuestionRequest;
use Modules\Interview\app\Http\Services\QuestionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuestionController extends Controller
{
    use AuthorizesRequests;

    protected QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    // Export users as Excel
    public function export()
    {
        return Excel::download(new QuestionsExport, 'Questions.xlsx');
    }

    // Import users from Excel
    public function import(ImportRequest $request)
    {
        Excel::import(new QuestionsImport, $request->file('file'));

        return back();
    }

    /**
     * Display a listing of the resource
     */
    public function index()
    {
        $types = $this->questionService->AllQuestions();

        return Inertia::render('Question', [
            'types' => $types,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionRequest $request)
    {
        $this->questionService->addQuestions($request);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionRequest $request, Type $type)
    {
        $this->questionService->updateQuestion($request, $type->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $this->questionService->deleteQuestion($type->id);

        return back();
    }
}
