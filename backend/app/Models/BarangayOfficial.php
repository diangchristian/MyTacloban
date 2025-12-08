<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangayOfficial extends Model
{
    use HasFactory;

    protected $table = 'barangay_officials';

    protected $fillable = [
        'name',
        'official_name',
        'position',
        'email',
        'contact_number'
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
