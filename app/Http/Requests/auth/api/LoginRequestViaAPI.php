<?php

namespace App\Http\Requests\auth\api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use App\Http\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequestViaAPI extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required_if:username,' . Null, 'string', 'email'],
            'username' => ['required_if:email,' . Null, 'string'],
            'password' => ['required', 'string'],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Login Failed', 'errors' => $validator->getMessageBag(),], 400));
    }
}
