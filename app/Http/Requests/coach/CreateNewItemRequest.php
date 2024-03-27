<?php

namespace App\Http\Requests\coach;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateNewItemRequest extends FormRequest
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
            "type" => 'required|in:meal,exercise',
            "item_name" => 'required|string',
            "item_type" => 'required|string',
            "qty" => 'required_if:type,meal',
        ];
    }
}
