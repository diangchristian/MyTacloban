<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Announcement;


class AnnouncementCategory extends Model
{
    /** @use HasFactory<\Database\Factories\AnnouncementCategoryFactory> */
    use HasFactory;
   

    public function announcements(){
        return $this->hasMany(Announcement::class);
    }
}
