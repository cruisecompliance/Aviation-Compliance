<?php

namespace App\Http\Controllers\Admin\Flows;

use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Models\Flow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class FilterController extends Controller
{
    /**
     * Store filter
     *
     * @param Flow $flow
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Flow $flow, Request $request)
    {

        $filter = Filter::create([
            'name' => $request->name,
            'params' => "filter_name=$request->name&rule_reference=$request->rule_reference&rule_section=$request->rule_section&assignee=$request->assignee",
            'user_id' => Auth::user()->id,
        ]);

        return redirect("$request->route?$filter->params");
    }

}
