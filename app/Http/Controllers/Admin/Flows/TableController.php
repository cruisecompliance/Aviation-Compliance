<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Enums\RequrementStatus;
use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Models\Flow;
use App\Models\FlowsData;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                return !empty($flowsData->due_date) ? $flowsData->due_date->format('d.m.Y') : '';
            })
            ->filterColumn('due_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(due_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('effectiveness_review_date', function (FlowsData $flowsData) {
                return !empty($flowsData->effectiveness_review_date) ? $flowsData->effectiveness_review_date->format('d.m.Y') : '';
            })
            ->filterColumn('effectiveness_review_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(effectiveness_review_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('response_date', function (FlowsData $flowsData) {
                return !empty($flowsData->response_date) ? $flowsData->response_date->format('d.m.Y') : '';
            })
            ->filterColumn('response_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(response_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('extension_due_date', function (FlowsData $flowsData) {
                return !empty($flowsData->extension_due_date) ? $flowsData->extension_due_date->format('d.m.Y') : '';
            })
            ->filterColumn('extension_due_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(extension_due_date,'%d.%m.%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('closed_date', function (FlowsData $flowsData) {
                return !empty($flowsData->closed_date) ? $flowsData->closed_date->format('d.m.Y H:i:s') : '';
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
//                    $query->where('rule_reference', 'like', "%{$request->get('rule_reference')}%");

                }
                if (!empty($request->rule_section)) {
                    $query->where('rule_section', "$request->rule_section");
                }
                if (!empty($request->assignee)) {
                    // get user role name
                    $roleName = User::findOrFail($request->assignee)->roles()->first()->name;
                    // get role statuses
                    $roleStatuses = RequrementStatus::getRoleStatuses($roleName);
                    // get task for role
                    $query->whereIn('task_status', $roleStatuses);
                }
                if (!empty($request->status)) {
                    $query->where('task_status', "$request->status");
                }
            }, true)
            ->rawColumns(['action'])
            ->make(true);


    }


}
