<?php

namespace Modules\Interview\app\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\Interview\app\Models\Template;
use Modules\Interview\app\Models\Candidate;
use Modules\Interview\app\Models\Interview;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class InterviewsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'id'             => 'required|integer',
            'candidate_name' => 'required|string|max:50',
            'template_title' => 'required|string|max:255',
            'status'         => 'required|string',
            'date'           => 'required|date',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'id.required'             => 'Every row must include a non-empty ID.',
            'candidate_name.required' => 'Every row must include a non-empty candidate name.',
            'template_title.required' => 'Every row must include a non-empty template title.',
            'status.required'         => 'Every row must include a non-empty status.',
            'date.required'           => 'Every row must include a non-empty date.',
        ];
    }

    /**
     * Process each row from Excel.
     *
     * @param  array  $row  // Keys: 'id', 'candidate_name', 'template_title', 'status', 'date'
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Normalize inputs
            $id            = (int) trim($row['id']);
            $candidateName = trim($row['candidate_name'] ?? '');
            $templateTitle = trim($row['template_title'] ?? '');
            $status        = trim($row['status']);
            $date          = trim($row['date']);

            // Look up related models
            $candidate = Candidate::where('name', $candidateName)->firstOrFail();
            $template  = Template::where('title', $templateTitle)->firstOrFail();

            // Parse incoming date as Carbon
            $incomingDate = Carbon::parse($date)->toDateString();

            // Check if interview exists by ID
            $interview = Interview::find($id);

            if ($interview) {
                $updates = [];

                if ($interview->candidate_id !== $candidate->id) {
                    $updates['candidate_id'] = $candidate->id;
                }
                if ($interview->template_id !== $template->id) {
                    $updates['template_id'] = $template->id;
                }
                if ($interview->status !== $status) {
                    $updates['status'] = $status;
                }

                // Compare current date value
                $currentDate = Carbon::parse($interview->date)->toDateString();
                if ($currentDate !== $incomingDate) {
                    $updates['date'] = $incomingDate;
                }

                // Apply updates if any
                if (!empty($updates)) {
                    $interview->update($updates);
                    return $interview;
                }

                // No changes; skip
                return null;
            }

            // Does not exist; create new interview
            return Interview::create([
                'id'           => $id,
                'candidate_id' => $candidate->id,
                'template_id'  => $template->id,
                'status'       => $status,
                'date'         => $incomingDate,
            ]);
        });
    }
}
