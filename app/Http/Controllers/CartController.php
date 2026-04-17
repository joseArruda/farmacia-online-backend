<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function index()
    {
        try{
            $carts = Cart::with('product')->get();

            return response()->json([
                'success' => true,
                'message' => 'Itens do carrinho retornados com sucesso.',
                'data' => $carts
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Erro ao retonar os itens do carrinho.',
                'error' => $e->getMessage()
            ], 500);
        };
    
    }

    public function store(StoreCartRequest $request)
    {
        try{
            DB::beginTransaction();

            $validated = $request->validated();

            $cart = Cart::where('id_product', $validated['id_product'])->first();

            if($cart){
                $cart->quantity += $validated['quantity'];
                $cart->save();
            }else{
                $cart = Cart::create($validated);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $cart->load('product'),
                'message' => 'Produto adicionado ao carrinho com sucesso.'
            ], 201);
        } catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar o produto ao carrinho.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateCartRequest $request, $id){
        try{
            DB::beginTransaction();

            $cart = Cart::findOrFail($id);
            if($request->action === 'increment'){
                $cart->quantity += 1;
            }

            if($request->action === 'decrement'){
                $cart->quantity -= 1;
            }

            if($cart->quantity <= 0){
                $cart->delete();
                
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Produto removido do carrinho'
                ], 200);
            }

            $cart->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $cart->load('product'),
                'message' => 'Quantidade atualizada com sucesso!'
            ], 200);

        }catch(\Exception $e){

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar a quantidade.',
                'error' => $e->getMessage()
            ], 500);
        }


    }

    public function destroy($id)
    {
        try{
            DB::beginTransaction();

            $cart = Cart::findOrFail($id);
            $cart->delete();

            $cartUpdate = Cart::with('product')->get();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $cartUpdate,
                'message' => 'Produto removido do carrinho com sucesso!'
            ], 200);
        }
        catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover produto do carrinho.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
