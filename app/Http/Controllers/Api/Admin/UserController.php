<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Admin\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required',
            'email'=>'nullable|email|unique:users',
            'phone'=>'nullable|unique:users',
            'role'=>'required|in:customer,mitra,driver',
            'password'=>'required|min:6'
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return new UserResource($user);
    }

    public function show($id)
    {
        return new UserResource(User::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name'=>'sometimes|required',
            'email'=>'sometimes|email|unique:users,email,'.$id,
            'phone'=>'sometimes|unique:users,phone,'.$id,
            'role'=>'sometimes|in:customer,mitra,driver',
            'password'=>'nullable|min:6'
        ]);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return new UserResource($user);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
