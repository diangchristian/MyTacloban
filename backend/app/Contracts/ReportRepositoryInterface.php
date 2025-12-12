<?php

namespace App\Contracts;

interface ReportRepositoryInterface
{
    public function getAll();


    public function store(array $data);


    public function getByUser($id, $search = null, $status = null);
    public function getReports( $search = null, $status = null, $start = null, $end = null);
    public function getByReportDetails($id);


    public function updateReportStatus($data, $id);
}


