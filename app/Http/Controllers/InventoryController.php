<?php

namespace App\Http\Controllers;

use App\Models\inventory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    
    public function index()
    {
        return Inventory::all();
    }
   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string',
            'description' => 'string|max:1500',
            'category' => 'required|string',
            'stock' => 'integer',
            'price' => 'numeric',
            'image' => 'file|max:40000'
        ]);

        if($request->hasFile('image')){
            $validated['image'] = $request->file('image')->store('inventory', 'public');
            $validated['image'] = str_replace('public/', '', $validated['image']);
        }

        $inventory = Inventory::create($validated);

        return response()->json(['success' => true, 'inventory' => $inventory]);
    }

    public function show($id)
    {
        $inventory = Inventory::findOrFail($id);
        return response()->json($inventory);
    }

    
    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);
        
        $inventory->update($request->all());

        return response()->json($inventory);
    }


    public function destroy($id)
    {
        Inventory::destroy($id);
        return response()->json(['success'=>true]);
    }
}
