<?php

namespace App\Contracts;

interface BarangayOfficialRepositoryInterface
{
    /**
     * Get all barangay officials with filters.
     */
    public function getAll(array $filters = []);

    /**
     * Find a barangay official by ID.
     */
    public function find(int $id);

    /**
     * Create a new barangay official.
     */
    public function store(array $data);

    /**
     * Update a barangay official.
     */
    public function update(array $data, int $id);

    /**
     * Delete a barangay official.
     */
    public function destroy(int $id);

    /**
     * Get officials by barangay ID.
     */
    public function getByBarangay(int $barangayId);

    /**
     * Get officials by position.
     */
    public function getByPosition(string $position);

    /**
     * Check if position exists for barangay (excluding specific ID).
     */
    public function positionExists(int $barangayId, string $position, int $excludeId = null);

    /**
     * Get statistics about barangay officials.
     */
    public function getStatistics();

    /**
     * Get barangays with missing required positions.
     */
    public function getMissingPositions();

    /**
     * Count barangays with all required positions filled.
     */
    public function countCompleteBarangays();

    /**
     * Count barangays missing required positions.
     */
    public function countIncompleteBarangays();
}
