<?php

namespace App\Repositories;

use App\Contracts\BarangayRepositoryInterface;
use App\Models\Barangay;
use Illuminate\Support\Facades\DB;

class BarangayRepository implements BarangayRepositoryInterface{
    public function getAll(){
        return DB::select("SELECT * FROM barangays");
    }

    /**
     * Find barangay by ID.
     */
    public function findById(int $id){
        return null;
    }

    /**
     * Create a barangay.
     */
    public function store(array $fields){
        $data = array_merge($fields, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return DB::insert("INSERT INTO barangays (name, barangay_captain, phone_number, created_at, updated_at) VALUES (?, ?, ?, ?, ?)", array_values($data));
    }

    /**
     * Update a barangay.
     */
    public function update(array $fields, int $id)
    {
        // Merge the updated_at timestamp
        $data = array_merge($fields, [
            'updated_at' => now(),
        ]);
    

        $sql = "UPDATE barangays SET name = ?, barangay_captain = ?, phone_number = ?, updated_at = ? WHERE id = ?";

        $values = array_values($data);
        $values[] = $id;
    
        // Execute the raw update
        return DB::update($sql, $values);
    }
    

    /**
     * Delete a barangay.
     */
    public function destroy(int $id){
        return DB::delete("DELETE FROM barangays WHERE id = ?", [$id]);
    }
}


