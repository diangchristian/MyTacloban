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
    public function create(array $data);

    /**
     * Update a barangay.
     */
    public function update(int $id, array $data);

    /**
     * Delete a barangay.
     */
    public function delete(int $id);

    /**
     * For dropdown API (lean response: id + name only).
     */

}
