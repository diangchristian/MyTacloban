<?php

namespace App\Repositories;
use App\Contracts\AnnouncementRepositoryInterface;
use Illuminate\Support\Facades\DB;


class AnnouncementRepository implements AnnouncementRepositoryInterface {

    public function getAllPublished()
    {
        return DB::select("
                        SELECT a.*, ac.* FROM announcements a
                        JOIN announcement_categories ac ON a.category_id = ac.id
                        WHERE a.status = 'published'
                        ");
    }

    public function getAll()
        {
            return DB::select("
            SELECT
                a.id AS announcement_id,
                a.title,
                a.body,
                a.image,
                a.category_id,
                a.status,
                a.isHighlight,
                a.created_at,
                a.updated_at,
        
                ac.id AS category_id,
                ac.category_name
            FROM announcements a
            JOIN announcement_categories ac
            ON a.category_id = ac.id
        ");
        
        }

    public function getById($id)
    {
        return DB::select("
                        SELECT a.*, ac.* FROM announcements a
                        JOIN announcement_categories ac ON a.category_id = ac.id
                        WHERE a.id = ?
                        ", [$id]);
    }

    public function stats() {
        $stats = DB::select("
            SELECT
                COUNT(*) AS total_count,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) AS published_count,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS draft_count,
                
                SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1) THEN 1 ELSE 0 END) AS total_this_week,
                SUM(CASE WHEN status = 'published' AND YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1) THEN 1 ELSE 0 END) AS published_this_week,
                SUM(CASE WHEN status = 'draft' AND YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1) THEN 1 ELSE 0 END) AS draft_this_week,
                
                SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(DATE_SUB(NOW(), INTERVAL 1 WEEK), 1) THEN 1 ELSE 0 END) AS total_last_week,
                SUM(CASE WHEN status = 'published' AND YEARWEEK(created_at, 1) = YEARWEEK(DATE_SUB(NOW(), INTERVAL 1 WEEK), 1) THEN 1 ELSE 0 END) AS published_last_week,
                SUM(CASE WHEN status = 'draft' AND YEARWEEK(created_at, 1) = YEARWEEK(DATE_SUB(NOW(), INTERVAL 1 WEEK), 1) THEN 1 ELSE 0 END) AS draft_last_week
            FROM announcements
        ");
    
        // Raw queries return array of stdClass, get first row
        $s = $stats[0];
    
        return response()->json([
            'total' => [
                'count' => $s->total_count,
                'thisWeek' => $s->total_this_week,
                'lastWeek' => $s->total_last_week,
            ],
            'published' => [
                'count' => $s->published_count,
                'thisWeek' => $s->published_this_week,
                'lastWeek' => $s->published_last_week,
            ],
            'draft' => [
                'count' => $s->draft_count,
                'thisWeek' => $s->draft_this_week,
                'lastWeek' => $s->draft_last_week,
            ],
        ]);
    }
    

    // using idx_ann_category
    public function getByCategory(string $category){
        return DB::select("SELECT * FROM announcements WHERE category_id = ? ", [$category]);
    }
    // using idx_ann_created_at
    public function getByDateRange($start, $end)
    {
        return DB::select(
            "SELECT * FROM announcements WHERE DATE(created_at) BETWEEN ? AND ?",
            [$start, $end]
        );
    }
    public function search($search = null, $category = null, $start = null, $end = null)
    {
        $sql = "SELECT
                    a.id AS announcement_id,
                    a.title,
                    a.body,
                    a.image,
                    a.category_id,
                    a.status,
                    a.isHighlight,
                    a.created_at,
                    a.updated_at,
                    ac.id AS category_id,
                    ac.category_name
                FROM announcements a
                JOIN announcement_categories ac
                    ON a.category_id = ac.id
                WHERE 1=1";

        $bindings = [];

        // Category FILTER
        if (!empty($category)) {
            $sql .= " AND a.category_id = ?";
            $bindings[] = $category;
        }

        // Date FILTER
        if (!empty($start) && !empty($end)) {
            $sql .= " AND DATE(a.created_at) BETWEEN ? AND ?";
            $bindings[] = $start;
            $bindings[] = $end;
        }

        // FULLTEXT SEARCH (fixed)
        if (!empty($search)) {
            $sql .= " AND MATCH(a.title) AGAINST(? IN BOOLEAN MODE)";
            $bindings[] = $search . '*'; // <-- wildcard for partial match
        }

        $sql .= " ORDER BY a.created_at DESC";

        return DB::select($sql, $bindings);
    }


    //storing values
    public function store(array $data)
    {
        // Add timestamps
        $data = array_merge($data, [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return DB::insert(
            "INSERT INTO announcements (category_id, title, isHighlight, body, image, status, user_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            array_values($data)
        );
    }

    // updating values
    public function update(array $data, int $id){
        $data = array_merge($data, [
            'updated_at' => now(),
            'id' => $id
        ]);

        
        return DB::update("UPDATE announcements 
            SET category_id = ?, title = ?, isHighlight = ?, body = ?, image = ?, status = ?, user_id = ?, updated_at = ? 
            WHERE id = ?", array_values($data));
    }

    //deleting a row
    public function destroy(int $id, $userId){
        DB::statement("SET @current_user_id = ?", [$userId]);
        return DB::delete("DELETE FROM announcements WHERE id = ?", [$id]);
    }


}