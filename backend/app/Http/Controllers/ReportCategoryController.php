<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ReportCategoryRepositoryInterface;

class ReportCategoryController extends Controller
{

    protected $category;


    public function __construct(ReportCategoryRepositoryInterface $category){
        $this->category = $category;
    }


    public function index(){
        return response()->json($this->category->getAll());
    }

    public function show(){

    }

    public function store(Request $request){
        $fields = $request->validate([
            'name' => 'required',
            'slug'       => 'required|string|max:255|',
            'icon_name'  => 'required|string|max:255',
            'color' => 'required|string|max:255',
        ]);


        if($fields){
            $this->category->store($fields);

            return response()->json([
                'message' => 'Category created successfully',
            ]);

        }

        return response()->json([
            'message' => 'Error adding category',
        ]);



    }

    public function update(Request $request, $id){

        $fields = $request->validate([
            'name' => 'required',
            'slug'       => 'required|string|max:255|',
            'icon_name'  => 'required|string|max:255',
            'color' => 'required|string|max:255',
        ]);

        $updated = $this->category->update($id, $fields);

        if ($updated) {
            return response()->json([
                'message' => 'Category updated successfully'
            ]);
        } else {
            return response()->json([
                'message' => 'Category not found or nothing changed'
            ], 404);
        }

    }

    public function destroy($id){
        
        $deleted = $this->category->destroy($id);

        if($deleted){
            return response()->json([
                'message' => 'Category deleted successfully'
            ]);
        }else{
            return response()->json([
                'message' => 'Category not found or nothing changed'
            ], 404);
        }
    }
}
