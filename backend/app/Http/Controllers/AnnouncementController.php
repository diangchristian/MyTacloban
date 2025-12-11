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
        return response()->json($this->announcement->getAllPublished());
    }

    public function getAllAnnouncements(){
        return response()->json($this->announcement->getAll());
    }


    public function getAnnouncementById($id){
        return response()->json($this->announcement->getById($id));
    }

    public function getByCategory($category){
        return response()->json($this->announcement->getByCategory($category));
    }

    public function getAllStats(){
        return $this->announcement->stats();
    }

    public function search(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $start = $request->query('start');
        $end = $request->query('end');

        $results = $this->announcement->search($search, $category, $start, $end);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function getByCreatedAt($filter){
        $today = now()->toDateString();
        
        if ($filter === 'today') {
            $start = $today;
            $end = $today;
    
        } else if ($filter === 'this_week') {
            $start = now()->startOfWeek()->toDateString();
            $end = now()->endOfWeek()->toDateString();
    
        } else if ($filter === 'this_month') {
            $start = now()->startOfMonth()->toDateString();
            $end = now()->endOfMonth()->toDateString();
    
        } else if ($filter === 'this_year') {
            $start = now()->startOfYear()->toDateString();
            $end = now()->endOfYear()->toDateString();
    
        } else {
            return response()->json(['error' => 'Invalid date filter'], 400);
        }



        return response()->json(
            $this->announcement->getByDateRange($start, $end)
        );
    }

    public function show(){

    }

    public function store(Request $request){

        $fields = $request->validate([
            'category_id' => 'required|integer|exists:announcement_categories,id',
            'announcement_title' => 'required|string|max:255',
            'isHighlight' => 'nullable',
            'body' => 'required|string',
            'image' => 'nullable|string|max:255', // or url validation if storing URLs
            'status' => 'required|string|in:draft,published,archived',
            'user_id' => 'required'
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
            'isHighlight' => 'nullable',
            'body' => 'required|string',
            'image' => 'nullable|string|max:255', // or url validation if storing URLs
            'status' => 'required|string|in:draft,published,archived',
            'user_id' => 'required'
        ]);


        if($this->announcement->update($fields, $id)){
           return response()->json([
            'message' => 'Announcement updated successfully!'
           ]);
        }
    }

    public function destroy(Request $request, $id){
        if($this->announcement->destroy($id, $request->query('user_id'))){
            return response()->json([
                'message' => 'Announcement deleted successfully!'
            ]);
        }
    }
}
