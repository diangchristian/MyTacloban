<?php

namespace App\Contracts;

interface SystemSettingsRepositoryInterface
{

    public function index();
    public function update(array $data);


}
