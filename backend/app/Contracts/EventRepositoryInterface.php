<?php

namespace App\Contracts;

interface EventRepositoryInterface
{
    public function getAll();

    public function getById(int $id);

    public function store(array $data);

    public function update(array $data, int $id);

    public function destroy(int $id, ?int $userId = null);
}
