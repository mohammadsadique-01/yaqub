<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CreditorController extends Controller
{
    public function index(): View
    {
        return view('backend.creditor.index');
    }
}
