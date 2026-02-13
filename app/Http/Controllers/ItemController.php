<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Item::latest()->get();

        return response()->json([
            'status' => true,
            'data'   => $items
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required'
        ]);

        $item = Item::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Item added successfully',
            'data'    => $item
        ]);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $request->validate([
            'name' => 'required'
        ]);

        $item->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Item updated successfully'
        ]);
    }

    public function destroy(Item $item): JsonResponse
    {
        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Item deleted successfully'
        ]);
    }
}
