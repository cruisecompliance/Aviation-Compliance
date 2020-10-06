<?php

namespace App\Http\Requests\Flows;

use App\Enums\RequrementStatus;
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
        $task_statuses = RequrementStatus::statusTransitions()->implode('status_name', ',');

        return [
            'rule_section' => 'sometimes|required|numeric',
            'rule_group' => 'sometimes|required|string',
            'rule_reference' => 'sometimes|required|string',
            'rule_title' => 'sometimes|required|string',
            'rule_manual_reference' => 'sometimes|nullable|string',
            'rule_chapter' => 'sometimes|nullable|string',
            'company_manual' => 'sometimes|nullable|string',
            'company_chapter' => 'sometimes|nullable|string',
            'frequency' => 'sometimes|required|string|in:annual,performance',
            'month_quarter' => 'sometimes|nullable|string',
            'assigned_auditor' => 'sometimes|nullable|numeric', // assigned
            'assigned_auditee' => 'sometimes|nullable|numeric', // assigned
            'questions' => 'sometimes|nullable|string',
            'finding' => 'sometimes|nullable|string',
            'deviation_statement' => 'sometimes|nullable|string',
            'evidence_reference' => 'sometimes|nullable|string',
            'deviation_level' => 'sometimes|nullable|string',
            'safety_level_before_action' => 'sometimes|nullable|string',
            'due_date' => 'sometimes|nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'repetitive_finding_ref_number' => 'sometimes|nullable|string',
            'assigned_investigator' => 'sometimes|nullable|numeric', // assigned
            'corrections' => 'sometimes|nullable|string',
            'rootcause' => 'sometimes|nullable|string',
            'corrective_actions_plan' => 'sometimes|nullable|string',
            'preventive_actions' => 'sometimes|nullable|string',
            'action_implemented_evidence' => 'sometimes|nullable|string',
            'safety_level_after_action' => 'sometimes|nullable|string',
            'effectiveness_review_date' => 'sometimes|nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'response_date' => 'sometimes|nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'extension_due_date' => 'sometimes|nullable|date_format:d.m.Y|after:'.Carbon::today()->format('d.m.Y'), // date
            'closed_date' => 'sometimes|nullable|date_format:d.m.Y H:i:s', // date
            'task_status' => 'required|string|in:' . $task_statuses,
        ];
    }
}
