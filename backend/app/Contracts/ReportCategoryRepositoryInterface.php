<?php

namespace App\Contracts;

interface ReportCategoryRepositoryInterface
{
    public function getAll();

    public function show();
    public function store(array $data);
    public function update(int $id, array $data);
    public function destroy(int $id);
}
