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
            'email' => 'required|unique:users,email|email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'nullable|unique:users,phone',
            'password' => 'required|string|min:8',
        ];
    }
    public function values()
    {
        return [
            "email" => $this->input('email'),
            "first_name" => $this->input('first_name'),
            "last_name" => $this->input('last_name'),
            "phone" => $this->input('phone'),
            "password" => Hash::make($this->input('password')),
            'type' => $this->has('coach') && $this->input('coach') == 'on' ? 'coach' : 'normal',
            'status' => 'approved',
        ];
    }
    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     info($validator->errors());
    // }
}
