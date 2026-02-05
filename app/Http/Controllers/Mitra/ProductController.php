<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('mitra.products.index');
    }

    public function data(Request $request)
    {
        $mitra = $request->user()->mitra;
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $search = $request->get('search',[])['value'] ?? null;

        $query = Product::where('mitra_id', $mitra->id)->with('category');
        $total = $query->count();
        if ($search) {
            $query->where('name','like','%'.$search.'%');
        }
        $filtered = $query->count();
        $rows = $query->offset($start)->limit($length)->get();

        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->name,
                $r->category ? $r->category->name : '-',
                $r->price,
                $r->stock,
                '<button class="btn btn-sm btn-edit" data-id="'.$r->id.'">Edit</button> <button class="btn btn-sm btn-delete" data-id="'.$r->id.'">Delete</button>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$data]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required',
            'category_id'=>'nullable|exists:categories,id',
            'price'=>'required|numeric',
            'stock'=>'required|integer'
        ]);
        $mitra = $request->user()->mitra;
        $data['mitra_id'] = $mitra->id;
        $product = Product::create($data);
        return response()->json(['message'=>'Product created','product'=>$product]);
    }

    public function show($id)
    {
        $mitra = auth()->user()->mitra;
        $product = Product::where('mitra_id',$mitra->id)->findOrFail($id);
        return $product;
    }

    public function update(Request $request, $id)
    {
        $mitra = $request->user()->mitra;
        $product = Product::where('mitra_id',$mitra->id)->findOrFail($id);
        $data = $request->validate([
            'name'=>'required',
            'category_id'=>'nullable|exists:categories,id',
            'price'=>'required|numeric',
            'stock'=>'required|integer'
        ]);
        $product->update($data);
        return response()->json(['message'=>'Product updated','product'=>$product]);
    }

    public function destroy($id)
    {
        $mitra = auth()->user()->mitra;
        $product = Product::where('mitra_id',$mitra->id)->findOrFail($id);
        $product->delete();
        return response()->json(['message'=>'Product deleted']);
    }
}
