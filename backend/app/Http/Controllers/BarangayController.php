<?php

namespace App\Http\Controllers;

use App\Contracts\BarangayRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BarangayController extends Controller
{

    protected $barangay;

    public function __construct(BarangayRepositoryInterface $barangay) {
        $this->barangay = $barangay;
    }

    
    
    public function index(){
        return response()->json($this->barangay->getAll());
    }

  

    // public function show(Request $request){
    //     $search = $request->query('search');
    //     // Log a simple informational message
    //     Log::info('search value:', ['search' => $search]);


    //     if($this->barangay->searchByName($search)){
    //         return response()->json($this->barangay->searchByName($search));
    //     }

    //     return response()->json([
    //         'message' => 'Errrorrr'
    //     ]);
    
    //     // return null;
        
    
    // }
    public function store(Request $request){

        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'population' => 'nullable|string|max:255',
            'households' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:20',
            'coordinates' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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
            'population' => 'nullable|string|max:255',
            'households' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_no' => 'nullable|string|max:20',
            'coordinates' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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

    public function searchFilter(Request $request){

        $search = $request->query('search');
                // Log a simple informational message
        Log::info('search value:', ['search' => $search]);


        if($this->barangay->searchByName($search)){
            return response()->json($this->barangay->searchByName($search));
        }

        return response()->json([
            'message' => 'Errrorrr'
        ]);

        
    }

}
