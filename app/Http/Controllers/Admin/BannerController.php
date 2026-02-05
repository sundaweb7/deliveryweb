<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.banners.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $q = Banner::orderBy('created_at','desc');
        $total = $q->count();
        $rows = $q->offset($start)->limit($length)->get();
        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->title,
                $r->image ? '<img src="'.asset('storage/'.$r->image).'" style="height:40px">' : '-',
                $r->link,
                ucfirst($r->status),
                '<button class="btn btn-sm btn-edit" data-id="'.$r->id.'">Edit</button> <button class="btn btn-sm btn-delete" data-id="'.$r->id.'">Delete</button>'
            ];
        });
        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['title'=>'required','link'=>'nullable|url','status'=>'in:active,inactive','image'=>'nullable|image|max:2048']);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners','public');
            $data['image'] = $path;
        }
        $banner = Banner::create($data);
        return response()->json(['message'=>'Banner created','banner'=>$banner]);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $data = $request->validate(['title'=>'required','link'=>'nullable|url','status'=>'in:active,inactive','image'=>'nullable|image|max:2048']);
        if ($request->hasFile('image')) {
            // delete old if exists
            if ($banner->image) Storage::disk('public')->delete($banner->image);
            $path = $request->file('image')->store('banners','public');
            $data['image'] = $path;
        }
        $banner->update($data);
        return response()->json(['message'=>'Banner updated','banner'=>$banner]);
    }

    public function show($id)
    {
        return Banner::findOrFail($id);
    }

    public function destroy($id)
    {
        $b = Banner::findOrFail($id);
        if ($b->image) Storage::disk('public')->delete($b->image);
        $b->delete();
        return response()->json(['message'=>'Banner removed']);
    }
}