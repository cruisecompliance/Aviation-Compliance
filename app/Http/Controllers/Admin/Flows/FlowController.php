<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Models\Requirement;
use App\Models\RequirementsData;
use Illuminate\Http\Request;
use App\Http\Requests\Flows\FlowRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class FlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     *
     */
    public function index()
    {
        // dataTable
        if (request()->ajax()) {

            $builder = Flow::query()
                ->with('company')
                ->with('requirement')
                ->select('flows.*');

            return datatables()->of($builder)
                ->addIndexColumn()
                ->editColumn('company', function (Flow $flow) {
                    return $flow->company ? $flow->company->name : '';
                })
                ->editColumn('requirement', function (Flow $flow) {
                    return $flow->requirement ? $flow->requirement->title : '';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('admin.flows.kanban.index', $row->id) . '" class="btn btn-success btn-sm mr-1">Kanban</a>';
                    $btn = $btn . ' <a href="' . route('admin.flows.table.index', $row->id) . '" class="btn btn-success btn-sm mr-1">Table</a>';
                    $btn = $btn . '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // get companies list for select input
        $companies = Company::active()->latest()->get(['id', 'name']);

        // get requirement list for select input
        $requirements = Requirement::latest()->get(['id', 'title']);

        // return view with data
        return view('admin.flows.index', [
            'companies' => $companies,
            'requirements' => $requirements,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Flows\FlowRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|unique:flows',
            'description' => 'nullable|string|min:4',
            'company' => 'required|numeric',
            'requirements' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        return DB::transaction(function () use ($request) {

            // create new flow
            $flow = Flow::create([
                'title' => $request->title,
                'description' => $request->description,
                'company_id' => $request->company,
                'requirement_id' => $request->requirements,
            ]);

            // get RequirementsData
            $requirementsData = RequirementsData::query()->where('version_id', $request->requirements)->get();

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

            return response()->json([
                'success' => true,
                'message' => "Flow {$flow->title} was added successfully.",
                'resource' => $flow,
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Flow $flow)
    {
        // return JSON data
        return response()->json([
            'success' => true,
            'flow' => $flow,
            'company' => $flow->company,
            'requirement' => $flow->requirement,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flow $flow)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4|unique:flows,title,' . $flow->id,
            'description' => 'nullable|string|min:4',
            'company' => 'required|numeric',
            'requirements' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $flow->update([
            'title' => $request->title,
            'description' => $request->description,
            'company_id' => $request->company,
            'requirement_id' => $request->requirements,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Flow {$flow->title} was update successfully.",
            'resource' => $flow,
        ]);
    }

}
