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

        // return view with data
        return view('user.flows.table', [
            'flow' => $flow,
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
            ->with('owner')
            ->select('flows_data.*');

        return datatables()->of($builder)
            ->addIndexColumn()
            ->editColumn('task_owner', function (FlowsData $flowsData) {
                return $flowsData->owner ? $flowsData->owner->name : '';
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
                $btn = '<a href="#' . $row->rule_reference. '" data-toggle="tooltip"  data-rule_reference="' . $row->rule_reference . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editItem">Edit</a>';
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
                    $query->where('task_owner', $request->assignee);
                }
                if (!empty($request->status)) {
                    $query->where('task_status', "$request->status");
                }
            }, true)
            ->rawColumns(['action'])
            ->make(true);
    }

}
