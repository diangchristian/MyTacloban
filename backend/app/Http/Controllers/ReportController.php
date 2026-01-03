<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ReportRepositoryInterface;


class ReportController extends Controller
{

    protected $report;


    public function __construct(ReportRepositoryInterface $report){
        $this->report = $report;
    }

    public function index(Request $request){
        $barangayId = $request->query('barangayId') ?? null;
        return response()->json($this->report->getAll(  $barangayId));

    }


    public function getUserReports(Request $request, $id){


        $search = $request->query('search');
        $status = $request->query('status');

        return response()->json($this->report->getByUser($id, $search, $status));
    }

    public function getReports(Request $request){


        $search = $request->query('search');
        $status = $request->query('status');
        $start = $request->query('start');
        $end = $request->query('end');
        $barangayId = $request->query('barangay_id');

        return response()->json($this->report->getReports($search, $status,  $start,   $end,   $barangayId));
    }

    public function escalateReport(Request $request){

        if($this->report->escalateToLGU($request)){
            return response()->json([
                'message' => 'Report successfully escalated to LGU Admin.'
            ]);
        }

        return response()->json([
            'message' => 'Failed to escalate report. Please try again.'
        ]);

 
    }



    public function getReportDetail($id){
        return response()->json($this->report->getByReportDetails($id));
    }

    public function getStatuses($id = null){
        return response()->json($this->report->getWeeklyCounts($id));
    }

    public function store(Request $request){

        $images = collect($request->input('images', []))
            ->map(fn($img) => $img['url'] ?? null)
            ->filter() // remove nulls
            ->toArray();

        // Merge mapped images into request
        $request->merge(['images' => $images]);

        $fields = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'category' => 'required|integer|exists:report_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'coordinates' => 'required|string|max:255',
            'other_issue' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'string|max:255', 
            'barangay_id' => 'required|integer|exists:barangays,id'
        ]);
        

        if($this->report->store($fields)){
            return response()->json([
                'message' => 'Report submitted successfully!'
            ]);
        }


        return response()->json([
            'message' => 'An error has occured!'
        ]);
    }

    public function update(Request $request, $id){
       $fields =  $request->validate([
            'status' => 'required|string|in:pending,in_progress,assigned,resolved,rejected',
            'user_id' => 'required'
        ]);

        if($this->report->updateReportStatus($fields, $id)){
            return response()->json([
                'message' => 'Status updated succesfully!'
            ]);
        }

        return response()->json([
            'message' => 'Couldn\'t update status!'
        ]);
    }

}
