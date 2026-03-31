<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Village;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with('villages')->get();

        return view('backend.locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'state' => 'required',
            'district' => 'required',
            'state_code' => 'nullable|max:10',
            'villages' => 'required|array',
        ]);

        try {
            $location = Location::firstOrCreate([
                'state' => trim($request->state),
                'district' => trim($request->district),
                'state_code' => $request->state_code,
            ]);
    
            foreach ($request->villages as $village) {
                Village::firstOrCreate([
                    'location_id' => $location->id,
                    'village_name' => trim($village),
                ]);
            }
    
            return back()->with('success', 'Location + Villages Added');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()
                    ->withInput()
                    ->with('error', 'Village already exists in this location');
            }

            throw $e; // other errors
        }
    }

    public function edit($id)
    {
        $locations = Location::with('villages')->findOrFail($id);

        return view('backend.locations.index', compact('locations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'state' => 'required',
            'district' => 'required',
            'state_code' => 'nullable|max:10',
            'villages' => 'required|array',
        ]);

        try {
            $location = Location::firstOrCreate([
                'state' => trim($request->state),
                'district' => trim($request->district),
                'state_code' => $request->state_code,
            ]);

            $oldLocation = Location::findOrFail($id);

            if ($oldLocation->id != $location->id) {
                Village::where('location_id', $oldLocation->id)
                    ->update(['location_id' => $location->id]);

                if (!$oldLocation->villages()->exists()) {
                    $oldLocation->delete();
                }
            }

            $location->villages()->delete();

            foreach ($request->villages as $village) {
                Village::firstOrCreate([
                    'location_id' => $location->id,
                    'village_name' => trim($village),
                ]);
            }

            return back()->with('success', 'Updated successfully');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()
                    ->withInput()
                    ->with('error', 'Village already exists in this location');
            }
            throw $e;
        }
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);

        $location->villages()->delete();

        $location->delete();

        return back()->with('success', 'Deleted');
    }

    public function list(): JsonResponse
    {
        return response()->json(Location::all());
    }

    // public function destroy(Location $location): JsonResponse
    // {
    //     // prevent delete if used by debitors
    //     if ($location->debitors()->exists()) {
    //         return response()->json([
    //             'message' => 'Location is already used and cannot be deleted',
    //         ], 422);
    //     }

    //     $location->delete();

    //     return response()->json([
    //         'message' => 'Location deleted successfully',
    //     ]);
    // }

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
