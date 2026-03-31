<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDebitorRequest;
use App\Models\Debitor;
use App\Models\DebitorSite;
use App\Models\DrillingReport;
use App\Models\Location;
use App\Models\Village;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DebitorController extends Controller
{
    public function index(): View
    {
        return view('backend.debitor.index');
    }

    public function store(StoreDebitorRequest $request)
    {
        $debitor = DB::transaction(function () use ($request) {

            $locationId = null;

            if ($request->filled('location_id')) {
                $locationId = $request->location_id;

            } elseif ($request->filled('state') && $request->filled('district')) {

                $location = Location::firstOrCreate(
                    [
                        'state' => $request->state,
                        'district' => $request->district,
                    ],
                    [
                        'state_code' => $request->state_code,
                    ]
                );

                $locationId = $location->id;
            }

            $villageId = null;

            if ($request->filled('village_id')) {
                $villageId = $request->village_id;

            } elseif ($request->filled('village_name') && $locationId) {

                $village = Village::create([
                    'location_id' => $locationId,
                    'village_name' => $request->village_name,
                ]);

                $villageId = $village->id;
            }

            $debitor = Debitor::create([
                'account_name' => $request->account_name,
                'phone' => $request->phone,
                'gst_number' => $request->gst_number,
                'actual_address' => $request->actual_address,
                'billing_address' => $request->billing_address,
                'location_id' => $locationId,
                'village_id' => $villageId,
                'lease_area' => $request->lease_area,
                'lease_period' => $request->lease_period,
                'remark' => $request->remark,
                'status' => 1,
            ]);

            if (is_array($request->site_name)) {
                collect($request->site_name)
                    ->filter()
                    ->each(function ($site) use ($debitor) {
                        DebitorSite::create([
                            'debitor_id' => $debitor->id,
                            'site_name' => $site,
                        ]);
                    });
            }

            return $debitor;
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => 'Debitor saved successfully',
            ]);
        }

        return redirect()
            ->route('debitors.index')
            ->with('success', 'Debitor saved successfully');
    }

    public function getData(Request $request)
    {
        $query = Debitor::with(['location', 'village'])->select('debitors.*')->orderBy('id', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('district', function ($row) {
                return optional($row->location)->district;
            })
            ->addColumn('state', function ($row) {
                return optional($row->location)->state;
            })
            ->addColumn('village', function ($row) {
                return optional($row->village)->village_name ?? '-';
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('debitor.show', $row->id);
                $editUrl = route('debitor.edit', $row->id);
                $deleteUrl = route('debitor.destroy', $row->id);

                return '
                    <button type="button" class="btn btn-info btn-xs viewDebitor" data-url="'.$viewUrl.'">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button 
                        type="button"
                        class="btn btn-primary btn-xs editDebitor"
                        data-id="'.$row->id.'">
                        <i class="fas fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-xs deleteDebitor" data-id="'.$row->id.'">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Debitor $debitor): JsonResponse
    {
        $debitor->load(['sites', 'village', 'location']);

        return response()->json([
            'id' => $debitor->id,
            'account_name' => $debitor->account_name,
            'phone' => $debitor->phone,
            'gst_number' => $debitor->gst_number,
            'actual_address' => $debitor->actual_address,
            'billing_address' => $debitor->billing_address,
            'lease_area' => $debitor->lease_area,
            'lease_period' => $debitor->lease_period,
            'remark' => $debitor->remark,

            'location_id' => $debitor->location_id,
            'district' => optional($debitor->location)->district,
            'state' => optional($debitor->location)->state,
            'state_code' => optional($debitor->location)->state_code,

            'village_id' => $debitor->village_id,
            'village_name' => optional($debitor->village)->village_name,

            'sites' => $debitor->sites->map(fn ($s) => ['id' => $s->id, 'site_name' => $s->site_name]),
        ]);
    }

    public function update(StoreDebitorRequest $request, Debitor $debitor)
    {
        DB::transaction(function () use ($request, $debitor) {

            // =========================
            // LOCATION HANDLING
            // =========================
            $locationId = $debitor->location_id;

            if ($request->filled('location_id')) {

                // Use existing location (DO NOT update shared record)
                $locationId = $request->location_id;

            } elseif ($request->filled('state') && $request->filled('district')) {

                $location = Location::firstOrCreate(
                    [
                        'state' => $request->state,
                        'district' => $request->district,
                    ],
                    [
                        'state_code' => $request->state_code,
                    ]
                );

                $locationId = $location->id;
            }

            // =========================
            // VILLAGE HANDLING
            // =========================
            $villageId = $debitor->village_id;

            if ($request->filled('village_id')) {

                $villageId = $request->village_id;

            } elseif ($request->filled('village_name') && $locationId) {

                $village = Village::create([
                    'location_id' => $locationId,
                    'village_name' => $request->village_name,
                ]);

                $villageId = $village->id;
            }

            // =========================
            // UPDATE DEBITOR
            // =========================
            $debitor->update([
                'account_name' => $request->account_name,
                'phone' => $request->phone,
                'gst_number' => $request->gst_number,
                'actual_address' => $request->actual_address,
                'billing_address' => $request->billing_address,
                'location_id' => $locationId,
                'village_id' => $villageId,
                'lease_area' => $request->lease_area,
                'lease_period' => $request->lease_period,
                'remark' => $request->remark,
            ]);

            // =========================
            // SITES SYNC
            // =========================
            $existingSites = DebitorSite::where('debitor_id', $debitor->id)
                ->get()
                ->keyBy('site_name');

            $requestSites = collect($request->site_name ?? [])
                ->filter()
                ->values();

            // ➕ ADD NEW
            $requestSites->each(function ($siteName) use ($existingSites, $debitor) {
                if (! $existingSites->has($siteName)) {
                    DebitorSite::create([
                        'debitor_id' => $debitor->id,
                        'site_name' => $siteName,
                    ]);
                }
            });

            // ➖ REMOVE (if not used)
            foreach ($existingSites as $site) {

                if (! $requestSites->contains($site->site_name)) {

                    $used = DrillingReport::where('debitor_site_id', $site->id)->exists();

                    if (! $used) {
                        $site->delete();
                    }
                    // if used → keep it
                }
            }
        });

        return $request->ajax()
            ? response()->json(['success' => 'Debitor updated successfully'])
            : redirect()->route('debitors.index')->with('success', 'Debitor updated successfully');
    }

    public function destroy(Debitor $debitor): JsonResponse
    {
        DB::transaction(function () use ($debitor) {

            foreach ($debitor->sites as $site) {

                $used = DrillingReport::where('debitor_site_id', $site->id)->exists();

                if ($used) {
                    abort(422, 'Cannot delete debitor. One or more sites are already used in drilling reports.');
                }
            }

            // delete related sites first
            $debitor->sites()->delete();

            // then delete debitor
            $debitor->delete();
        });

        return response()->json([
            'success' => 'Debitor deleted successfully',
        ]);
    }

    public function show(Debitor $debitor)
    {
        $debitor->load(['location', 'sites']);

        return view('backend.debitor.view', compact('debitor'));
    }

    public function getSites(Debitor $debitor): JsonResponse
    {
        $sites = $debitor->sites()->select('id', 'site_name')->get();

        return response()->json($sites);
    }
}
