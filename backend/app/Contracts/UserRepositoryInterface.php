<?php

namespace App\Contracts;

interface UserRepositoryInterface
{

    public function index(int $id);
    public function update(array $data, int $id);


    public function destroy(int $id);
}
