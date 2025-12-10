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

    public function store($name)
    {
        DB::table('report_categories')->insert([
            'category_name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function update($name, $id)
    {
        $updated = DB::update("UPDATE report_categories SET category_name = ?, updated_at = ? WHERE id = ?", 
        [$name, now(), $id]);
        
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