<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user') ?? null;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($userId ? ",$userId" : ''),
            'phone' => 'nullable|string|max:30',
            'role' => 'required|string|in:admin,mitra,driver,user',
            'password' => ($this->isMethod('post') ? 'required' : 'nullable') . '|string|min:6'
        ];

        return $rules;
    }
}
