<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Models\Requirement;
use App\Models\RequirementsData;
use Illuminate\Http\Request;
use App\Http\Requests\Flows\FlowRequest;
use Illuminate\Support\Facades\DB;


class FlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flows = Flow::with('requirement')->get();

        return view('admin.flows.index', [
            'flows' => $flows,
        ]);
    }

    /**
     * Show the form for creating a new flow.
     *
     * @param \App\Models\Requirement $requirement
     * @return \Illuminate\Http\Response
     */
    public function create(Requirement $requirement)
    {
        return view('admin.flows.create', [
            'requirement' => $requirement,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Flows\FlowRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(FlowRequest $request)
    {
        return DB::transaction(function () use ($request) {

            // create new flow
            $flow = Flow::create([
                'title' => $request->title,
                'description' => $request->description,
                'requirement_id' => $request->requirement_id,
            ]);

            // get RequirementsData
            $requirementsData = RequirementsData::query()->where('version_id', $request->requirement_id)->get();

            // save FlowData
            foreach ($requirementsData as $requirement) {
                $flow->flowData()->create([
                    'rule_section' => $requirement->rule_section,
                    'rule_group' => $requirement->rule_group,
                    'rule_reference' => $requirement->rule_reference,
                    'rule_title' => $requirement->rule_title,
                    'rule_manual_reference' => $requirement->rule_manual_reference,
                    'rule_chapter' => $requirement->rule_chapter,
                ]);
            }

            // redirect to the admin.flows.show route with success status
            return redirect()->route('admin.flows.show', $flow)->with('status', 'Flow successful created');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Flow $flow
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show(Flow $flow, Request $request)
    {
        if (request()->ajax()) {
            $builder = FlowsData::whereFlowId($flow->id);

            return datatables()->of($builder)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="editModal btn btn-primary btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg" data-flow_id="' . $row->id . '" data-rule_reference="' . $row->rule_reference . '">Edit</a>';
//                    $btn = $btn. '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.flows.show', [
            'flow' => $flow,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $flow_id
     * @param string $rule_reference
     * @return \Illuminate\Http\Response
     */
    public function ajaxGetRuleReference(int $flow_id, string $rule_reference)
    {
        // get rule reference data
        $data = FlowsData::whereFlowId($flow_id)->whereRuleReference($rule_reference)->first();

        return response()->json([
            'requirement' => $data
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

        $flow->flowData()->whereRuleReference($request->rule_reference)->update($request->except('_token', 'rule_section', 'rule_group', 'rule_reference', 'rule_title', 'rule_manual_reference', 'rule_chapter'));

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


//        return response()->json(['success' => 'Update successful']);
        return redirect()->back()->with('success', 'Update successful');
    }

}
