<?php

namespace App\Repositories;
use App\Contracts\ReportCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReportCategoryRepository implements ReportCategoryRepositoryInterface {

    public function getAll()
    {
        return DB::select("SELECT * FROM report_categories");
    }

    public function show()
    {
        throw new \Exception('Not implemented');
    }

    public function store($data)
    {
        DB::insert("
            INSERT INTO report_categories (category_name, slug, icon_name, color, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $data['name'],
            $data['slug'] ?? null,
            $data['icon_name'] ?? null,
            $data['color'] ?? null,
            now(),
            now(),
        ]);
    }
    

    public function update($id, $data)
    {
        $updated = DB::update("
            UPDATE report_categories 
            SET category_name = ?, 
                slug = ?, 
                icon_name = ?, 
                color = ?, 
                updated_at = ? 
            WHERE id = ?
        ", [
            $data['name'],
            $data['slug'] ?? null,
            $data['icon_name'] ?? null,
            $data['color'] ?? null,
            now(),
            $id
        ]);
    
        return $updated;
    }
    

    public function destroy($id)
    {
        return DB::delete(
            "DELETE FROM report_categories WHERE id = ?",
            [$id]
        );
    }
}