<?php

namespace App\Http\Controllers\User;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Services\Flows\NotificationService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        // get latest company flow
        $flow = Flow::whereCompanyId(Auth::user()->company->id)->latest()->first();

        // check assigned users for flowData (Auditee, Auditor, Investigator)
        if (!empty($flow)){
            if(Auth::user()->hasRole([RoleName::AUDITEE, RoleName::AUDITOR, RoleName::INVESTIGATOR])){
                if (empty(FlowsData::checkAssignedUser(Auth::user()->id, $flow->id))) {
                    return abort(403, 'User not assigned ');
                }
            }
        }

        // get users by roles (for select input)
        $auditors = User::auditors()->active()->whereCompanyId($flow->company->id)->get();
        $auditees = User::auditees()->active()->whereCompanyId($flow->company->id)->get();
        $investigators = User::investigators()->active()->whereCompanyId($flow->company->id)->get();

        // return view with data
        return view('user.flows.requirements', [
            'flow' => $flow,
            'auditors' => $auditors,
            'auditees' => $auditees,
            'investigators' => $investigators,
        ]);

    }

    /**
     * DataTable - get data for index page
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        // get latest company flow
        $flow = Flow::whereCompanyId(Auth::user()->company->id)->latest()->first();

        $builder = FlowsData::whereFlowId($flow->id)
            ->with('auditor')
            ->with('auditee')
            ->with('investigator')
            ->select('flows_data.*');

        return datatables()->of($builder)
            ->addIndexColumn()
            ->editColumn('auditor', function (FlowsData $flowsData) {
                return $flowsData->auditor ? $flowsData->auditor->name : '';
            })
            ->editColumn('auditee', function (FlowsData $flowsData) {
                return $flowsData->auditee ? $flowsData->auditee->name : '';
            })
            ->editColumn('investigator', function (FlowsData $flowsData) {
                return $flowsData->investigator ? $flowsData->investigator->name : '';
            })
            ->editColumn('due_date', function (FlowsData $flowsData) {
                return $flowsData->due_date ? $flowsData->due_date->format('d.m.Y') : '';
            })
            ->filterColumn('due_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(due_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('effectiveness_review_date', function (FlowsData $flowsData) {
                return $flowsData->effectiveness_review_date ? $flowsData->effectiveness_review_date->format('d.m.Y') : '';
            })
            ->filterColumn('effectiveness_review_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(due_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('response_date', function (FlowsData $flowsData) {
                return $flowsData->response_date ? $flowsData->effectiveness_review_date->format('d.m.Y') : '';
            })
            ->filterColumn('response_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(response_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('extension_due_date', function (FlowsData $flowsData) {
                return $flowsData->extension_due_date ? $flowsData->extension_due_date->format('d.m.Y') : '';
            })
            ->filterColumn('extension_due_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(extension_due_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('closed_date', function (FlowsData $flowsData) {
                return $flowsData->closed_date ? $flowsData->closed_date->format('d.m.Y') : '';
            })
            ->filterColumn('closed_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(closed_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="#' . $row->rule_reference. '" data-toggle="tooltip"  data-rule_reference="' . $row->rule_reference . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
//                    $btn = $btn. '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $rule_reference
     * @return \Illuminate\Http\Response
     */
    public function edit(string $rule_reference)
    {
        // get latest company flow
        $flow = Auth::user()->company->flows->first();

        // get rule reference data
        $flowData = FlowsData::whereFlowId($flow->id)->whereRuleReference($rule_reference)->first();

        // return json response with data
        return response()->json([
            'success' => true,
            'resource' => $flowData,
            'auditor' => $flowData->auditor,
            'auditee' => $flowData->auditee,
            'investigator' => $flowData->investigator,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // validate request data
        $validator = Validator::make($request->all(), [
            'rule_section' => 'sometimes|required|numeric',
            'rule_group' => 'sometimes|required|string',
            'rule_reference' => 'sometimes|required|string',
            'rule_title' => 'sometimes|required|string',
            'rule_manual_reference' => 'sometimes|nullable|string',
            'rule_chapter' => 'sometimes|nullable|string',
            'company_manual' => 'sometimes|nullable|string',
            'company_chapter' => 'sometimes|nullable|string',
            'frequency' => 'sometimes|nullable|string',
            'month_quarter' => 'sometimes|nullable|string',
            'assigned_auditor' => 'sometimes|nullable|numeric', // assigned
            'assigned_auditee' => 'sometimes|nullable|numeric', // assigned
            'comments' => 'sometimes|nullable|string',
            'finding' => 'sometimes|nullable|string',
            'deviation_statement' => 'sometimes|nullable|string',
            'evidence_reference' => 'sometimes|nullable|string',
            'deviation_level' => 'sometimes|nullable|string',
            'safety_level_before_action' => 'sometimes|nullable|string',
            'due_date' => 'sometimes|nullable|date', // date
            'repetitive_finding_ref_number' => 'sometimes|nullable|string',
            'assigned_investigator' => 'sometimes|nullable|numeric', // assigned
            'corrections' => 'sometimes|nullable|string',
            'rootcause' => 'sometimes|nullable|string',
            'corrective_actions_plan' => 'sometimes|nullable|string',
            'preventive_actions' => 'sometimes|nullable|string',
            'action_implemented_evidence' => 'sometimes|nullable|string',
            'safety_level_after_action' => 'sometimes|nullable|string',
            'effectiveness_review_date' => 'sometimes|nullable|date', // date
            'response_date' => 'sometimes|nullable|date', // date
            'extension_due_date' => 'sometimes|nullable|date', // date
            'closed_date' => 'sometimes|nullable|date', // date

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // get latest company flow
        $flow = Auth::user()->company->flows->first();

        // update flow requirements data
        $flow->flowData()
            ->whereRuleReference($request->requirements_rule)
            ->update($request->except(
                'requirements_rule',
                '_token',
                '_method',
                'rule_section',
                'rule_group',
                'rule_reference',
                'rule_title',
                'rule_manual_reference',
                'rule_chapter'
            ));

        // get task data (FlowData)
        $task = FlowsData::whereFlowId($flow->id)->whereRuleReference($request->requirements_rule)->first();

        // send email notification
        app(NotificationService::class)->sendEditTaskMailNotification($flow, $task, Auth::user());

        // send teams notification
        // app(NotificationService::class)->sendTeamsNotification($flow, $task);

        // return json response with data
        return response()->json([
            'success' => true,
            'message' => "{$request->requirements_rule} was update successfully.",
            'resource' => $flow,
        ], 200);

    }

}
