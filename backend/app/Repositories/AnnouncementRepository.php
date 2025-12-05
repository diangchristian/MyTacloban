<?php

namespace App\Repositories;
use App\Contracts\AnnouncementRepositoryInterface;
use Illuminate\Support\Facades\DB;


class AnnouncementRepository implements AnnouncementRepositoryInterface {

    public function getAll()
    {
        return DB::select("SELECT * FROM announcements");
    }



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


    public function update(array $data, int $id){
        $data = array_merge($data, [
            'updated_at' => now(),
            'id' => $id
        ]);

        
        return DB::update("UPDATE announcements 
            SET category_id = ?, title = ?, body = ?, image = ?, status = ?, updated_at = ? 
            WHERE id = ?", array_values($data));
    }

    public function destroy(int $id){
        return DB::delete("DELETE FROM announcements WHERE id = ?", [$id]);
    }


}