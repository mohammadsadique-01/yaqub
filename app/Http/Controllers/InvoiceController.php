<?php

namespace App\Http\Controllers;

use App\Models\Debitor;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public static function generateInvoiceNumber()
    {
        $companyCode = 'AME';
        $financialYear = activeFinancialYear();
        $start = date('y', strtotime($financialYear->start_date));
        $end = date('y', strtotime($financialYear->end_date));
        $yearFormat = $start.'-'.$end;

        $lastInvoice = Invoice::where('financial_year_id', $financialYear->id)->latest()->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -2);
            $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '01';
        }

        $nextNumberFormatted = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        return $companyCode.'/'.$yearFormat.'/'.$nextNumberFormatted;
    }

    public function index(): View
    {
        $debitors = Debitor::latest()->get();
        $operators = Operator::latest()->get();
        $items = Item::latest()->get();

        return view('backend.invoice.index', compact(['debitors', 'operators', 'items']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required',
            'date' => 'required',
            'debitor_id' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'date' => $request->date,
                'financial_year_id' => activeFinancialYear()->id,
                'debitor_id' => $request->debitor_id,
                'debitor_site_id' => $request->debitor_site_id,
                'total_qty' => $request->total_qty,
                'total_amount' => $request->total_amount,
                'total_a_amount' => $request->total_a_amount,
                'cgst_percent' => $request->cgst_percent,
                'cgst_amount' => $request->cgst_amount,
                'sgst_percent' => $request->sgst_percent,
                'sgst_amount' => $request->sgst_amount,
                'igst_percent' => $request->igst_percent,
                'igst_amount' => $request->igst_amount,
                'freight_amount' => $request->freight_amount,
                'discount_type' => $request->discount_type,
                'discount_amount' => $request->discount_amount,
                'net_amount' => $request->net_amount,
                'net_a_amount' => $request->net_a_amount,
            ]);

            foreach ($request->items as $row) {

                if (! empty($row['item_id'])) {

                    $invoice->items()->create([
                        'item_id' => $row['item_id'],
                        'hsn_sac' => $row['hsn_sac'],
                        'unit' => $row['unit'],
                        'qty' => $row['qty'],
                        'price' => $row['price'],
                        'amount' => $row['amount'],
                        'a_price' => $row['a_price'],
                        'a_amount' => $row['a_amount'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Invoice saved successfully',
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getData(Request $request)
    {
        $query = Invoice::with(['debitor'])
            ->select('invoices.*')
            ->orderBy('id', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('invoice', function ($row) {
                return $row->invoice_number;
            })

            ->addColumn('debitor', function ($row) {
                return optional($row->debitor)->account_name;
            })

            ->addColumn('tax', function ($row) {

                $formatPercent = function ($value) {
                    return (float) $value;
                };

                $html = '<table class="table table-sm table-bordered mb-0 text-nowrap" style="font-size:12px;">';

                // Base Amount
                if ($row->total_amount > 0) {
                    $html .= '<tr>
                                <td><strong>Amount</strong></td>
                                <td class="text-right">₹ '.indian_number($row->total_amount).'</td>
                            </tr>';
                }

                // CGST
                if ($row->cgst_percent > 0) {
                    $html .= '<tr>
                                <td>CGST ('.$formatPercent($row->cgst_percent).'%)</td>
                                <td class="text-right">₹ '.indian_number($row->cgst_amount).'</td>
                            </tr>';
                }

                // SGST
                if ($row->sgst_percent > 0) {
                    $html .= '<tr>
                                <td>SGST ('.$formatPercent($row->sgst_percent).'%)</td>
                                <td class="text-right">₹ '.indian_number($row->sgst_amount).'</td>
                            </tr>';
                }

                // IGST
                if ($row->igst_percent > 0) {
                    $html .= '<tr>
                                <td>IGST ('.$formatPercent($row->igst_percent).'%)</td>
                                <td class="text-right">₹ '.indian_number($row->igst_amount).'</td>
                            </tr>';
                }

                // Freight
                if ($row->freight_amount > 0) {
                    $html .= '<tr>
                                <td>Freight</td>
                                <td class="text-right">₹ '.indian_number($row->freight_amount).'</td>
                            </tr>';
                }

                // Discount
                if ($row->discount_amount > 0) {

                    if ($row->discount_type === 'percentage') {
                        $label = 'Discount ('.$formatPercent($row->discount_percent).'%)';
                    } else {
                        $label = 'Discount';
                    }

                    $html .= '<tr>
                                <td>'.$label.'</td>
                                <td class="text-right text-danger">- ₹ '.indian_number($row->discount_amount).'</td>
                            </tr>';
                }

                $html .= '</table>';

                return $html;
            })

            ->addColumn('amount', function ($row) {
                return indian_number($row->net_amount);
            })

            ->addColumn('a_amount', function ($row) {
                return indian_number($row->net_a_amount);
            })

            ->addColumn('action', function ($row) {
                return '
                    <button type="button" 
                            class="btn btn-info btn-xs viewInvoice" 
                            data-url="'.route('invoice.show', $row->id).'">
                        <i class="fas fa-eye"></i>
                    </button>

                    <button class="btn btn-sm btn-primary btn-xs editBtn" data-id="'.$row->id.'">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button class="btn btn-danger btn-xs deleteInvoice" data-id="'.$row->id.'">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })

            ->rawColumns(['tax', 'action'])
            ->make(true);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->json([
            'status' => true,
            'message' => 'Invoice deleted successfully.',
        ]);
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');

        return response()->json([
            'status' => true,
            'data' => $invoice,
        ]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'invoice_number' => $request->invoice_number, // keep same
            'date' => $request->date,
            'debitor_id' => $request->debitor_id,
            'debitor_site_id' => $request->debitor_site_id,
            'cgst_percent' => $request->cgst_percent,
            'sgst_percent' => $request->sgst_percent,
            'igst_percent' => $request->igst_percent,
            'freight_amount' => $request->freight_amount,
            'discount_type' => $request->discount_type,
            'discount_amount' => $request->discount_amount,
            'net_amount' => $request->net_amount,
            'net_a_amount' => $request->net_a_amount,
            'with_tax' => $request->with_tax ? 1 : 0,
        ]);

        // Delete old items
        $invoice->items()->delete();

        // Insert new items
        if ($request->items) {
            foreach ($request->items as $item) {
                $invoice->items()->create($item);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Invoice updated successfully',
        ]);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'debitor', 'debitorSite']);

        logger($invoice);

        return view('backend.invoice.view', compact('invoice'));

        return response()->json([
            'status' => true,
            'data' => $invoice,
        ]);
    }
}
