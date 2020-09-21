<?php

namespace App\Http\Controllers\User\Flows;

use App\Enums\RequrementStatus;
use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Models\Flow;
use App\Models\FlowsData;
use App\Services\Flows\NotificationService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
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

        if (!empty($flow)){

            // check assigned users for flowData (Auditee, Auditor, Investigator)
            if(Auth::user()->hasRole([RoleName::AUDITEE, RoleName::AUDITOR, RoleName::INVESTIGATOR])){
                if (empty(FlowsData::checkAssignedUser(Auth::user()->id, $flow->id))) {
                    return abort(403, 'User not assigned ');
                }
            }

            // get data for edit task (form modal - select input) ToDo - ajax load in _form.blade.php
            $auditors = User::auditors()->active()->whereCompanyId($flow->company->id)->get();
            $auditees = User::auditees()->active()->whereCompanyId($flow->company->id)->get();
            $investigators = User::investigators()->active()->whereCompanyId($flow->company->id)->get();

        }

        // return view with data
        return view('user.flows.table', [
            'flow' => $flow,
            'auditors' => ($auditors) ?? NULL,
            'auditees' => ($auditees) ?? NULL,
            'investigators' => $investigators ?? NULL,
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
            ->filter(function ($query) use ($request, $flow) {

                if (!empty($request->rule_reference)) {
                    $query->where('rule_reference', "$request->rule_reference");
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
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}