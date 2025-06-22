<?php

namespace Modules\Interview\app\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Interview\app\Models\Interview;
use Modules\Interview\app\Exports\InterviewsExport;
use Modules\Interview\app\Imports\InterviewsImport;
use Modules\Interview\app\Http\Requests\ImportRequest;
use Modules\Interview\app\Http\Requests\InterviewRequest;
use Modules\Interview\app\Http\Services\InterviewService;

class InterviewController extends Controller
{
    protected InterviewService $interviewService;

    public function __construct(InterviewService $interviewService)
    {
        $this->interviewService = $interviewService;
    }

    // Export users as Excel
    public function export()
    {
        return Excel::download(new InterviewsExport, 'Interviews.xlsx');
    }

    // Import users from Excel
    public function import(ImportRequest $request)
    {
        Excel::import(new InterviewsImport, $request->file('file'));

        return back();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->interviewService->AllInterviews();

        return Inertia::render('Interview', [
            'interviews' => $data['interviews'],
            'templates' => $data['templates'],
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(InterviewRequest $request, Interview $interview)
    {
        $this->interviewService->updateInterview($request, $interview->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interview $interview)
    {
        $this->interviewService->deleteInterview($interview->id);

        return back();
    }
}
