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
    public function searchByName($name)
    {
        if (trim($name) === '') {
            return DB::select("SELECT * FROM barangays ORDER BY name ASC");
        }
        return DB::select(
            "SELECT * FROM barangays WHERE name LIKE ? ORDER BY name ASC",
            ["%{$name}%"]
        );

   
    }


    /**
     * Create a barangay.
     */
    public function store(array $fields){
        $data = array_merge($fields, [
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return DB::insert(
            "INSERT INTO barangays (name, population, households, area, contact_person, contact_no, coordinates, email, phone_number, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['name'],
                $data['population'] ?? null,
                $data['households'] ?? null,
                $data['area'] ?? null,
                $data['contact_person'],
                $data['contact_no'] ?? null,
                $data['coordinates'],
                $data['email'],
                $data['phone_number'],
                $data['created_at'],
                $data['updated_at']
            ]
        );
    }

    /**
     * Update a barangay.
     */
    public function update(array $fields, int $id)
    {
        $data = array_merge($fields, [
            'updated_at' => now(),
        ]);
    
        $sql = "UPDATE barangays SET name = ?, population = ?, households = ?, area = ?, contact_person = ?, contact_no = ?, coordinates = ?, email = ?, phone_number = ?, updated_at = ? WHERE id = ?";

        $values = [
            $data['name'],
            $data['population'] ?? null,
            $data['households'] ?? null,
            $data['area'] ?? null,
            $data['contact_person'],
            $data['contact_no'] ?? null,
            $data['coordinates'],
            $data['email'],
            $data['phone_number'],
            $data['updated_at'],
            $id
        ];
    
        return DB::update($sql, $values);
    }
    

    /**
     * Delete a barangay.
     */
    public function destroy(int $id){
        return DB::delete("DELETE FROM barangays WHERE id = ?", [$id]);
    }
}


