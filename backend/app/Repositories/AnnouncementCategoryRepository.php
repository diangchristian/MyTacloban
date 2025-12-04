<?php

namespace App\Repositories;
use App\Contracts\AnnouncementCategoryRepositoryInterface;


class AnnouncementCategoryRepository implements AnnouncementCategoryRepositoryInterface {

    public function getAll()
    {
        return null;
    }

    public function store($name)
    {
        throw new \Exception('Not implemented');
    }

    public function update($id)
    {
        throw new \Exception('Not implemented');
    }

    public function destroy($id)
    {
        throw new \Exception('Not implemented');
    }
}