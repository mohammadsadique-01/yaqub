<?php

namespace App\Http\Controllers;

use App\Models\DebitorSite;

class DebitorSiteController extends Controller
{
    public function destroy(DebitorSite $debitorSite)
    {
        // ❌ DO NOT CASCADE drilling_reports
        // ❌ DO NOT delete debitor

        // Check if used in drilling reports
        if ($debitorSite->drillingReports()->exists()) {
            return response()->json([
                'message' => 'This site is already used in drilling reports',
            ], 422);
        }

        $debitorSite->delete();

        return response()->json([
            'success' => true,
            'message' => 'This site is deleted.',
        ]);
    }
}
