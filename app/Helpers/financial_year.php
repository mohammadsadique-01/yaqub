<?php

use App\Models\FinancialYear;

if (! function_exists('activeFinancialYear')) {
    function activeFinancialYear()
    {
        return session('financial_year')
            ?? FinancialYear::where('is_active', true)->first();
    }
}
