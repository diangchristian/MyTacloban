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

    public function index(){
        return response()->json($this->report->getAll());

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
