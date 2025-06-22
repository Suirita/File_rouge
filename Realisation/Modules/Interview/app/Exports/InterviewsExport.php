<?php

namespace Modules\Interview\app\Exports;

use Modules\Interview\app\Models\Interview;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class InterviewsExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    /**
     * Fetch all Interviews, eager‐loading the candidate and template relations,
     * and then map each Interview model into a simple array of values.
     */
    public function collection()
    {
        return Interview::with(['candidate', 'template'])
            ->get()
            ->map(function (Interview $interview) {
                return [
                    // 1) id
                    $interview->id,

                    // 2) candidate->name
                    $interview->candidate ? $interview->candidate->name : null,

                    // 3) template->title
                    $interview->template ? $interview->template->title : null,

                    // 4) status (string or however you store it)
                    $interview->status,

                    // 5) date (cast to a PHP date string, Excel will interpret as date)
                    //    If you store 'date' as a date field, you can simply:
                    $interview->date,
                ];
            });
    }

    /**
     * Define exactly these four headings (in the same order as above).
     */
    public function headings(): array
    {
        return [
            'ID',
            'Candidate Name',
            'Template Title',
            'Status',
            'Date',
        ];
    }

    /**
     * Tell PhpSpreadsheet to treat column D (“Date”) as a date/time cell.
     */
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
