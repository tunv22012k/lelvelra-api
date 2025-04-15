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
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'min:8',
                'max:20',
                'confirmed' // password_confirmation
            ],
            'sex' => [
                'required',
                'in:1,2,3'
            ],
            'role' => [
                'required',
                'regex:/^(01|02|03)$/'
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

    /**
     * messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'role.regex' => 'Vai trò không hợp lệ. Vui lòng nhập chính xác: 01(người dùng), 02(người bán hàng) hoặc 03(admin).',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ];
    }
}
