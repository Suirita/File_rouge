<?php

namespace Modules\Branch\app\Exports;

use Modules\Branch\app\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class BranchesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $branches = Branch::with('questions')->get();

        $data = new Collection();

        foreach ($branches as $branch) {
            $data->push([
                'Branch ID'   => $branch->id,
                'Branch Name' => $branch->name,
                'User ID'     => $branch->user_id,
                'Questions'   => $branch->questions->pluck('question')->implode(', '),
                'Created At'  => $branch->created_at,
                'Updated At'  => $branch->updated_at,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Branch ID', 'Branch Name', 'User ID', 'Questions', 'Created At', 'Updated At'];
    }
}
