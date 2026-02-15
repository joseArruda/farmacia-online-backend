<?php

namespace App\Http\Controllers;

use App\Models\inventory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inventory::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string',
            'description' => 'string|max:1500',
            'category' => 'required|string',
            'stock' => 'integer',
            'price' => 'numeric',
            'image' => 'file|mimes:jpg,png|max:40000'
        ]);

        if($request->hasFile('image')){
            $validated['image'] = $request->file('image')->store('inventory', 'public');
            $validated['image'] = str_replace('public/', '', $validated['image']);
        }

        $inventory = Inventory::create($validated);

        return response()->json(['success' => true, 'inventory' => $inventory]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $inventory = Inventory::findOrFail($id);
        return response()->json($inventory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $product = Inventory::FindOrFail($id);

        $product->update($request->all());

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string|max:1500',
            'category' => 'string',
            'stock' => 'integer',
            'price' => 'numeric',
            'image' => 'file|mimes:jpg,png|max:40000'
        ]);
        $inventory->update($data);

        return response()->json(['success' => true, 'inventory' => $inventory]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Inventory::destroy($id);
        return response()->json(['success'=>true]);
    }
}
