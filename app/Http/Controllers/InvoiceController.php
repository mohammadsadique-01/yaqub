<?php

namespace App\Http\Controllers;

use App\Models\Debitor;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $debitors = Debitor::latest()->get();
        $operators = Operator::latest()->get();

        return view('backend.invoice.index', compact(['debitors', 'operators']));
    }
}
