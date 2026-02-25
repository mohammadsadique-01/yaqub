<?php

namespace App\Http\Controllers;

use App\Models\Debitor;
use App\Models\Item;
use App\Models\Operator;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $debitors = Debitor::latest()->get();
        $operators = Operator::latest()->get();
        $items = Item::latest()->get();

        return view('backend.invoice.index', compact(['debitors', 'operators', 'items']));
    }
}
