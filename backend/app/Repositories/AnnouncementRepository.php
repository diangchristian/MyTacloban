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
                        ORDER BY a.created_at DESC
                        ");
    }

    public function getAll($barangayId = null)
    {

        $query = "
            SELECT
                a.id AS announcement_id,
                a.barangay_id,
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
        ";
        $bindings = [];

        if($barangayId !== null){
            $query .= "WHERE barangay_id = ?";
            $bindings[] = $barangayId;
        }else{
            $query .= "WHERE barangay_id IS NULL";
        }


        return DB::select($query,         $bindings);

            
        
    }

    public function getById($id)
    {
        return DB::select("
                        SELECT a.*, ac.* FROM announcements a
                        JOIN announcement_categories ac ON a.category_id = ac.id
                        WHERE a.id = ?
                        ", [$id]);
    }

    public function stats($barangayId = null)
{
    $where = '';
    $bindings = [];

    if (!is_null($barangayId)) {
        $where = 'WHERE barangay_id = ?';
        $bindings[] = $barangayId;
    }else{
        $where = 'WHERE barangay_id IS NULL';
    }
    
    $stats = DB::select("
        SELECT
            COUNT(*) AS total_count,
            SUM(status = 'published') AS published_count,
            SUM(status = 'draft') AS draft_count,
            SUM(status = 'archived') AS archived_count,

            -- This week
            SUM(YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1)) AS total_this_week,
            SUM(status = 'published' AND YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1)) AS published_this_week,
            SUM(status = 'draft' AND YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1)) AS draft_this_week,
            SUM(status = 'archived' AND YEARWEEK(created_at, 1) = YEARWEEK(NOW(), 1)) AS archived_this_week,

            -- Last week
            SUM(YEARWEEK(created_at, 1) = YEARWEEK(DATE_SUB(NOW(), INTERVAL 1 WEEK), 1)) AS total_last_week,
            SUM(status = 'published' AND YEARWEEK(created_at, 1) = YEARWEEK(DATE_SUB(NOW(), INTERVAL 1 WEEK), 1)) AS published_last_week,
            SUM(status = 'draft' AND YEARWEEK(created_at, 1) = YEARWEEK(DATE_SUB(NOW(), INTERVAL 1 WEEK), 1)) AS draft_last_week,
            SUM(status = 'archived' AND YEARWEEK(created_at, 1) = YEARWEEK(DATE_SUB(NOW(), INTERVAL 1 WEEK), 1)) AS archived_last_week
        FROM announcements
        $where
    ", $bindings);

    $s = $stats[0];

    return response()->json([
        'total' => [
            'count' => (int) $s->total_count,
            'thisWeek' => (int) $s->total_this_week,
            'lastWeek' => (int) $s->total_last_week,
        ],
        'published' => [
            'count' => (int) $s->published_count,
            'thisWeek' => (int) $s->published_this_week,
            'lastWeek' => (int) $s->published_last_week,
        ],
        'draft' => [
            'count' => (int) $s->draft_count,
            'thisWeek' => (int) $s->draft_this_week,
            'lastWeek' => (int) $s->draft_last_week,
        ],
        'archived' => [
            'count' => (int) $s->archived_count,
            'thisWeek' => (int) $s->archived_this_week,
            'lastWeek' => (int) $s->archived_last_week,
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
    public function search($search = null, $category = null, $start = null, $end = null, $status = null, $barangayId = null)
    {
        $sql = "SELECT
                    a.id AS announcement_id,
                    a.title,
                    a.body,
                    a.image,
                    a.category_id,
                    a.barangay_id,
                    a.status,
                    a.isHighlight,
                    a.created_at,
                    a.updated_at,
                    ac.id AS category_id,
                    ac.category_name
                FROM announcements a
                JOIN announcement_categories ac
                    ON a.category_id = ac.id
                ";

        $bindings = [];

        if($barangayId){
            $sql .= "WHERE barangay_id = ?";
            $bindings[] = $barangayId;
        }
        
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

        if (!empty($status)) {
            $sql .= " AND a.status = ?";
            $bindings[] = $status;
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
        // Map the request fields to DB columns
        $data['title'] = $data['announcement_title'] ?? $data['title'] ?? '';
        $data['isHighlight'] = $data['isHighlight'] ?? null;
        $data['image'] = $data['image'] ?? null;
        $data['barangay_id'] = $data['barangay_id'] ?? null; // crucial for admin
    
        $data = array_merge($data, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
    
        return DB::insert(
            "INSERT INTO announcements 
                (category_id, title, isHighlight, body, image, status, user_id, barangay_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['category_id'],
                $data['title'],
                $data['isHighlight'],
                $data['body'],
                $data['image'],
                $data['status'],
                $data['user_id'],
                $data['barangay_id'], 
                $data['created_at'],
                $data['updated_at']
            ]
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