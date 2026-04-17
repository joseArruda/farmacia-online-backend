<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    
    public function index(Request $request)
    {
        $per_page = min($request->get('per_page',10),100);
        $products = Inventory::query()
            ->select('id','name','description','stock','category','price','image');
            if($request->has('category')){
                $products->where('category', $request->category);
            }

        $products = $products
            ->orderBy('created_at', 'desc')
            ->paginate($per_page);
             
        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Produtos retornados com sucesso.'
        ], 200);
    }
   
    public function store( StoreProductRequest $request)
    {
        $validated = $request->validated();

        if($request->hasFile('image')){
            $validated['image'] = $request->file('image')
            ->store('inventory', 'public');
        }

        $product = Inventory::create($validated);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Produto criado com sucesso!'
        ], 201);
    }

    public function show(Inventory $inventory)
    {
        return response()->json([
            'success' => true,
            'data' => $inventory,
            'message' => 'Produto carregado com êxito.'
        ], 200);
    }

    
    public function update(UpdateProductRequest $request, Inventory $inventory)
    {
        try{
            DB::beginTransaction();
            $validated = $request->validated();
            if($request->hasFile('image')){
                if($inventory->image && Storage::disk('public')
                ->exists($inventory->image)){
                    Storage::disk('public')->delete($inventory->image);
                }
                $validated['image'] = $request->file('image')
                ->store('inventory','public');
            }
            $inventory->update($validated);
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $inventory->fresh(),
                'message' => 'Produto atualizado.'
            ], 200);
        }
        catch(\Exception $e){ DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o produto.',
                'error' => config('app.debug') ? $e->getMessage() : null 
            ], 500);
        }
    }


    public function destroy(Inventory $inventory)
    {
        try{

            DB::beginTransaction();

            if($inventory->image && Storage::disk('public')->exists($inventory->image)){
                Storage::disk('public')->delete($inventory->image);
            }

            $inventory->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produto removido com sucesso.'
            ],200);

        }catch(\Exception $e){

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover produto.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ],500);

        }
    }
}
