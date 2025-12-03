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
    public function create(array $data){
        return null;
    }

    /**
     * Update a barangay.
     */
    public function update(int $id, array $data){
        return null;
    }

    /**
     * Delete a barangay.
     */
    public function delete(int $id){
        return null;
    }
}


