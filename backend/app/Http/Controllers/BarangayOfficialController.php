<?php

namespace App\Http\Controllers;

use App\Models\BarangayOfficial;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BarangayOfficialController extends Controller
{
    /**
     * Display a listing of barangay officials.
     */
    public function index(Request $request)
    {
        $query = BarangayOfficial::with(['barangay']);

        // Filter by barangay if provided
        if ($request->has('barangay_id')) {
            $query->where('barangay_id', $request->barangay_id);
        }

        // Filter by position if provided
        if ($request->has('position')) {
            $query->where('position', $request->position);
        }

        // Search by name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('official_name', 'LIKE', "%{$search}%");
        }

        $officials = $query->orderBy('position')->orderBy('official_name')->paginate(15);

        return response()->json($officials);
    }

    /**
     * Store a newly created barangay official.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barangay_id' => 'required|exists:barangays,id',
            'official_name' => 'required|string|max:255',
            'position' => ['required', Rule::in(['Captain', 'Councilor', 'SK Chairman', 'Secretary', 'Treasurer'])],
            'email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if position already exists for this barangay (except Councilor which can have multiple)
        if ($request->position !== 'Councilor') {
            $existingOfficial = BarangayOfficial::where('barangay_id', $request->barangay_id)
                ->where('position', $request->position)
                ->first();

            if ($existingOfficial) {
                return response()->json([
                    'message' => "A {$request->position} already exists for this barangay",
                    'errors' => [
                        'position' => ["This position is already occupied in the selected barangay"]
                    ]
                ], 422);
            }
        }

        $official = BarangayOfficial::create($request->all());

        return response()->json([
            'message' => 'Barangay official created successfully',
            'data' => $official->load('barangay')
        ], 201);
    }

    /**
     * Display the specified barangay official.
     */
    public function show($id)
    {
        $official = BarangayOfficial::with(['barangay'])->find($id);

        if (!$official) {
            return response()->json([
                'message' => 'Barangay official not found'
            ], 404);
        }

        return response()->json($official);
    }

    /**
     * Update the specified barangay official.
     */
    public function update(Request $request, $id)
    {
        $official = BarangayOfficial::find($id);

        if (!$official) {
            return response()->json([
                'message' => 'Barangay official not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'barangay_id' => 'sometimes|required|exists:barangays,id',
            'official_name' => 'sometimes|required|string|max:255',
            'position' => ['sometimes', 'required', Rule::in(['Captain', 'Councilor', 'SK Chairman', 'Secretary', 'Treasurer'])],
            'email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if position already exists for this barangay (except Councilor which can have multiple)
        if ($request->has('position') && $request->position !== 'Councilor') {
            $barangayId = $request->barangay_id ?? $official->barangay_id;
            
            $existingOfficial = BarangayOfficial::where('barangay_id', $barangayId)
                ->where('position', $request->position)
                ->where('id', '!=', $id)
                ->first();

            if ($existingOfficial) {
                return response()->json([
                    'message' => "A {$request->position} already exists for this barangay",
                    'errors' => [
                        'position' => ["This position is already occupied in the selected barangay"]
                    ]
                ], 422);
            }
        }

        $official->update($request->all());

        return response()->json([
            'message' => 'Barangay official updated successfully',
            'data' => $official->load('barangay')
        ]);
    }

    /**
     * Remove the specified barangay official.
     */
    public function destroy($id)
    {
        $official = BarangayOfficial::find($id);

        if (!$official) {
            return response()->json([
                'message' => 'Barangay official not found'
            ], 404);
        }

        $official->delete();

        return response()->json([
            'message' => 'Barangay official deleted successfully'
        ]);
    }

    /**
     * Get officials by barangay
     */
    public function getByBarangay($barangayId)
    {
        $barangay = Barangay::find($barangayId);

        if (!$barangay) {
            return response()->json([
                'message' => 'Barangay not found'
            ], 404);
        }

        $officials = BarangayOfficial::where('barangay_id', $barangayId)
            ->orderByRaw("FIELD(position, 'Captain', 'SK Chairman', 'Secretary', 'Treasurer', 'Councilor')")
            ->orderBy('official_name')
            ->get();

        return response()->json([
            'barangay' => $barangay,
            'officials' => $officials
        ]);
    }

    /**
     * Get statistics about barangay officials
     */
    public function statistics()
    {
        $stats = [
            'total_officials' => BarangayOfficial::count(),
            'by_position' => BarangayOfficial::selectRaw('position, COUNT(*) as count')
                ->groupBy('position')
                ->get(),
            'by_barangay' => BarangayOfficial::with('barangay:id,name')
                ->selectRaw('barangay_id, COUNT(*) as count')
                ->groupBy('barangay_id')
                ->get(),
            'barangays_with_complete_officials' => $this->getCompleteBarangays(),
            'barangays_missing_officials' => $this->getIncompleteBarangays()
        ];

        return response()->json($stats);
    }

    /**
     * Get barangays with all required positions filled
     */
    private function getCompleteBarangays()
    {
        $requiredPositions = ['Captain', 'SK Chairman', 'Secretary', 'Treasurer'];
        
        $barangays = Barangay::withCount(['officials as has_all_positions' => function($query) use ($requiredPositions) {
            $query->whereIn('position', $requiredPositions)
                  ->selectRaw('COUNT(DISTINCT position)');
        }])->having('has_all_positions', '=', count($requiredPositions))->count();

        return $barangays;
    }

    /**
     * Get barangays with missing required positions
     */
    private function getIncompleteBarangays()
    {
        $requiredPositions = ['Captain', 'SK Chairman', 'Secretary', 'Treasurer'];
        
        $barangays = Barangay::withCount(['officials as has_all_positions' => function($query) use ($requiredPositions) {
            $query->whereIn('position', $requiredPositions)
                  ->selectRaw('COUNT(DISTINCT position)');
        }])->having('has_all_positions', '<', count($requiredPositions))->count();

        return $barangays;
    }

    /**
     * Get barangays with missing positions details
     */
    public function missingPositions()
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

        return response()->json($result);
    }

    /**
     * Get officials by position
     */
    public function getByPosition($position)
    {
        $validPositions = ['Captain', 'Councilor', 'SK Chairman', 'Secretary', 'Treasurer'];
        
        if (!in_array($position, $validPositions)) {
            return response()->json([
                'message' => 'Invalid position'
            ], 400);
        }

        $officials = BarangayOfficial::with('barangay')
            ->where('position', $position)
            ->orderBy('official_name')
            ->get();

        return response()->json($officials);
    }
}