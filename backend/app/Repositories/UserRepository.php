<?php

namespace App\Repositories;
use App\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface {

    public function index(int $id)
    {
        return DB::select("SELECT * FROM users WHERE id = ?" ,[$id]);
    }

    public function update($data, $id)
    {
        // if (isset($data['profile_image']) && is_array($data['profile_image'])) {
        //     $data['profile_image'] = $data['profile_image'][0] ?? null;
        // }

        $updated = DB::update("
                        UPDATE users 
                        SET email = ?, username = ?, full_name = ?, bio = ?, profile_image = ?, updated_at = NOW()
                        WHERE id = ?
                    ", [
                        $data['email'],
                        $data['username'],
                        $data['fullName'],
                        $data['bio'] ?? null,
                        $data['profile_image'] ?? null,   
                        $id
                    ]);
        
        return $updated;
    }

    public function destroy($id)
    {
        return DB::delete(
            "DELETE FROM users WHERE id = ?",
            [$id]
        );
    }
}