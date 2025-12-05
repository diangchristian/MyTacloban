<?php

namespace App\Repositories;
use App\Contracts\EventCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EventCategoryRepository implements EventCategoryRepositoryInterface {

    public function getAll()
    {
        return DB::select("SELECT * FROM event_categories");
    }

    public function show()
    {
        throw new \Exception('Not implemented');
    }

    public function store($name)
    {
        DB::table('event_categories')->insert([
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function update($name, $id)
    {
        $updated = DB::update("UPDATE event_categories SET name = ?, updated_at = ? WHERE id = ?", 
        [$name, now(), $id]);
        
        return $updated;
    }

    public function destroy($id)
    {
        return DB::delete(
            "DELETE FROM event_categories WHERE id = ?",
            [$id]
        );
    }
}