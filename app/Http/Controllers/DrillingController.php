<?php

namespace App\Http\Controllers;

use App\Models\Debitor;
use App\Models\DebitorSite;
use App\Models\DrillingReport;
use App\Models\Operator;
use Barryvdh\DomPDF\Facade\Pdf;
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
            ->where('financial_year_id', session('financial_year_id'));

        // Clean arrays
        $debitors = $this->cleanArray($request->debitors ?? []);
        $sites = $this->cleanArray($request->sites ?? []);
        $operators = $this->cleanArray($request->operators ?? []);

        if (! empty($debitors)) {
            $query->whereIn('debitor_id', $debitors);
        }

        if (! empty($sites)) {
            $query->whereIn('debitor_site_id', $sites);
        }

        if (! empty($operators)) {
            $query->whereIn('operator_id', $operators);
        }

        if ($request->filled('from_date') || $request->filled('to_date')) {
            $query->whereBetween('date', [
                $request->from_date ?? '1900-01-01',
                $request->to_date ?? now()->toDateString(),
            ]);
        }

        return DataTables::of($query->latest())
            ->addIndexColumn()

            ->addColumn('date', fn ($r) => format_date($r->date))
            ->addColumn('debitor', fn ($r) => $r->debitor->account_name ?? '-')
            ->addColumn('site', fn ($r) => $r->site->site_name ?? '-')
            ->addColumn('operator', fn ($r) => $r->operator->name ?? '-')

            ->addColumn('hole', fn ($r) => indian_number($r->hole ?? 0))
            ->addColumn('meter', fn ($r) => indian_number($r->meter ?? 0))
            ->addColumn('diesel', fn ($r) => indian_number($r->diesel ?? 0))
            ->addColumn('total_hours', fn ($r) => indian_number($r->total_hours ?? 0))
            ->addColumn('balance_diesel', fn ($r) => indian_number($r->balance_diesel ?? 0))

            ->addColumn('action', function ($row) {
                return view('backend.drilling.action', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function cleanArray($arr)
    {
        return array_values(array_filter($arr, fn ($v) => ! is_null($v) && $v !== ''));
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

    public function edit(DrillingReport $drilling): JsonResponse
    {
        return response()->json($drilling->load(['debitor', 'site', 'operator']));
    }

    public function update(Request $request, DrillingReport $drilling): JsonResponse
    {
        $request->validate([
            'start_time' => 'required|numeric',
            'end_time' => 'required|numeric|gt:start_time',
        ]);

        $drilling->update($request->except(['financial_year_id ', 'debitor_id', 'debitor_site_id', 'operator_id']));

        return response()->json([
            'message' => 'Drilling report updated successfully',
        ]);
    }

    public function getSitesByDebitors(Request $request): JsonResponse
    {
        $debitorIds = $request->debitor_ids;

        $sites = DebitorSite::whereIn('debitor_id', $debitorIds)
            ->select('id', 'site_name')
            ->distinct()
            ->get();

        return response()->json($sites);
    }

    public function pdf(Request $request)
    {
        $query = DrillingReport::with(['debitor', 'site', 'operator'])
            ->where('financial_year_id', session('financial_year_id'));

        if (! empty($request->debitors)) {
            $query->whereIn('debitor_id', $request->debitors);
        }

        if (! empty($request->sites)) {
            $query->whereIn('debitor_site_id', $request->sites);
        }

        if (! empty($request->operators)) {
            $query->whereIn('operator_id', $request->operators);
        }

        if ($request->filled('from_date') || $request->filled('to_date')) {
            $query->whereBetween('date', [
                $request->from_date ?? '1900-01-01',
                $request->to_date ?? now()->toDateString(),
            ]);
        }

        $reports = $query->orderBy('date')->get();

        // totals
        $totals = [
            'holes' => indian_number($reports->sum('hole')),
            'meter' => indian_number($reports->sum('meter')),
            'diesel' => indian_number($reports->sum('diesel')),
            'hours' => indian_number($reports->sum('total_hours')),
            'balance_diesel' => indian_number($reports->sum('balance_diesel')),
        ];

        $pdf = Pdf::loadView('backend.drilling.pdf', compact(
            'reports',
            'totals',
            'request'
        ))->setPaper('A4', 'landscape');

        return $pdf->stream('drilling-report.pdf');
    }
}
