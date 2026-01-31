<?php

namespace App\Http\Controllers;

use App\Models\Debitor;
use App\Models\DrillingReport;
use App\Models\Operator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class DrillingController extends Controller
{
    public function index(): View
    {
        $debitors = Debitor::latest()->get();
        $operators = Operator::latest()->get();

        return view('backend.drilling.index', compact(['debitors', 'operators']));
    }

    public function create(): View
    {
        $debitors = Debitor::latest()->get();
        $operators = Operator::latest()->get();

        return view('backend.drilling.form', compact(['debitors', 'operators']));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'debitor_id' => 'required|integer',
            'debitor_site_id' => 'required|integer',
            'operator_id' => 'required|integer',
            'start_time' => 'required|numeric',
            'end_time' => 'required|numeric',
            'total_hours' => 'required|numeric',
            'diesel' => 'nullable|numeric',
            'hole' => 'nullable|integer',
            'meter' => 'nullable|numeric',
            'balance_diesel' => 'nullable|numeric',
            'remark' => 'nullable|string',
        ]);

        $validated['financial_year_id'] = session('financial_year_id');

        DrillingReport::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Drilling report saved successfully',
        ]);
    }

    public function getData(Request $request)
    {
        $query = DrillingReport::with(['debitor', 'site', 'operator'])
            ->where('financial_year_id', session('financial_year_id'))
            ->latest('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('debitor', fn ($row) => $row->debitor->account_name ?? '-'
            )
            ->addColumn('site', fn ($row) => $row->site->site_name ?? '-'
            )
            ->addColumn('operator', fn ($row) => $row->operator->name ?? '-'
            )
            ->addColumn('operator', fn ($row) => $row->operator->name ?? '-'
            )
            ->addColumn('action', function ($row) {
                $viewUrl = route('drilling.show', $row->id);

                return '
                    <button type="button" class="btn btn-info btn-xs viewDrilling" data-url="'.$viewUrl.'">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button 
                        type="button"
                        class="btn btn-primary btn-xs editDebitor"
                        data-id="'.$row->id.'">
                        <i class="fas fa-pen"></i>
                    </button>

                    <button class="btn btn-danger btn-xs deleteDrilling" data-id="'.$row->id.'">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function destroy(DrillingReport $drillingReport): JsonResponse
    {
        $drillingReport->delete();

        return response()->json([
            'message' => 'Debitor deleted successfully',
        ]);
    }

    public function show(DrillingReport $drillingReport): View
    {
        $drillingReport->load(['debitor', 'site', 'operator']);

        return view('backend.drilling.view', compact('drillingReport'));
    }
}
