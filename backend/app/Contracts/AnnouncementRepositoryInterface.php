<?php

namespace App\Contracts;

interface AnnouncementRepositoryInterface
{
    public function getAll();


    public function store(array $fields);
    public function update(array $fields, int $id);
    public function destroy(int $id);
}
