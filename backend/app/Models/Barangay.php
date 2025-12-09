<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Barangay extends Model
{
    /** @use HasFactory<\Database\Factories\BarangayFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'population',
        'households',
        'area',
        'contact_person',
        'contact_no',
        'coordinates',
        'email',
        'phone_number'
    ];

    public function users(){
        return $this->hasMany(User::class);
    }
}
