<?php

namespace App\Repositories;

use App\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class ReportRepository implements ReportRepositoryInterface {

    public function getAll()
    {
        return DB::select("SELECT * FROM reports");
    }

    public function getByUser($id, $search = null, $status = null)
    {
        $sql = "
            SELECT 
                r.id,
                r.user_id,
                r.category_id,
                r.title,
                r.coordinates,
                r.description,
                r.status,
                r.created_at,
                r.updated_at,
                rc.category_name,
                GROUP_CONCAT(DISTINCT rm.file_path) AS images,
                CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT(
                    'id', rt.id,
                    'status', rt.status,
                    'description', rt.description,
                    'created_at', rt.created_at,
                    'updated_at', rt.updated_at
                )), ']') AS timelines
            FROM reports r
            JOIN report_categories rc ON r.category_id = rc.id
            LEFT JOIN report_images rm ON rm.report_id = r.id
            LEFT JOIN report_timelines rt ON rt.report_id = r.id
            WHERE r.user_id = ?
        ";
    
        $params = [$id];
    
        // Optional: FULLTEXT search
        if ($search) {
            $sql .= " AND MATCH(r.title, r.description) AGAINST(? IN NATURAL LANGUAGE MODE)";
            $params[] = $search;
        }
    
        // Optional: status filter
        if ($status && $status !== 'all') {
            $sql .= " AND r.status = ?";
            $params[] = $status;
        }
    
        $sql .= "
            GROUP BY 
                r.id, r.user_id, r.category_id, r.title, 
                r.description, r.status, r.created_at, r.updated_at, 
                r.coordinates, rc.category_name
        ";
    
        $reports = DB::select($sql, $params);
    
        // Convert images and timelines to arrays
        $reports = collect($reports)->map(function ($report) {
            $report->images = $report->images ? explode(',', $report->images) : [];
            $report->timelines = $report->timelines ? json_decode($report->timelines, true) : [];
            return $report;
        });
    
        return $reports;
    }
    
    public function getReports($search = null, $status = null)
    {   
        $sql = "
            SELECT 
                r.id,
                r.user_id,
                r.category_id,
                r.title,
                r.coordinates,
                r.description,
                r.status,
                r.created_at,
                r.updated_at,
                rc.category_name,
                GROUP_CONCAT(DISTINCT rm.file_path) AS images
            FROM reports r
            JOIN report_categories rc ON r.category_id = rc.id
            LEFT JOIN report_images rm ON rm.report_id = r.id
            WHERE 1 = 1             
        ";

        $params = [];                // <--- required

        // Optional search
        if ($search) {
            $sql .= " AND MATCH(r.title, r.description) AGAINST(? IN NATURAL LANGUAGE MODE)";
            $params[] = $search;
        }

        // Optional status filter
        if ($status && $status !== 'all') {
            $sql .= " AND r.status = ?";
            $params[] = $status;
        }

        $sql .= "
            GROUP BY 
                r.id, r.user_id, r.category_id, r.title, 
                r.description, r.status, r.created_at, r.updated_at, 
                r.coordinates, rc.category_name
        ";

        $reports = DB::select($sql, $params);

        $reports = collect($reports)->map(function ($report) {
            $report->images = $report->images ? explode(',', $report->images) : [];
            return $report;
        });

        return $reports;
    }

    public function getByReportDetails($id){
        $report = DB::select("
            SELECT 
                r.id,
                r.user_id,
                r.category_id,
                r.title,
                r.coordinates,
                r.description,
                r.status,
                r.created_at,
                r.updated_at,
                rc.category_name,
                GROUP_CONCAT(DISTINCT rm.file_path) AS images,
                CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT(
                    'id', rt.id,
                    'status', rt.status,
                    'description', rt.description,
                    'created_at', rt.created_at,
                    'updated_at', rt.updated_at
                ) ORDER BY rt.created_at DESC   ), ']') AS timelines   -- << ORDER HERE
            FROM reports r
            JOIN report_categories rc ON r.category_id = rc.id
            LEFT JOIN report_images rm ON rm.report_id = r.id
            LEFT JOIN report_timelines rt ON rt.report_id = r.id
            WHERE r.id = ?
            GROUP BY 
                r.id, r.user_id, r.category_id, r.title, 
                r.description, r.status, r.created_at, r.updated_at, 
                r.coordinates, rc.category_name
        ", [$id]);
    
        if(!empty($report)){
            $report[0]->timelines = json_decode($report[0]->timelines, true);
            $report[0]->images = $report[0]->images ? explode(',', $report[0]->images) : [];
        }
    
        return $report;
    }
    
    public function updateReportStatus($fields, $id)
    {
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
                (user_id, category_id, title, description, coordinates,  created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    $data['user_id'],
                    $data['category'],
                    $data['title'] ?? '',
                    $data['description'] ?? '',
                    $data['coordinates'] ?? '',
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
