<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ReportCategory;
use App\Models\User;


class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;

    public function category(){
        return $this->belongsTo(ReportCategory::class);
    }



    public function user(){
        return $this->hasOne(User::class);
    }
}

