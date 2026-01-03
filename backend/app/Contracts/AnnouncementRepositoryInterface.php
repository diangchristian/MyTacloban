<?php

namespace App\Contracts;

interface AnnouncementRepositoryInterface
{
    public function getAllPublished();

    public function getAll($barangayId = null);
    public function getByCategory(string $category);

    
    public function stats($barangayId = null);
    public function search($search = null, $category = null, $start = null, $end = null, $status = null, $barangayId = null);
    public function getById($id);
    public function getByDateRange(string $start, string $end);

    public function store(array $fields);
    public function update(array $fields, int $id);
    public function destroy(int $id, $userId);
}
