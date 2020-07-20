<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\Requirement;
use App\Models\RequirementsData;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\Flows\FlowRequest;


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
     * @param \App\Http\Requests\Flows\ $flowRequest
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

            // attach requirement data to flow
            $data = RequirementsData::where('version_id', $request->requirement_id)->get()->pluck('id');

            $flow->requirementsData()->attach($data);

            // redirect to the admin.flows.show route with success status
            return redirect()->route('admin.flows.show', $flow)->with('status', 'Flow successful created');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Flow $flow
     * @return \Illuminate\Http\Response
     */
    public function show(Flow $flow)
    {
        // load requirement info
        $flow->load('requirement');

        // load requirement data
        $flow->load('requirementsData');

        // return view with data
        return view('admin.flows.show', [
            'flow' => $flow,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Flow $flow
     * @return \Illuminate\Http\Response
     */
    public function edit(Flow $flow)
    {
        dd(__METHOD__);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Flow $flow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flow $flow)
    {
        dd(__METHOD__);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Flow $flow
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flow $flow)
    {
        dd(__METHOD__);
    }
}
