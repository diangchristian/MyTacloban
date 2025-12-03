<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Report;


class ReportCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ReportCategoryFactory> */
    use HasFactory;

    public function reports(){
        return $this->hasMany(Report::class);
    }
}
