<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * RegisterUserRequest
 */
class RegisterUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'max:50'
            ],
            'last_name' => [
                'required',
                'max:50'
            ],
            'email' => [
                'required',
                'email'
            ],
            'password' => [
                'required'
            ],
            'sex' => [
                'required',
                'in:1,2,3'
            ],
            'role' => [
                'required',
                'in:01,02,03'
            ],
            'phone' => [
                'digits_between:9,11'
            ],
            'address' => [
                'max:200'
            ],
            'description' => [
                'max:1000'
            ]
        ];
    }
}
