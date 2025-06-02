<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Interview\App\Models\Candidate;

class InterviewSeeder extends Seeder
{
    public function run()
    {
        // Get all candidate IDs
        $candidateIds = Candidate::pluck('id')->toArray();
        $total = count($candidateIds);
        if ($total === 0) {
            $this->command->info('No candidates found — skipping Interview seeding.');
            return;
        }

        // Split into two halves: 2023 batch and 2024 batch
        $half             = intdiv($total, 2);
        $firstBatchCount  = $half;
        $secondBatchCount = $total - $half;

        // Possible dates for each batch
        $dateOptions2023 = [
            '2023-09-07 10:00:00',
            '2023-09-08 10:00:00',
        ];
        $dateOptions2024 = [
            '2024-09-07 10:00:00',
            '2024-09-08 10:00:00',
        ];

        // Define timestamps (created_at / updated_at)
        $timestamp2023 = '2023-09-01 00:00:00';
        $timestamp2024 = '2024-09-01 00:00:00';

        // --- 2023 batch statuses (only 'absent' or 'completed') ---
        $absent2023    = (int) round($firstBatchCount * 0.10);
        $completed2023 = $firstBatchCount - $absent2023;
        $statuses2023  = array_merge(
            array_fill(0, $absent2023,    'absent'),
            array_fill(0, $completed2023, 'completed')
        );
        shuffle($statuses2023);

        // --- 2024 batch statuses (distribution includes 'pending') ---
        $absent2024    = (int) round($secondBatchCount * 0.05);
        $completed2024 = (int) round($secondBatchCount * 0.40);
        $pending2024   = $secondBatchCount - $absent2024 - $completed2024;
        $statuses2024  = array_merge(
            array_fill(0, $absent2024,    'absent'),
            array_fill(0, $completed2024, 'completed'),
            array_fill(0, $pending2024,   'pending')
        );
        shuffle($statuses2024);

        // Build interviews
        $interviews = [];
        for ($i = 0; $i < $total; $i++) {
            if ($i < $firstBatchCount) {
                // 2023 batch
                $status    = $statuses2023[$i];
                $template  = 1;
                // pick either Sep 07 or Sep 08 for 2023
                $date      = $dateOptions2023[array_rand($dateOptions2023)];
                $timestamp = $timestamp2023;
            } else {
                // 2024 batch
                $idx       = $i - $firstBatchCount;
                $status    = $statuses2024[$idx];
                $template  = 2;
                // pick either Sep 07 or Sep 08 for 2024
                $date      = $dateOptions2024[array_rand($dateOptions2024)];
                $timestamp = $timestamp2024;
            }

            $interviews[] = [
                'candidate_id' => $candidateIds[$i],
                'template_id'  => $template,
                'status'       => $status,
                'date'         => $date,
                'created_at'   => $timestamp,
                'updated_at'   => $timestamp,
            ];
        }

        // Bulk insert
        DB::table('interviews')->insert($interviews);
    }
}
