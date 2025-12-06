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
                GROUP_CONCAT(rm.file_path) AS images
            FROM reports r
            JOIN report_categories rc ON r.category_id = rc.id
            LEFT JOIN report_images rm ON rm.report_id = r.id
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

        // Convert images to array
        $reports = collect($reports)->map(function ($report) {
            $report->images = $report->images ? explode(',', $report->images) : [];
            return $report;
        });

        return $reports;
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

            DB::commit();

            return $reportId;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e; 
        }
    }
}
