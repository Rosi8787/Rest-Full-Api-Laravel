<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();

        return response()->json([
            'message' => 'Data product berhasil diambil',
            'data' => $products
        ], 200);
    }


    public function store(Request $request)
    {
        $validate = $request->validate ([
            'name' => 'required|string|max:255',
            'price' =>'required|integer|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        $product = Product::create($validate);

        return response()->json([
            'message' => 'Product berhasil di buat',
            'data' => $product
        ],201);
    }


    public function show(string $id)
    {
        $product = Product::find($id);

        if(!$product){
            return response ()->json([
                'message' => 'product tidak ada',
            ],404);
        }

        return response()->json([
            'data'=> $product
        ],200);

    }


    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product tidak di temukan'
            ], 404);  
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|integer|min:0',
            'stock' => 'sometimes|required|integer|min:0'
        ]);

        $product->update($validated);

        return response()->json([
            'message'=> 'Product berhasil di update',
            'data'=> $product
        ],200);
    }


    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product){
            return response()->json([
                'message' =>'produk tidak di temukan'
            ],404);
        }

        $product->delete();

        return response()->json([
            'message'=> 'Product berhasil di hapus'
        ],200);
    }
}
