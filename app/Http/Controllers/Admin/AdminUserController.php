<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start', 0);
        $length = (int)$request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? null;

        $query = User::query();
        $total = $query->count();

        if ($search) {
            $query->where('name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%');
        }

        $filtered = $query->count();
        $rows = $query->offset($start)->limit($length)->get();

        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->name,
                $r->email,
                $r->phone,
                $r->role,
                '<button class="btn btn-sm btn-edit" data-id="'.$r->id.'">Edit</button> <button class="btn btn-sm btn-delete" data-id="'.$r->id.'">Delete</button>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$data]);
    }

    public function store(\App\Http\Requests\Admin\UserRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['password'])) $data['password'] = Hash::make($data['password']); else unset($data['password']);
        $user = User::create($data);
        return response()->json(['message'=>'User created','user'=>$user]);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(\App\Http\Requests\Admin\UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();
        if (!empty($data['password'])) $data['password'] = Hash::make($data['password']); else unset($data['password']);
        $user->update($data);
        return response()->json(['message'=>'User updated','user'=>$user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message'=>'User deleted']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
}
