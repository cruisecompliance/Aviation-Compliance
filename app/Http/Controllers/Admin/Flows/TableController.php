<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flow;
use App\Models\FlowsData;

class TableController extends Controller
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
        // return requirements table view with data
        return view('admin.flows.table', [
            'flow' => $flow->load('company', 'requirement'),
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
        $builder = FlowsData::whereFlowId($flow->id)
            ->with('owner')
            ->select('flows_data.*');

        return datatables()->of($builder)
            ->addIndexColumn()
            ->editColumn('task_owner', function (FlowsData $flowsData) {
                return $flowsData->owner ? $flowsData->owner->name : '';
            })
            ->editColumn('due_date', function (FlowsData $flowsData) {
                return !empty($flowsData->due_date) ? $flowsData->due_date : '';
            })
            ->filterColumn('due_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(due_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('effectiveness_review_date', function (FlowsData $flowsData) {
                return !empty($flowsData->effectiveness_review_date) ? $flowsData->effectiveness_review_date : '';
            })
            ->filterColumn('effectiveness_review_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(effectiveness_review_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('response_date', function (FlowsData $flowsData) {
                return !empty($flowsData->response_date) ? $flowsData->response_date : '';
            })
            ->filterColumn('response_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(response_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('extension_due_date', function (FlowsData $flowsData) {
                return !empty($flowsData->extension_due_date) ? $flowsData->extension_due_date : '';
            })
            ->filterColumn('extension_due_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(extension_due_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('closed_date', function (FlowsData $flowsData) {
                return !empty($flowsData->closed_date) ? $flowsData->closed_date : '';
            })
            ->filterColumn('closed_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(closed_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="#' . $row->rule_reference . '" data-toggle="tooltip" data-rule_reference="' . $row->rule_reference . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
//                    $btn = $btn. '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                return $btn;
            })
            ->filter(function ($query) use ($request, $flow) {
                if (!empty($request->rule_reference)) {
//                    $query->where('rule_reference', "$request->rule_reference");
                    $query->where('rule_reference', 'like', "%{$request->rule_reference}%");
                }
                if (!empty($request->rule_section)) {
                    $query->where('rule_section', "$request->rule_section");
                }
                if (!empty($request->assignee)) {
                    // get task for assignee user
                    $query->where('task_owner', $request->assignee);
                }
                if (!empty($request->status)) {
                    $query->where('task_status', "$request->status");
                }
                if (!empty($request->finding)) {
                    $query->where('finding', $request->finding);
                }
            }, true)
            ->rawColumns(['action'])
            ->make(true);


    }


}
