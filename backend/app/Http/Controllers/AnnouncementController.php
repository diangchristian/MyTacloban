<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\AnnouncementRepositoryInterface;


class AnnouncementController extends Controller
{

    protected $announcement;

    public function __construct(AnnouncementRepositoryInterface $announcement){
        $this->announcement = $announcement;
    }

    public function index(){
        return response()->json($this->announcement->getAll());
    }

    public function show(){

    }

    public function store(Request $request){

        $fields = $request->validate([
            'category_id' => 'required|integer|exists:announcement_categories,id',
            'announcement_title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|string|max:255', // or url validation if storing URLs
            'status' => 'required|string|in:draft,published,archived',
        ]);

        // if okay it input
        if($this->announcement->store($fields)){
           return response()->json([
            'message' => 'Announcement created successfully!'
           ]);
        }

    }

    public function update(Request $request, $id){
        $fields = $request->validate([
            'category_id' => 'required|integer|exists:announcement_categories,id',
            'announcement_title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|string|max:255', // or url validation if storing URLs
            'status' => 'required|string|in:draft,published,archived',
        ]);


        if($this->announcement->update($fields, $id)){
           return response()->json([
            'message' => 'Announcement updated successfully!'
           ]);
        }
    }

    public function destroy($id){
        if($this->announcement->destroy($id)){
            return response()->json([
                'message' => 'Announcement deleted successfully!'
            ]);
        }
    }
}
