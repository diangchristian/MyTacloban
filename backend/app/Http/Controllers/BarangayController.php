<?php

namespace App\Http\Controllers;

use App\Contracts\BarangayRepositoryInterface;
use Illuminate\Http\Request;

class BarangayController extends Controller
{

    protected $barangay;

    public function __construct(BarangayRepositoryInterface $barangay) {
        $this->barangay = $barangay;
    }

    
    
    public function index(){
        return response()->json($this->barangay->getAll());
    }

    public function show(){

    }

    public function store(Request $request){

        $fields = $request->validate([
           'name' => 'required|string|max:255',
            'barangay_captain' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);


        if($this->barangay->store($fields)){
            return response()->json([
                'message' => 'Barangay Added successfully!'
            ]);
        }


    }

    public function update(Request $request, $id){
        $fields = $request->validate([
            'name' => 'required|string|max:255',
             'barangay_captain' => 'required|string|max:255',
             'phone_number' => 'required|string|max:20',
         ]);

         if($this->barangay->update($fields, $id)){
            return response()->json([
                'message' => 'Barangay updated successfully!'
            ]);
        }

    }

    public function destroy($id){

        if($this->barangay->destroy($id)){
            return response()->json([
                'message' => 'Barangay deleted successfully!'
            ]);
        }

    }
}
