<?php

namespace App\Http\Controllers\Components\Flows;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Models\Flow;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FilterController extends Controller
{

    public function showFilterForm(Flow $flow)
    {
        try {
            // get filters list of user
            $filters = Filter::whereUserId(Auth::user()->id)->get();

            // get flow (if flow !empty - filter in admin page (SME) else filter in company page)
            if (empty($flow)) {
                $flow = Flow::whereCompanyId(Auth::user()->company->id)->latest()->first();
            }

            // get rule references of flow
            $tasks = $flow->flowData->pluck('rule_reference');

            // get rule sections of flow
            $sections = $flow->flowData->pluck('rule_section')->unique();

            // get company users
            $users = User::whereCompanyId(Auth::user()->company_id)
                ->role([
                    RoleName::ACCOUNTABLE_MANAGER,
                    RoleName::COMPLIANCE_MONITORING_MANAGER,
                    RoleName::AUDITOR,
                    RoleName::AUDITEE,
                    RoleName::INVESTIGATOR,
                ])
                ->get();

            // return json data
            return response()->json([
                'success' => true,
                'filters' => $filters,
                'tasks' => $tasks,
                'sections' => $sections,
                'users' => $users,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store filter
     *
     * @param Flow $flow
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Flow $flow, Request $request)
    {

        // validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:26',
            'rule_reference' => 'sometimes|nullable|string',
            'rule_section' => 'sometimes|nullable|numeric',
            'assignee' => 'sometimes|nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        try {

            $filter = Filter::create([
                'name' => $request->name,
                'params' => "filter_name=$request->name&rule_reference=$request->rule_reference&rule_section=$request->rule_section&assignee=$request->assignee",
                'user_id' => Auth::user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$filter->name} was create successfully.",
                'filter' => $filter,
                'redirect' => "$request->route?$filter->params",
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }

        // redirect back with filter query
        // return redirect("$request->route?$filter->params");
    }

}
