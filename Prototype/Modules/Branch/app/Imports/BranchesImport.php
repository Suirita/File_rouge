<?php

namespace Modules\Branch\app\Imports;

use Modules\Branch\app\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BranchesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $branch = Branch::create([
            'name'    => $row['branch_name'],
            'user_id' => $row['user_id'],
        ]);

        // If there are questions provided, split the comma-separated string and create each question.
        if (!empty($row['questions'])) {
            $questions = explode(',', $row['questions']);
            foreach ($questions as $questionText) {
                // Trim extra whitespace and check if the question isn't empty
                $cleanQuestion = trim($questionText);
                if (!empty($cleanQuestion)) {
                    $branch->questions()->create([
                        'question' => $cleanQuestion,
                    ]);
                }
            }
        }

        return $branch;
    }
}
