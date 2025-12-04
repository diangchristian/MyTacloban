<?php

namespace App\Contracts;

interface AnnouncementCategoryRepositoryInterface
{
    public function getAll();

    public function store(string $name);

    public function update(int $id);


    public function destroy(int $id);
}
