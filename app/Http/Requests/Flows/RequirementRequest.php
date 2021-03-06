<?php

namespace App\Http\Requests\Flows;

use App\Enums\RequirementStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'rule_section' => 'sometimes|required|numeric',
            'rule_group' => 'sometimes|required|string|max:500',
            'rule_reference' => 'sometimes|required|string|max:500',
            'rule_title' => 'sometimes|required|string|max:500',
            'rule_manual_reference' => 'sometimes|nullable|string|max:500',
            'rule_chapter' => 'sometimes|nullable|string|max:500',
            'company_manual' => 'sometimes|nullable|string|max:500',
            'company_chapter' => 'sometimes|nullable|string|max:500',
            'frequency' => 'sometimes|nullable|string|in:Annual,Performance|max:200',
//            'month_quarter' => 'sometimes|nullable|date_format:m.Y|after:'.Carbon::today()->subMonths(1)->format('m.Y'), // date
            'month_quarter' => 'sometimes|nullable|date_format:m.Y', // date
            'questions' => 'sometimes|nullable|string|max:3000',
            'finding' => 'sometimes|required|string|in:Finding,Observation,None|max:200',
            'deviation_statement' => 'sometimes|nullable|string|max:3000',
            'evidence_reference' => 'sometimes|nullable|file|max:100000', // file max size 100 mb
            'deviation_level' => 'sometimes|nullable|string|max:3000',
            'safety_level_before_action' => 'sometimes|nullable|string|max:3000',
//            'due_date' => 'sometimes|nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'due_date' => 'sometimes|nullable|date_format:d.m.Y', // date
            'repetitive_finding_ref_number' => 'sometimes|nullable|string|max:3000',
            'corrections' => 'sometimes|nullable|string|max:3000',
            'rootcause' => 'sometimes|nullable|string|max:3000',
            'corrective_actions_plan' => 'sometimes|nullable|string|max:3000',
            'preventive_actions' => 'sometimes|nullable|string|max:3000',
            'action_implemented_evidence' => 'sometimes|nullable|file|max:100000', // file max size 100 mb
            'safety_level_after_action' => 'sometimes|nullable|string|max:3000',
//            'effectiveness_review_date' => 'sometimes|nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'effectiveness_review_date' => 'sometimes|nullable|date_format:d.m.Y', // date
//            'response_date' => 'sometimes|nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'response_date' => 'sometimes|nullable|date_format:d.m.Y', // date
//            'extension_due_date' => 'sometimes|nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'extension_due_date' => 'sometimes|nullable|date_format:d.m.Y', // date
            'closed_date' => 'sometimes|nullable|date_format:d.m.Y H:i:s', // date
            'task_owner' => 'required|nullable|numeric',
            'task_status' => 'required|string|in:' . $task_statuses,
            'comment' => 'nullable|string|max:500' // comment textarea in task form
        ];
    }
}
