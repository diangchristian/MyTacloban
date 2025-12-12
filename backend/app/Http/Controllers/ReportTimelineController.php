<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ReportTimelineRepositoryInterface;



class ReportTimelineController extends Controller
{
    protected $timelines;

    public function __construct(ReportTimelineRepositoryInterface $timelines)
    {
        $this->timelines = $timelines;
    }



    public function getTimelines($id){
        return response()->json($this->timelines->getAll($id));
    }


    public function store(Request $request){

        $fields = $request->validate([
            'id' => 'required',
            'status' => 'required|string',
            'description' => 'required|string',
        ]);


        if($this->timelines->addTimeline($fields)){
            return response()->json([
                'message' => 'New timeline added successfully!'
            ]);

        }

        return response()->json([
            'message' => 'Error adding timeline!'
        ]);

    }
}
