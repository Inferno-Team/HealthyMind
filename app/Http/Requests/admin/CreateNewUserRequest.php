<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateNewUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email|email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'nullable|unique:users,phone',
        ];
    }
    public function values()
    {
        return [
            "username" => $this->input('username'),
            "email" => $this->input('email'),
            "first_name" => $this->input('first_name'),
            "last_name" => $this->input('last_name'),
            "phone" => $this->input('phone'),
            "password" => Hash::make('password'),
            'type' => $this->has('coach') && $this->input('coach') == 'on' ? 'coach' : 'normal',
            'status' => 'approved',
        ];
    }
}
