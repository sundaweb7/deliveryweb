<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Admin\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::with('mitra')->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mitra_id'=>'required|exists:mitras,id',
            'name'=>'required',
            'description'=>'nullable',
            'price'=>'required|integer',
            'stock'=>'integer'
        ]);
        $product = Product::create($data);
        return new ProductResource($product);
    }

    public function show($id)
    {
        return new ProductResource(Product::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name'=>'sometimes|required',
            'description'=>'nullable',
            'price'=>'integer',
            'stock'=>'integer'
        ]);
        $product->update($data);
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
