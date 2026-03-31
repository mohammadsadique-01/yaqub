<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperatorRequest;
use App\Models\Operator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OperatorController extends Controller
{
    public function index(): View
    {
        $operators = Operator::latest()->get();

        return view('backend.operators.index', compact('operators'));
    }

    public function store(OperatorRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();

            $image->storeAs('operators', $name, 'public');

            $data['image'] = $name;
        }

        Operator::create($data);

        return redirect()->route('operators.index')->with('success', 'Operator added successfully');
    }

    public function edit(Operator $operator): View
    {
        $operators = Operator::latest()->get();

        return view('backend.operators.index', compact('operator', 'operators'));
    }

    public function update(OperatorRequest $request, Operator $operator): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($operator->image) {
                Storage::disk('public')->delete('operators/'.$operator->image);
            }

            $image = $request->file('image');

            $name = time().'.'.$image->getClientOriginalExtension();

            $image->storeAs('operators', $name, 'public');

            $data['image'] = $name;
        }

        $operator->update($data);

        return redirect()->route('operators.index')->with('success', 'Operator updated successfully');
    }

    public function destroy(Operator $operator): RedirectResponse
    {
        // ✅ Check if operator is used anywhere
        if ($operator->drillingReports()->exists()) {
            return redirect()
                ->route('operators.index')
                ->with('error', 'This operator is already used in drilling reports and cannot be deleted.');
        }

        $operator->delete();

        return redirect()->route('operators.index')->with('success', 'Operator deleted successfully');
    }
}
