<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $TotalNumberOfGuests = 0;
        $NumberofCurrentGuests = 0;
        $totalRevenue = 0;
        $totalReturnAdvanceAmount = 0;
        $newdaysLeft = 0;
        $clientBillingCount = 0;

        return view('backend.dashboard', compact(
            'user',
            'TotalNumberOfGuests',
            'NumberofCurrentGuests',
            'totalRevenue',
            'totalReturnAdvanceAmount',
            'newdaysLeft',
            'clientBillingCount'
        ));
    }
}
