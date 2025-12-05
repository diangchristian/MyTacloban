<?php

namespace App\Http\Controllers;

use App\Contracts\BarangayRepositoryInterface;
use Illuminate\Http\Request;

class BarangayController extends Controller
{

    protected $barangays;

    public function __construct(BarangayRepositoryInterface $barangays) {
        $this->barangays = $barangays;
    }

    
    
    public function index(){
        return response()->json($this->barangays->getAll());
    }

    public function show(){

    }

    public function store(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
