<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreditorRequest;
use App\Models\Creditor;
use Illuminate\View\View;

class CreditorController extends Controller
{
    public function index(): View
    {
        return view('backend.creditor.index');
    }

    public function store(StoreCreditorRequest $request)
    {
        $creditor = Creditor::create($request->all());

        if ($request->site_at) {
            foreach ($request->site_at as $site) {
                if (!empty($site)) {
                    $creditor->sites()->create([
                        'site_name' => $site
                    ]);
                }
            }
        }

        return redirect()
            ->route('creditor.index')
            ->with('success', 'Creditor created successfully!');
    }

}
