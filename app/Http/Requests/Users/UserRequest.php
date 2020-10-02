<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ])
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $email = null;

        // method post for create
        if ($this->isMethod('POST')) {
            $email = 'required|string|email|min:4|unique:users,email';
        }

        // method patch for update
        if ($this->isMethod('PATCH')) {
            $email = 'required|string|email|min:4|unique:users,email,' . $this->id;
        }

        return [
            'name' => 'required|string|min:4',
            'email' => $email,
            'password' => 'required|string|min:8',
            'status' => 'required|boolean',
            'company' => 'required|numeric',
            'role' => 'required|string',
        ];
    }
}
