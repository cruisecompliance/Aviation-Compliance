<?php

namespace App\Http\Controllers\Admin\Flows;

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
        // get data for edit task (form modal) ToDo
        $auditors = User::auditors()->active()->whereCompanyId($flow->company->id)->get();
        $auditees = User::auditees()->active()->whereCompanyId($flow->company->id)->get();
        $investigators = User::investigators()->active()->whereCompanyId($flow->company->id)->get();

        // get filter data ToDo
        $filters = Filter::whereUserId(Auth::user()->id)->get();
        $users = array_merge($auditors->toArray(), $auditees->toArray(), $investigators->toArray());

        // return requirements table view with data
        return view('admin.flows.table', [
            'flow' => $flow->load('company', 'requirement'),
            'auditors' => $auditors,
            'auditees' => $auditees,
            'investigators' => $investigators,

            'filters' => $filters,
            'flowData' => $flow->flowData,
            'users' => $users,
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
                $btn = '<a href="#' . $row->rule_reference . '" data-toggle="tooltip" data-rule_reference="' . $row->rule_reference . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
//                    $btn = $btn. '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                return $btn;
            })
            ->filter(function ($query) use ($request, $flow) {

                if (!empty($request->rule_reference)) {
                    $query->where('rule_reference', "$request->rule_reference");
                }
                if (!empty($request->rule_section)) {
                    $query->where('rule_section', "$request->rule_section");
                }
                if (!empty($request->assignee)) {
                    $query->where('auditor_id', "$request->assignee");
                }
                if (!empty($request->assignee)) {

                    $query->where(function ($assignee) use ($request) {
                        $assignee->orWhere('auditor_id', "$request->assignee")
                            ->orWhere('auditee_id', "$request->assignee")
                            ->orWhere('investigator_id', "$request->assignee");
                    });
                }
            })
            ->rawColumns(['action'])
            ->make(true);


    }


}
