<?php

namespace Modules\Branch\app\Repositories;

use Modules\Branch\app\Models\Branch;
use Modules\Branch\app\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchRepository
{
    function AllBranches()
    {
        return Branch::with('questions')->get();
    }

    function addBranches($request)
    {
        $branch = Branch::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        foreach ($request->questions as $questionText) {
            $branch->questions()->create([
                'question' => $questionText,
            ]);
        }

        return $branch;
    }

    function findBranche($id)
    {
        return Branch::find($id);
    }

    function updateBranche(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update(['name' => $request->name, 'user_id' => Auth::user()->id]);

        Question::where('branch_id', $id)->delete();

        if (!empty($request->questions)) {
            foreach ($request->questions as $questionText) {
                Question::create([
                    'question' => $questionText,
                    'branch_id' => $id,
                ]);
            }
        }
    }

    function deleteBranche($id)
    {
        Branch::destroy($id);
    }
}
