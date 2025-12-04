<?php

namespace App\Repositories;
use App\Contracts\EventCategoryRepositoryInterface;


class EventCategoryRepository implements EventCategoryRepositoryInterface {

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