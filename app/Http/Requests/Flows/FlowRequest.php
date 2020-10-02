<?php

namespace App\Http\Requests\Flows;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class FlowRequest extends FormRequest
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
        $title = null;

        // method post for create
        if ($this->isMethod('POST')) {
            $title = 'required|string|min:4|unique:flows';
        }

        // method patch for update
        if ($this->isMethod('PATCH')) {
            $title = 'required|string|min:4|unique:flows,title,' . $this->id;
        }

        return [
            'title' => $title,
            'description' => 'nullable|string|min:4|max:255',
            'company' => 'required|numeric',
            'requirements' => 'required|numeric',
        ];
    }
}
