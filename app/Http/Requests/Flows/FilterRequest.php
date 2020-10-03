<?php

namespace App\Http\Requests\Flows;

use App\Models\Filter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
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
        // request filter data
        $filter = [
            'name' => $this->name,
            'user_id' => Auth::user()->id,
        ];

        return [
            'name' =>
                'required', 'string', 'min:2', 'max:26',
                'name' => Rule::unique('filters')->where(function ($query) use ($filter) {
                    return $query
                        ->where('name', $filter['name'])
                        ->where('user_id', $filter['user_id']);
                }),
            'rule_reference' => 'sometimes|nullable|string',
            'rule_section' => 'sometimes|nullable|numeric',
            'assignee' => 'sometimes|nullable|numeric',
        ];
    }
}
