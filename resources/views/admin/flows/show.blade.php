@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        {{--    <div class="container">--}}
        <div class="row justify-content-center">

            <!-- breadcrumb -->
            <div class="col-md-12 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.flows.index') }}">Flows</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> {{ $flow->title }}</li>
                    </ol>
                </nav>
            </div>
            <!-- /breadcrumb -->

            <!-- file info block -->
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <p><span class="font-weight-bold">Title: </span>{{ $flow->title }}</p>
                        <p><span class="font-weight-bold">Description:</span> {{ $flow->description }}</p>
                        <p><span class="font-weight-bold">Requirements:</span> version {{ $flow->requirement->id }}</p>

                    </div>
                </div>
            </div>
            <!-- /file info block -->

            <!-- flow requirements data block-->

            <div class="col-md-12 mb-3">
                <div class="card ">
                    <div class="card-body">
                        @if($flow->requirementsData->isNotEmpty())
                            <div class="table">
                                <table class="table table-sm table-bordered" style="display: block; overflow-x: auto; white-space: nowrap;">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="align-middle">Sec #</th>
                                        <th scope="col" class="align-middle">IR/AMC/GM</th>
                                        <th scope="col" class="align-middle">Rule Reference</th>
                                        <th scope="col" class="align-middle">Rule Title</th>
                                        <th scope="col" class="align-middle">AMC3 ORO.MLR.100 Manual Reference</th>
                                        <th scope="col" class="align-middle">AMC3 ORO.MLR.100 Chapter</th>
                                        <th scope="col" class="align-middle">Company Manual</th>
                                        <th scope="col" class="align-middle">Company Chapter</th>
                                        <th scope="col" class="align-middle">Frequency</th>
                                        <th scope="col" class="align-middle">Month Quarter</th>
                                        <th scope="col" class="align-middle">Assigned Auditor</th>
                                        <th scope="col" class="align-middle">Assigned Auditee</th>
                                        <th scope="col" class="align-middle">Comments / Questions</th>
                                        <th scope="col" class="align-middle">Finding / Observation</th>
                                        <th scope="col" class="align-middle">Deviation Statement</th>
                                        <th scope="col" class="align-middle">Manual / Evidence Reference</th>
                                        <th scope="col" class="align-middle">Deviation-Level</th>
                                        <th scope="col" class="align-middle">Safety level before action</th>
                                        <th scope="col" class="align-middle">Due-Date</th>
                                        <th scope="col" class="align-middle">Repetitive Finding ref Number</th>
                                        <th scope="col" class="align-middle">Assigned Investigator</th>
                                        <th scope="col" class="align-middle">Correction(s)</th>
                                        <th scope="col" class="align-middle">Rootcause:<br> 1. Why?<br> 2. Why?<br> 3. Why?<br> 4. Why?<br> 5. Why?</th>
                                        <th scope="col" class="align-middle">Corrective Action(s) Plan</th>
                                        <th scope="col" class="align-middle">Preventive Action(s)</th>
                                        <th scope="col" class="align-middle">Action implemented evidence</th>
                                        <th scope="col" class="align-middle">Safety level after action</th>
                                        <th scope="col" class="align-middle">Effectiveness Review date</th>
                                        <th scope="col" class="align-middle">Response date</th>
                                        <th scope="col" class="align-middle">Extension Due-Date</th>
                                        <th scope="col" class="align-middle">Closed date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($flow->requirementsData as $item)
                                        <tr>
                                            <td>{{ $item->rule_section }}</td>
                                            <td>{{ $item->rule_group }}</td>
                                            <td>{{ $item->rule_reference }}</td>
                                            <td>{{ $item->rule_title }}</td>
                                            <td>{{ $item->rule_manual_reference }}</td>
                                            <td>{{ $item->rule_chapter }}</td>
                                            <td>{{ $item->pivot->company_manual }}</td>
                                            <td>{{ $item->pivot->company_chapter }}</td>
                                            <td>{{ $item->pivot->frequency }}</td>
                                            <td>{{ $item->pivot->month_quarter }}</td>
                                            <td>{{ $item->pivot->assigned_auditor }}</td>
                                            <td>{{ $item->pivot->assigned_auditee }}</td>
                                            <td>{{ $item->pivot->comments }}</td>
                                            <td>{{ $item->pivot->finding }}</td>
                                            <td>{{ $item->pivot->deviation_statement }}</td>
                                            <td>{{ $item->pivot->evidence_reference }}</td>
                                            <td>{{ $item->pivot->deviation_level }}</td>
                                            <td>{{ $item->pivot->safety_level_before_action }}</td>
                                            <td>{{ $item->pivot->due_date }}</td>
                                            <td>{{ $item->pivot->repetitive_finding_ref_number }}</td>
                                            <td>{{ $item->pivot->assigned_investigator }}</td>
                                            <td>{{ $item->pivot->corrections }}</td>
                                            <td>{{ $item->pivot->rootcause }}</td>
                                            <td>{{ $item->pivot->corrective_actions_plan }}</td>
                                            <td>{{ $item->pivot->preventive_actions }}</td>
                                            <td>{{ $item->pivot->action_implemented_evidence }}</td>
                                            <td>{{ $item->pivot->safety_level_after_action }}</td>
                                            <td>{{ $item->pivot->effectiveness_review_date }}</td>
                                            <td>{{ $item->pivot->response_date }}</td>
                                            <td>{{ $item->pivot->extension_due_date }}</td>
                                            <td>{{ $item->pivot->closed_date }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <span>no data</span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- flow requirements data block-->

        </div>

    </div>

@endsection
