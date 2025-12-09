<?php

namespace App\Http\Controllers;

use App\Contracts\BarangayOfficialRepositoryInterface;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BarangayOfficialController extends Controller
{
    protected $barangayOfficialRepository;

    public function __construct(BarangayOfficialRepositoryInterface $barangayOfficialRepository)
    {
        $this->barangayOfficialRepository = $barangayOfficialRepository;
    }

    /**
     * Display a listing of barangay officials.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['barangay_id', 'position', 'search']);
        $officials = $this->barangayOfficialRepository->getAll($filters);

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
            'position' => ['required', Rule::in(['captain', 'councilor', 'skchairman', 'secretary', 'treasurer'])],
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
            if ($this->barangayOfficialRepository->positionExists($request->barangay_id, $request->position)) {
                return response()->json([
                    'message' => "A {$request->position} already exists for this barangay",
                    'errors' => [
                        'position' => ["This position is already occupied in the selected barangay"]
                    ]
                ], 422);
            }
        }

        $official = $this->barangayOfficialRepository->store($request->all());

        return response()->json([
            'message' => 'Barangay official created successfully',
            'data' => $official
        ], 201);
    }

    /**
     * Display the specified barangay official.
     */
    public function show($id)
    {
        $official = $this->barangayOfficialRepository->find($id);

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
        $official = $this->barangayOfficialRepository->find($id);

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
            
            if ($this->barangayOfficialRepository->positionExists($barangayId, $request->position, $id)) {
                return response()->json([
                    'message' => "A {$request->position} already exists for this barangay",
                    'errors' => [
                        'position' => ["This position is already occupied in the selected barangay"]
                    ]
                ], 422);
            }
        }

        $updated = $this->barangayOfficialRepository->update($request->all(), $id);

        return response()->json([
            'message' => 'Barangay official updated successfully',
            'data' => $updated
        ]);
    }

    /**
     * Remove the specified barangay official.
     */
    public function destroy($id)
    {
        $result = $this->barangayOfficialRepository->destroy($id);

        if (!$result) {
            return response()->json([
                'message' => 'Barangay official not found'
            ], 404);
        }

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

        $officials = $this->barangayOfficialRepository->getByBarangay($barangayId);

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
        $stats = $this->barangayOfficialRepository->getStatistics();

        return response()->json($stats);
    }

    /**
     * Get barangays with missing positions details
     */
    public function missingPositions()
    {
        $result = $this->barangayOfficialRepository->getMissingPositions();

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

        $officials = $this->barangayOfficialRepository->getByPosition($position);

        return response()->json($officials);
    }
}