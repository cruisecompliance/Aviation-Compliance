<?php

namespace App\Http\Requests\Flows;

use App\Enums\RequirementStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MultipleAssignRequest extends FormRequest
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
        // task statuses (for validation)
        $task_statuses = RequirementStatus::statusTransitions()->implode('status_name', ',');

        return [
            'tasks_id' => 'required|string',
            'task_owner' => 'required|numeric',
            'month_quarter' => 'required|date_format:m.Y|after:'.Carbon::today()->subMonths(1)->format('m.Y'), // date
            'due_date' => 'required|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'task_status' => 'required|string|in:' . $task_statuses,
        ];
    }
}
