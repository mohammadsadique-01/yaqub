<?php

namespace App\Http\Controllers;

use App\Models\Debitor;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

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
}
