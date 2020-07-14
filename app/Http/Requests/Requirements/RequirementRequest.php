<?php

namespace App\Http\Requests\Requirements;

use Illuminate\Foundation\Http\FormRequest;

class RequirementRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:4',
            'description' => 'nullable|string|min:4',
            'user_file' => 'required|file|mimes:xlsx'
        ];
    }
}
