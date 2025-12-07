<?php

namespace App\Repositories;
use App\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EventRepository implements EventRepositoryInterface {

    public function getAll()
    {
        return DB::select(" SELECT e.*, ec.* FROM events e
                        JOIN event_categories ec ON e.category_id = ec.id
                        ");
    }
}