<?php

namespace App\Repositories;
use App\Contracts\AnnouncementCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AnnouncementCategoryRepository implements AnnouncementCategoryRepositoryInterface {

    public function getAll()
    {
        return DB::select("SELECT * FROM announcement_categories");
    }

    public function show()
    {
        throw new \Exception('Not implemented');
    }

    public function store($name)
    {
        DB::table('announcement_categories')->insert([
            'category_name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function update($name, $id)
    {
        $updated = DB::update("UPDATE announcement_categories SET category_name = ?, updated_at = ? WHERE id = ?", 
        [$name, now(), $id]);
        
        return $updated;
    }

    public function destroy($id)
    {
        return DB::delete(
            "DELETE FROM announcement_categories WHERE id = ?",
            [$id]
        );
    }
}