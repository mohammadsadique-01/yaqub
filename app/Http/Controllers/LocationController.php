<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Village;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function list(): JsonResponse
    {
        return response()->json(Location::all());
    }

    public function destroy(Location $location): JsonResponse
    {
        // prevent delete if used by debitors
        if ($location->debitors()->exists()) {
            return response()->json([
                'message' => 'Location is already used and cannot be deleted',
            ], 422);
        }

        $location->delete();

        return response()->json([
            'message' => 'Location deleted successfully',
        ]);
    }

    public function villages(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
        ]);

        $villages = Village::where('location_id', $request->location_id)
            ->orderBy('village_name')
            ->get();

        return response()->json($villages);
    }
}
