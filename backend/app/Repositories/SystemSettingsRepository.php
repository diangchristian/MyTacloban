<?php

namespace App\Repositories;

use App\Contracts\SystemSettingsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SystemSettingsRepository implements SystemSettingsRepositoryInterface {

    public function index()
    {
        return DB::select('SELECT * FROM system_settings');
    }

    public function update($data)
    {
        return DB::update('UPDATE system_settings 
                        SET system_name = ?, description = ?, logo_path = ?, updated_at = NOW()
                        WHERE id = 1', [
                            $data['system_name'],
                            $data['description'],
                            $data['logo'], 
                        ]);
    }

}