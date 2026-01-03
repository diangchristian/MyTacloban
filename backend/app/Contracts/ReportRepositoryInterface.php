<?php

namespace App\Contracts;

interface ReportRepositoryInterface
{
    public function getAll($barangayId = null);


    public function store(array $data);


    public function getByUser($id, $search = null, $status = null);
    public function getReports( $search = null, $status = null, $start = null, $end = null, $barangayId = null);
    public function getByReportDetails($id);


    public function getWeeklyCounts($id = null);
    public function escalateToLGU($fields);


    public function updateReportStatus($data, $id);
}


