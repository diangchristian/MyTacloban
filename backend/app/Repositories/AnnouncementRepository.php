<?php

namespace App\Repositories;
use App\Contracts\AnnouncementRepositoryInterface;
use Illuminate\Support\Facades\DB;


class AnnouncementRepository implements AnnouncementRepositoryInterface {

    public function getAll()
    {
        return DB::select("
                        SELECT a.*, ac.* FROM announcements a
                        JOIN announcement_categories ac ON a.category_id = ac.id
                        ");
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

    //storing values
    public function store(array $data)
    {
        // Add timestamps
        $data = array_merge($data, [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return DB::insert(
            "INSERT INTO announcements (category_id, title, body, image, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?)",
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
            SET category_id = ?, title = ?, body = ?, image = ?, status = ?, updated_at = ? 
            WHERE id = ?", array_values($data));
    }

    //deleting a row
    public function destroy(int $id){
        return DB::delete("DELETE FROM announcements WHERE id = ?", [$id]);
    }


}