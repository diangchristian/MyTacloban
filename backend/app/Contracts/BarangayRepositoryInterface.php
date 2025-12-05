<?php

namespace App\Contracts;

interface BarangayRepositoryInterface
{

    public function getAll();

    /**
     * Find barangay by ID.
     */
    public function findById(int $id);

    /**
     * Create a barangay.
     */
    public function store(array $data);

    /**
     * Update a barangay.
     */
    public function update(array $data, int $id);

    /**
     * Delete a barangay.
     */
    public function destroy(int $id);

    /**
     * For dropdown API (lean response: id + name only).
     */

}
