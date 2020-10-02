<?php

namespace App\Http\Requests\Users;

use App\Enums\RequrementStatus;
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
        // method post for create
        if ($this->isMethod('POST')) {
            return [
                'name' => 'required|string|min:4|max:64',
                'email' => 'required|string|email|min:4|unique:users,email',
                'password' =>'required|string|min:8|max:64',
                'status' => 'required|boolean',
                'company' => 'required|numeric',
                'role' => 'required|string',
            ];

        }

        // method patch for update
        if ($this->isMethod('PATCH')) {
            return [
                'name' => 'required|string|min:4|max:64',
                'email' => 'required|string|email|min:4|unique:users,email,' . $this->user->id,
                'status' => 'required|boolean',
                'company' => 'required|numeric',
                'role' => 'required|string',
            ];
        }

    }
}
