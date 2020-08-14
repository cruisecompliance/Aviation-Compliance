<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowsData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Flow $flow
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Flow $flow)
    {
        // load relation data
        $flow->load('company');
        $flow->load('requirement');

        // return view with data
        return view('admin.flows.requirements', [
            'flow' => $flow,
        ]);

    }

    /**
     * DataTable - get data for index page
     *
     * @param Flow $flow
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function datatable(Flow $flow, Request $request)
    {
        $builder = FlowsData::whereFlowId($flow->id);

        return datatables()->of($builder)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-rule_reference="' . $row->rule_reference . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
//                    $btn = $btn. '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $flow_id
     * @param string $rule_reference
     * @return \Illuminate\Http\Response
     */
    public function edit(int $flow_id, string $rule_reference)
    {
        // get rule reference data
        $flowData = FlowsData::whereFlowId($flow_id)->whereRuleReference($rule_reference)->first();

        return response()->json([
            'success' => true,
            'resource' => $flowData,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Flow $flow
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Flow $flow, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rule_section' => 'required|numeric',
            'rule_group' => 'required|string',
            'rule_reference' => 'required|string',
            'rule_title' => 'required|string',
            'rule_manual_reference' => 'nullable|string',
            'rule_chapter' => 'nullable|string',
            'company_manual' => 'nullable|string',
            'company_chapter' => 'nullable|string',
            'frequency' => 'nullable|string',
            'month_quarter' => 'nullable|string',
            'assigned_auditor' => 'nullable|string',
            'assigned_auditee' => 'nullable|string',
            'comments' => 'nullable|string',
            'finding' => 'nullable|string',
            'deviation_statement' => 'nullable|string',
            'evidence_reference' => 'nullable|string',
            'deviation_level' => 'nullable|string',
            'safety_level_before_action' => 'nullable|string',
            'due_date' => 'nullable|date', // date
            'repetitive_finding_ref_number' => 'nullable|string',
            'assigned_investigator' => 'nullable|string',
            'corrections' => 'nullable|string',
            'rootcause' => 'nullable|string',
            'corrective_actions_plan' => 'nullable|string',
            'preventive_actions' => 'nullable|string',
            'action_implemented_evidence' => 'nullable|string',
            'safety_level_after_action' => 'nullable|string',
            'effectiveness_review_date' => 'nullable|date', // date
            'response_date' => 'nullable|date', // date
            'extension_due_date' => 'nullable|date', // date
            'closed_date' => 'nullable|date', // date

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $flow->flowData()
            ->whereRuleReference($request->rule_reference)
            ->update($request->except(
                '_token',
                '_method',
                'rule_section',
                'rule_group',
                'rule_reference',
                'rule_title',
                'rule_manual_reference',
                'rule_chapter'
            ));

//         $flow->flowData()->whereRuleReference($request->rule_reference)->update([
//            'company_manual' => $request->company_manual,
//            'company_chapter' => $request->company_chapter,
//            'frequency' => $request->frequency,
//            'month_quarter' => $request->month_quarter,
//            'assigned_auditor' => $request->assigned_auditor,
//            'assigned_auditee' => $request->assigned_auditee,
//            'comments' => $request->comments,
//            'finding' => $request->finding,
//            'deviation_statement' => $request->deviation_statement,
//            'evidence_reference' => $request->evidence_reference,
//            'deviation_level' => $request->deviation_level,
//            'safety_level_before_action' => $request->safety_level_before_action,
//            'due_date' => $request->due_date,
//            'repetitive_finding_ref_number' => $request->repetitive_finding_ref_number,
//            'assigned_investigator' => $request->assigned_investigator,
//            'corrections' => $request->corrections,
//            'rootcause' => $request->rootcause,
//            'corrective_actions_plan' => $request->corrective_actions_plan,
//            'preventive_actions' => $request->preventive_actions,
//            'action_implemented_evidence' => $request->action_implemented_evidence,
//            'safety_level_after_action' => $request->safety_level_after_action,
//            'effectiveness_review_date' => $request->effectiveness_review_date,
//            'response_date' => $request->response_date,
//            'extension_due_date' => $request->extension_due_date,
//            'closed_date' => $request->closed_date,
//        ]);


        return response()->json([
            'success' => true,
            'message' => "{$request->rule_reference} was update successfully.",
            'resource' => $flow,
        ]);
    }

}
