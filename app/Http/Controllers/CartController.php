<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
        Cart::with('product')->get()
    );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add(Request $request)
    {
        $request->validate([
            'id_product' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Cart::where('id_product', $request->id_product)->first();
        if($product){
            $product->quantity += $request->quantity;
            $product->save();
        }else{
            $product = Cart::create([
                'id_product' => $request->id_product,
                'quantity' => $request->quantity
            ]);
        }
         return response()->json(
        Cart::with('product')->get()
    );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
