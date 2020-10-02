<?php

namespace App\Http\Requests\Companies;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyRequest extends FormRequest
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

        $name = null;

        // method post for create
        if ($this->isMethod('POST')) {
            $name = 'required|string|min:4|max:50|unique:companies,name';
        }

        // method patch for update
        if ($this->isMethod('PATCH')) {
            $name = 'required|string|min:4|max:50|unique:companies,name,' . $this->id;
        }

        return [
            'name' => $name,
            'url' => 'required|string|url|min:4|unique:companies,url',
            'status' => 'required|boolean',
        ];
    }
}
