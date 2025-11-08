<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
        $data = $this->json()->all();
        $data['updated_by'] = Auth::user()->id;
        $this->replace($data);
        $user_email = Auth::user()->email;
        if (isset($data['email']) && $data['email'] === $user_email) {
            unset($data['email']);
            $this->replace($data);
            // Log::info("Email matches authenticated user's email; removing from validation.");
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        \Log::info("Request JSON:", $this->json()->all());
        return [
             'name' => [
                'bail',
                'string',
                'max:255',
                'sometimes'
            ],
            'roles' => [
                'bail',
                'exists:roles,name',
                'sometimes'
            ],
            'email' => [
                'bail',
                'string',
                'email',
                'unique:users,email',
                'sometimes'
            ],
            'no_telp' => [
                'bail',
                'string',
                'sometimes'
            ],
            'password' => [
                'bail',
                'string',
                'sometimes',
                Password::min(8)->mixedCase()->letters()->numbers()->symbols(),
            ],
            'alamat' => [
                'bail',
                'nullable',
                'string',
            ],
            'updated_by' => [
                'bail',
                'nullable',
                'uuid',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'User name is required.',
            'name.string' => 'User name must be a valid text.',
            'name.max' => 'User name must not exceed 255 characters.',

            'roles.required' => 'Role is required.',
            'roles.string' => 'Role must be a valid text',
            'roles.exists' => 'Role must be an existing role',

            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a valid text.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use.',

            'no_telp.required' => 'No Telepon is required.',
            'no_telp.string' => 'No Telepon must be a valid text.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid text.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.mixedCase' => 'Password must contain both uppercase and lowercase letters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.', 
            'alamat.string' => 'Alamat must be a valid text.',
        ];
    }
    
    public function getData(): array
    {
        $data = $this->only([
            'name',
            'email',
            'no_telp',
            'password',
            'alamat',
            'roles',
            'updated_by',
        ]);
        
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return $data;
    }
}


