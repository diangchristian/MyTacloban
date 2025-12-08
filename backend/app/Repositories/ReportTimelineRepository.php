<?php

namespace App\Repositories;
use App\Contracts\ReportTimelineRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReportTimelineRepository implements ReportTimelineRepositoryInterface {

    public function getAll($id)
    {
        return DB::select(" SELECT * FROM report_timelines 
                                    WHERE report_id = ?
                                    ORDER BY created_at ASC
                        ", [$id]);
    }



    public function addTimeline(array $fields): bool
    {
        $data = array_merge($fields, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return DB::insert('INSERT INTO report_timelines(report_id, status, description, created_at, updated_at) 
                                VALUES (?, ?, ?, ?, ?)', array_values($data));
    }
}