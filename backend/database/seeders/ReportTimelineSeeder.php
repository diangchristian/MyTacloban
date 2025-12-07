<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\ReportTimeline;

class ReportTimelineSeeder extends Seeder
{
    public function run(): void
    {
        Report::all()->each(function ($report) {
            ReportTimeline::firstOrCreate([
                'report_id' => $report->id,
            ], [
                'status' => 'Report request is received by the system',
                'description' => 'Initial request has been recorded and is awaiting review.',
            ]);
        });
    }
}
