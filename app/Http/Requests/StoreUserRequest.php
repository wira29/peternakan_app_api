<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;


class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage-users');
    }

    public function prepareForValidation() : void
    {
        $user = Auth::user();
        $this->merge([
            'created_by' => $user->uuid,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
            'role' => [
                'bail',
                'required',
                'exists:roles,name'
            ],
            'email' => [
                'bail',
                'required',
                'string',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols(),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'User name is required.',
            'name.string' => 'User name must be a valid text.',
            'name.max' => 'User name must not exceed 255 characters.',

            'role.required' => 'Role is required.',
            'role.string' => 'Role must be a valid text',
            'role.exists' => 'Role must be an existing role',

            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a valid text.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid text.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.mixedCase' => 'Password must contain both uppercase and lowercase letters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.', 
        ];
    }
    
    public function getData(): array
    {
        return [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'created_by' => $this->input('created_by'),
            'role' => $this->input('role'),
        ];
    }
}
