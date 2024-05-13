<?php

namespace App\Http\Requests\auth;

use App\Http\Traits\LocalResponse;
use App\Models\Disease;
use App\Models\Goal;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class RegisterRequest extends FormRequest
{
    use LocalResponse;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
            'dob' => ['required', 'numeric'],
            'weight' => ['required', function (string $attribute, mixed $value, Closure $fail) {
                if (!is_numeric($value))
                    $fail("weight must be numeric value");
                if ($value < 0)
                    $fail("weight must be positive number");
            },],
            'height' => ['required',  function (string $attribute, mixed $value, Closure $fail) {
                if (!is_numeric($value)) {
                    $fail("height must be numeric value");
                    return;
                }
                if ($value < 0) {
                    $fail("height must be positive number");
                    return;
                }
            },],
            'gender' => ['required', 'in:male,female'],
        ];
    }
    public function userData(): array
    {
        return [
            'first_name' => $this->input('firstname'),
            'last_name' => $this->input('lastname'),
            'email' => $this->input('email'),
            'password' => Hash::make($this->input('password')),
            "status" => "approved",
        ];
    }
    public function userDetails($user_id): array
    {
        return [
            'user_id' => $user_id,
            'weight' => $this->input('weight'),
            'height' => $this->input('height'),
            'gender' => $this->input('gender'),
            'dob' => $this->input('dob'),
        ];
    }
    public function diseases()
    {
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        info($this->all());
        info($validator->errors());
        throw new HttpResponseException($this->returnError("failed on validationm", 400, $validator->errors()));
    }
}
