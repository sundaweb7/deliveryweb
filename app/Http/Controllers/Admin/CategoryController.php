<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $q = Category::orderBy('created_at','desc');
        $total = $q->count();
        $rows = $q->offset($start)->limit($length)->get();
        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->name,
                $r->description ?? '-',
                ucfirst($r->status),
                '<button class="btn btn-sm btn-edit" data-id="'.$r->id.'">Edit</button> <button class="btn btn-sm btn-delete" data-id="'.$r->id.'">Delete</button>'
            ];
        });
        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name'=>'required|string|max:255','description'=>'nullable|string','status'=>'in:active,inactive']);
        $cat = Category::create($data);
        return response()->json(['message'=>'Category created','category'=>$cat]);
    }

    public function show($id)
    {
        return Category::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $cat = Category::findOrFail($id);
        $data = $request->validate(['name'=>'required|string|max:255','description'=>'nullable|string','status'=>'in:active,inactive']);
        $cat->update($data);
        return response()->json(['message'=>'Category updated','category'=>$cat]);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(['message'=>'Category deleted']);
    }
}