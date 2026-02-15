<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        return response()->json(
        Cart::with('product')->get()
    );
    }

    public function store(Request $request)
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

    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
