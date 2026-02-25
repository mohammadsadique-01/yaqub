<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(): View
    {
        $items = Item::latest()->get();

        return view('backend.items.index', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'hsn_sac' => 'required',
            'unit' => 'required',
        ]);

        Item::create($request->only('name', 'hsn_sac', 'unit'));

        return redirect()
            ->route('items.index')
            ->with('success', 'Item added successfully');
    }

    public function edit(Item $item): View
    {
        $items = Item::latest()->get();

        return view('backend.items.index', compact('items', 'item'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'hsn_sac' => 'required',
            'unit' => 'required',
        ]);

        $item->update($request->only('name', 'hsn_sac', 'unit'));

        return redirect()
            ->route('items.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', 'Item deleted successfully');
    }
}
