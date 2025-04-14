<?php

namespace Modules\Branch\app\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Branch\app\Models\Branch;
use Modules\Branch\app\Exports\BranchesExport;
use Modules\Branch\app\Imports\BranchesImport;
use Modules\Branch\app\Http\Requests\BranchRequest;
use Modules\Branch\app\Repositories\BranchRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BranchController extends Controller
{
    use AuthorizesRequests;

    protected BranchRepository $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    // Export users as Excel
    public function export()
    {
        return Excel::download(new BranchesExport, 'Branches.xlsx');
    }

    // Import users from Excel
    public function import(BranchRequest $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new BranchesImport, $request->file('file'));

        return back()->withStatus('Import successful!');
    }

    /**
     * Display a listing of the resource
     */
    public function index()
    {
        $user = Auth::user();

        $branches = $this->branchRepository->AllBranches()->map(function ($branch) use ($user) {
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'questions' => $branch->questions,
                'canModify' => $user && method_exists($user, 'can') ? $user->can('modify', $branch) : false,
            ];
        });

        return Inertia::render('Branch', [
            'branches' => $branches,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchRequest $request)
    {
        $this->branchRepository->addBranches($request);

        return redirect()->route('branch')->with('success', 'Branche ajoutée avec succès');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchRequest $request, Branch $branch)
    {
        $this->authorize('modify', $branch);

        $this->branchRepository->updateBranche($request, $branch->id);

        return redirect()->route('branch')->with('success', 'Branche modifier avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $this->authorize('modify', $branch);

        $this->branchRepository->deleteBranche($branch->id);

        return redirect()->route('branch')->with('success', 'Branche supprimée avec succès');
    }
}
