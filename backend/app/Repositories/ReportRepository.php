<?php

namespace App\Repositories;

use App\Contracts\ReportRepositoryInterface;
use App\Models\Report;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Exception;

class ReportRepository implements ReportRepositoryInterface {


    public function getAll( $barangayId = null)
    {

        $bindings = [];
        $query = "SELECT * FROM reports ";  

        if(!is_null($barangayId)){
            $query .= "WHERE barangay_id = ? AND handled_by = ? ";
            $bindings[] = $barangayId;
            $bindings[] = 'BARANGAY';
        }else{
            $query .= "WHERE handled_by = ?  ";
            $bindings[] = 'LGU';
        }

        $query .= "ORDER BY created_at";


        return DB::select($query, $bindings);


    }

    public function getByUser($id, $search = null, $status = null)
    {
        $sql = "
            SELECT 
                v.* 
            FROM view_reports_base v
            JOIN reports r ON r.id = v.id
            WHERE v.user_id = ?
        ";

        $params = [$id];

        if ($search) {
            $sql .= "  AND MATCH(r.title, r.description) AGAINST(? IN NATURAL LANGUAGE MODE)";
            $params[] = $search;
        }

        if ($status && $status !== 'all') {
            $sql .= " AND v.status = ?";
            $params[] = $status;
        }

        $reports = DB::select($sql, $params);

        // Aggregate timelines manually (as in original repository)
        $reports = collect($reports)->map(function ($report) {
            $report->images = $report->images ? explode(',', $report->images) : [];

            // Keep timelines aggregation in PHP
            $timelines = DB::select("
                SELECT id, status, description, created_at, updated_at
                FROM report_timelines
                WHERE report_id = ?
                ORDER BY created_at DESC
            ", [$report->id]);

            $report->timelines = $timelines;
            return $report;
        });

        return $reports;
    }
        
    
    public function getReports($search = null, $status = null, $start = null, $end = null, $barangayId = null)
    {
        $sql = "
            SELECT 
                v.* 
            FROM view_reports_base v
            JOIN reports r ON r.id = v.id
            
        ";

        $params = [];


        if(!is_null($barangayId)){
            $sql .= "WHERE barangay_id = ? AND handled_by = ?";
            $params[] = $barangayId;
            $params[] = 'BARANGAY';
        }else{
            $sql .= "WHERE handled_by = ?";
            $params[] = 'LGU';
        }


        if ($search) {
            $sql .= " AND MATCH(r.title, r.description) AGAINST(? IN NATURAL LANGUAGE MODE)";
            $params[] = $search;
        }

        if ($status && $status !== 'all') {
            $sql .= " AND v.status = ?";
            $params[] = $status;
        }

         // Date FILTER
         if (!empty($start) && !empty($end)) {
            $sql .= " AND DATE(v.created_at) BETWEEN ? AND ?";
            $params[] = $start;
            $params[] = $end;
        }

        $reports = DB::select($sql, $params);

        // Aggregate timelines for each report
        $reports = collect($reports)->map(function ($report) {
            $report->images = $report->images ? explode(',', $report->images) : [];

            $timelines = DB::select("
                SELECT id, status, description, created_at, updated_at
                FROM report_timelines
                WHERE report_id = ?
                ORDER BY created_at DESC
            ", [$report->id]);

            $report->timelines = $timelines;
            return $report;
        });

        return $reports;
    }

    
    
    public function getByReportDetails($id)
    {
        $sql = "
            SELECT 
                v.*,
                u.full_name,
                u.email,
                u.phone_number
            FROM view_reports_base v
            JOIN users u ON u.id = v.user_id
            WHERE v.id = ?
            LIMIT 1
        ";
    
        $report = DB::select($sql, [$id]);
    
        if (!empty($report)) {
            $report[0]->images = $report[0]->images ? explode(',', $report[0]->images) : [];
    
            $timelines = DB::select("
                SELECT id, status, description, created_at, updated_at
                FROM report_timelines
                WHERE report_id = ?
                ORDER BY created_at DESC
            ", [$report[0]->id]);
    
            $report[0]->timelines = $timelines;
        }
    
        return $report;
    }
    public function getWeeklyCounts($barangayId = null)
    {
        $statuses = ['pending', 'assigned', 'in_progress', 'resolved'];
    
        $baseQuery = Report::query()
        ->when(
            $barangayId !== null,
            fn ($q) => $q
                ->where('barangay_id', $barangayId)
                ->where('handled_by', 'BARANGAY'),
            fn ($q) => $q
                ->where('handled_by', 'LGU')
        );
    
    
        $reportsThisWeek = (clone $baseQuery)
            ->whereDate('created_at', '>=', Carbon::now()->startOfWeek()->toDateString())
            ->whereDate('created_at', '<=', Carbon::now()->endOfWeek()->toDateString())
            ->count();
    
        $reportsLastWeek = (clone $baseQuery)
            ->whereDate('created_at', '>=', Carbon::now()->subWeek()->startOfWeek()->toDateString())
            ->whereDate('created_at', '<=', Carbon::now()->subWeek()->endOfWeek()->toDateString())
            ->count();
    
        $thisWeekStatus = (clone $baseQuery)
            ->whereDate('created_at', '>=', Carbon::now()->startOfWeek()->toDateString())
            ->whereDate('created_at', '<=', Carbon::now()->endOfWeek()->toDateString())
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count','status')
            ->toArray();
    
        $lastWeekStatus = (clone $baseQuery)
            ->whereDate('created_at', '>=', Carbon::now()->subWeek()->startOfWeek()->toDateString())
            ->whereDate('created_at', '<=', Carbon::now()->subWeek()->endOfWeek()->toDateString())
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count','status')
            ->toArray();
    
        return [
            'all_reports_this_week' => $reportsThisWeek,
            'all_reports_last_week' => $reportsLastWeek,
            'this_week' => collect($statuses)->mapWithKeys(fn($status) => [
                $status => $thisWeekStatus[$status] ?? 0
            ]),
            'last_week' => collect($statuses)->mapWithKeys(fn($status) => [
                $status => $lastWeekStatus[$status] ?? 0
            ]),
        ];
    }


    public function escalateToLGU($fields)
    {
        $id = $fields['report_id'];
        DB::statement("SET @current_user_id = ?", [$fields['user_id']]);

        $handled_by = 'LGU';
        
        return DB::update(
            "UPDATE reports SET handled_by = ? , updated_at = ? WHERE id = ?",
            [$handled_by, now(), $id]
        );
    }
    
    
    
    public function updateReportStatus($fields, $id)
    {
        DB::statement("SET @current_user_id = ?", [$fields['user_id']]);

        $status = $fields['status'];
        
        return DB::update(
            "UPDATE reports SET status = ?, updated_at = ? WHERE id = ?",
            [$status, now(), $id]
        );
        
    }
    public function store(array $data)
    {
        DB::beginTransaction();
        
        try {

            DB::insert(
                "INSERT INTO reports 
                (user_id, category_id, title, description, coordinates, barangay_id, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $data['user_id'],
                    $data['category'],
                    $data['title'] ?? '',
                    $data['description'] ?? '',
                    $data['coordinates'] ?? '',
                    $data['barangay_id'],
                    now(),
                    now(),
                ]
            );

            $reportId = DB::getPdo()->lastInsertId();


            if (!empty($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $imagePath) {
                    DB::insert(
                        "INSERT INTO report_images 
                        (report_id, file_path, created_at, updated_at)
                        VALUES (?, ?, ?, ?)",
                        [
                            $reportId,
                            $imagePath,
                            now(),
                            now(),
                        ]
                    );
                }
            }

            DB::insert(
                "INSERT INTO report_timelines
                (report_id, status, description, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?)",
                [
                    $reportId,
                    'Report request is received by the system',
                    'Initial request has been recorded and is awaiting review.',
                    now(),
                    now(),
                ]
            );
    
            DB::commit();

            return $reportId;
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e; 
        }
    }
}
