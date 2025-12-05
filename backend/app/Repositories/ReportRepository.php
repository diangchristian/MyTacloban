<?php

namespace App\Repositories;
use App\Contracts\ReportRepositoryInterface;
use Illuminate\Support\Facades\DB;



class ReportRepository implements ReportRepositoryInterface {

    public function getAll()
    {
        return DB::select("SELECT * FROM reports");
    }
}