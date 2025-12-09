<?php

namespace App\Repositories;

use App\Contracts\BarangayOfficialRepositoryInterface;
use App\Models\BarangayOfficial;
use App\Models\Barangay;
use Illuminate\Support\Facades\DB;

class BarangayOfficialRepository implements BarangayOfficialRepositoryInterface
{
    /**
     * Get all barangay officials with filters.
     */
    public function getAll(array $filters = [])
    {
        $query = BarangayOfficial::with(['barangay']);

        // Filter by barangay if provided
        if (isset($filters['barangay_id'])) {
            $query->where('barangay_id', $filters['barangay_id']);
        }

        // Filter by position if provided
        if (isset($filters['position'])) {
            $query->where('position', $filters['position']);
        }

        // Search by name
        if (isset($filters['search'])) {
            $query->where('official_name', 'LIKE', "%{$filters['search']}%");
        }

        return $query->orderBy('position')->orderBy('official_name')->paginate(15);
    }

    /**
     * Find a barangay official by ID.
     */
    public function find(int $id)
    {
        return BarangayOfficial::with(['barangay'])->find($id);
    }

    /**
     * Create a new barangay official.
     */
    public function store(array $data)
    {
        $official = BarangayOfficial::create($data);
        return $official->load('barangay');
    }

    /**
     * Update a barangay official.
     */
    public function update(array $data, int $id)
    {
        $official = BarangayOfficial::find($id);
        
        if (!$official) {
            return null;
        }

        $official->update($data);
        return $official->load('barangay');
    }

    /**
     * Delete a barangay official.
     */
    public function destroy(int $id)
    {
        $official = BarangayOfficial::find($id);
        
        if (!$official) {
            return false;
        }

        return $official->delete();
    }

    /**
     * Get officials by barangay ID.
     */
    public function getByBarangay(int $barangayId)
    {
        return BarangayOfficial::where('barangay_id', $barangayId)
            ->orderByRaw("FIELD(position, 'Captain', 'SK Chairman', 'Secretary', 'Treasurer', 'Councilor')")
            ->orderBy('official_name')
            ->get();
    }

    /**
     * Get officials by position.
     */
    public function getByPosition(string $position)
    {
        return BarangayOfficial::with('barangay')
            ->where('position', $position)
            ->orderBy('official_name')
            ->get();
    }

    /**
     * Check if position exists for barangay (excluding specific ID).
     */
    public function positionExists(int $barangayId, string $position, int $excludeId = null)
    {
        $query = BarangayOfficial::where('barangay_id', $barangayId)
            ->where('position', $position);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get statistics about barangay officials.
     */
    public function getStatistics()
    {
        return [
            'total_officials' => BarangayOfficial::count(),
            'by_position' => BarangayOfficial::selectRaw('position, COUNT(*) as count')
                ->groupBy('position')
                ->get(),
            'by_barangay' => BarangayOfficial::with('barangay:id,name')
                ->selectRaw('barangay_id, COUNT(*) as count')
                ->groupBy('barangay_id')
                ->get(),
            'barangays_with_complete_officials' => $this->countCompleteBarangays(),
            'barangays_missing_officials' => $this->countIncompleteBarangays()
        ];
    }

    /**
     * Get barangays with missing required positions.
     */
    public function getMissingPositions()
    {
        $requiredPositions = ['Captain', 'SK Chairman', 'Secretary', 'Treasurer'];
        $barangays = Barangay::with('officials')->get();
        
        $result = [];
        
        foreach ($barangays as $barangay) {
            $existingPositions = $barangay->officials->pluck('position')->toArray();
            $missingPositions = array_diff($requiredPositions, $existingPositions);
            
            if (!empty($missingPositions)) {
                $result[] = [
                    'barangay_id' => $barangay->id,
                    'barangay_name' => $barangay->name,
                    'missing_positions' => array_values($missingPositions),
                    'existing_officials_count' => count($existingPositions)
                ];
            }
        }

        return $result;
    }

    /**
     * Count barangays with all required positions filled.
     */
    public function countCompleteBarangays()
    {
        $requiredPositions = ['Captain', 'SK Chairman', 'Secretary', 'Treasurer'];
        
        return Barangay::withCount(['officials as has_all_positions' => function($query) use ($requiredPositions) {
            $query->whereIn('position', $requiredPositions)
                  ->selectRaw('COUNT(DISTINCT position)');
        }])->having('has_all_positions', '=', count($requiredPositions))->count();
    }

    /**
     * Count barangays missing required positions.
     */
    public function countIncompleteBarangays()
    {
        $requiredPositions = ['Captain', 'SK Chairman', 'Secretary', 'Treasurer'];
        
        return Barangay::withCount(['officials as has_all_positions' => function($query) use ($requiredPositions) {
            $query->whereIn('position', $requiredPositions)
                  ->selectRaw('COUNT(DISTINCT position)');
        }])->having('has_all_positions', '<', count($requiredPositions))->count();
    }
}
