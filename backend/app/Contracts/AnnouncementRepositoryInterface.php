<?php

namespace App\Contracts;

interface AnnouncementRepositoryInterface
{
    public function getAll();

    public function getByCategory(string $category);

    public function getByDateRange(string $start, string $end);

    public function store(array $fields);
    public function update(array $fields, int $id);
    public function destroy(int $id);
}
