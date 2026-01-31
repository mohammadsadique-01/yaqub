<?php

namespace App\Http\Middleware;

use App\Models\FinancialYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinancialYearMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('financial_year_id')) {
            $fy = FinancialYear::where('is_active', true)->first();

            if ($fy) {
                session([
                    'financial_year_id' => $fy->id,
                    'financial_year_name' => $fy->name,
                ]);
            }
        }

        return $next($request);
    }
}
