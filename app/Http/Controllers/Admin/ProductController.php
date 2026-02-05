<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start', 0);
        $length = (int)$request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? null;

        $query = Product::with('mitra','category');
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
                ($r->image ? '<img src="'.asset('storage/products/'.$r->image).'" style="height:40px;border-radius:4px" onerror="this.onerror=null;this.src=\''.asset('images/no-image.svg').'\';">' : '-'),
                $r->mitra ? $r->mitra->name : '—',
                $r->category ? $r->category->name : '-',
                $r->price,
                '<button class="btn btn-sm btn-edit" data-id="'.$r->id.'">Edit</button> <button class="btn btn-sm btn-delete" data-id="'.$r->id.'">Delete</button>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$data]);
    }

    public function store(\App\Http\Requests\Admin\ProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products','public');
            $data['image'] = basename($path);
        }
        $product = Product::create($data);
        return response()->json(['message'=>'Product created','product'=>$product]);
    }

    public function show($id)
    {
        $p = Product::findOrFail($id);
        $arr = $p->toArray();
        $arr['image_url'] = $p->image ? asset('storage/products/'.$p->image) : null;
        return $arr;
    }

    public function update(\App\Http\Requests\Admin\ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            // delete old image
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('products/'.$product->image);
            }
            $path = $request->file('image')->store('products','public');
            $data['image'] = basename($path);
        }
        $product->update($data);
        return response()->json(['message'=>'Product updated','product'=>$product]);
    }

    public function destroy($id)
    {
        $p = Product::findOrFail($id);
        if ($p->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('products/'.$p->image);
        }
        $p->delete();
        return response()->json(['message'=>'Product deleted']);
    }
}
