<?php

namespace App\Contracts;

interface AnnouncementCategoryRepositoryInterface
{
    public function getAll();

    public function show();
    public function store(string $name);
    public function update(int $id, string $name);
    public function destroy(int $id);
}
