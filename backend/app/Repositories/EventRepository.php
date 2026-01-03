<?php

namespace App\Repositories;
use App\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EventRepository implements EventRepositoryInterface {

    public function getAll($barangayId = null)
{
    return DB::table('events as e')
        ->leftJoin('event_categories as ec', 'e.category_id', '=', 'ec.id')
        ->select('e.*', 'ec.category_name')
        ->when(
            $barangayId !== null, 
            
            fn($q) => $q->where('e.barangay_id', $barangayId),
            fn($q) => $q->whereNull('e.barangay_id')

            
            
            )
        ->orderByDesc('e.event_date')
        ->orderByDesc('e.created_at')
        ->get();
}


    public function getById(int $id)
    {
        return DB::table('events as e')
            ->leftJoin('event_categories as ec', 'e.category_id', '=', 'ec.id')
            ->select('e.*', 'ec.category_name')
            ->where('e.id', $id)
            ->first();
    }

    public function store(array $data)
    {
        $payload = array_merge($data, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('events')->insertGetId($payload);
    }

    public function update(array $data, int $id)
    {
        $payload = array_merge($data, [
            'updated_at' => now(),
        ]);

        return DB::table('events')
            ->where('id', $id)
            ->update($payload);
    }

    public function destroy(int $id, ?int $userId = null)
    {
        if ($userId) {
            DB::statement("SET @current_user_id = ?", [$userId]);
        }

        return DB::table('events')
            ->where('id', $id)
            ->delete();
    }
}