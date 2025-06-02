<?php

namespace Modules\Interview\app\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Interview\app\Models\Template;
use Modules\Interview\app\Exports\TemplatesExport;
use Modules\Interview\app\Imports\TemplatesImport;
use Modules\Interview\app\Http\Requests\ImportRequest;
use Modules\Interview\app\Http\Requests\TemplateRequest;
use Modules\Interview\app\Http\Services\TemplateService;

class TemplateController extends Controller
{
    protected TemplateService $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    // Export users as Excel
    public function export()
    {
        return Excel::download(new TemplatesExport, 'Templates.xlsx');
    }

    // Import users from Excel
    public function import(ImportRequest $request)
    {
        Excel::import(new TemplatesImport, $request->file('file'));

        return back();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->templateService->AllTemplates();

        return Inertia::render('Template', [
            'templates' => $data['templates'],
            'types'   => $data['types'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TemplateRequest $request)
    {
        $this->templateService->addTemplates($request);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TemplateRequest $request, Template $template)
    {
        $this->templateService->updateTemplate($request, $template->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        $this->templateService->deleteTemplate($template->id);

        return back();
    }
}
