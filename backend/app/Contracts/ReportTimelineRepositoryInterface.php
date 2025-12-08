<?php

namespace App\Contracts;

interface ReportTimelineRepositoryInterface
{
    public function getAll($id);


    public function addTimeline(array $data);

}
