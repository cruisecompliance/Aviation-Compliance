@extends('layouts.app')

@section('content')

    @if (session('success'))
        <!-- alert -->
        <div class="bg-light">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /alert -->
    @endif

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
            <style>
                #datatable_wrapper {
                    display: block;
                    overflow-x: auto;
                    white-space: nowrap;
                }
            </style>

            <div class="col-md-12 mb-3">
                <div class="card ">
                    <div class="card-body">
                        <div class="table">
                            <table class="table table-sm table-bordered" id="datatable" style="display: block; overflow-x: auto; white-space: nowrap;">
                                <thead>
                                <tr>
                                    <th scope="col" class="align-middle">action</th>
                                    <th scope="col" class="align-middle">Sec #</th>
                                    <th scope="col" class="align-middle">European rule <br>IR/AMC/GM</th>
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editModal" action="{{ route('admin.flows.update', $flow->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Sec #</label>
                            <input type="text" name="rule_section" value="" class="form-control" placeholder="" disabled>
                        </div>

                        <h2>European Rule </h2>
                        <div class="form-group">
                            <label>IR/AMC/GM</label>
                            <input type="text" name="rule_group" value="" class="form-control" placeholder="" disabled>
                        </div>
                        <div class="form-group">
                            <label>Rule Reference</label>
                            <input type="text" name="rule_reference" value="" class="form-control" placeholder="" disabled>
                        </div>
                        <div class="form-group">
                            <label>Rule Title</label>
                            <input type="text" name="rule_title" value="" class="form-control" placeholder="" disabled>
                        </div>
                        <hr>

                        <div class="form-group">
                            <label>AMC3 ORO.MLR.100 Manual Reference</label>
                            <input type="text" name="rule_manual_reference" value="" class="form-control" placeholder="" disabled>
                        </div>
                        <div class="form-group">
                            <label>AMC3 ORO.MLR.100 Chapter</label>
                            <input type="text" name="rule_chapter" value="" class="form-control" placeholder="" disabled>
                        </div>
                        <hr>

                        <h2>Company Structure</h2>
                        <div class="form-group">
                            <label>Company Manual</label>
                            <input type="text" name="company_manual" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Company Chapter</label>
                            <input type="text" name="company_chapter" value="" class="form-control" placeholder="">
                        </div>
                        <hr>

                        <h2>Audit Structure</h2>
                        <div class="form-group">
                            <label>Frequency</label>
                            <input type="text" name="frequency" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Month / Quarter</label>
                            <input type="text" name="month_quarter" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Assigned Auditor</label>
                            <input type="text" name="assigned_auditor" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Assigned Auditee</label>
                            <input type="text" name="assigned_auditee" value="" class="form-control" placeholder="">
                        </div>
                        <hr>

                        <h2>Auditors Input</h2>
                        <div class="form-group">
                            <label>Comments / Questions</label>
                            <input type="text" name="comments" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Finding / Observation</label>
                            <input type="text" name="finding" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Deviation Statement</label>
                            <input type="text" name="deviation_statement" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Manual / Evidence Reference</label>
                            <input type="text" name="evidence_reference" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Deviation-Level</label>
                            <input type="text" name="deviation_level" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Safety level before action</label>
                            <input type="text" name="safety_level_before_action" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Due-Date</label>
                            <input type="date" name="due_date" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Repetitive Finding ref Number</label>
                            <input type="text" name="repetitive_finding_ref_number" value="" class="form-control" placeholder="">
                        </div>
                        <hr>

                        <h2>Auditee Input (NP)</h2>
                        <div class="form-group">
                            <label>Assigned Investigator</label>
                            <input type="text" name="assigned_investigator" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Correction(s)</label>
                            <input type="text" name="corrections" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Rootcause:
                                1. Why?
                                2. Why?
                                3. Why?
                                4. Why?
                            </label>
                            <textarea name="rootcause" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Corrective Action(s) Plan</label>
                            <input type="text" name="corrective_actions_plan" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Preventive Action(s)</label>
                            <input type="text" name="preventive_actions" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Action implemented evidence</label>
                            <input type="text" name="action_implemented_evidence" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Safety level after action</label>
                            <input type="text" name="safety_level_after_action" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Effectiveness Review date</label>
                            <input type="date" name="effectiveness_review_date" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Response date</label>
                            <input type="date" name="response_date" value="" class="form-control" placeholder="">
                        </div>
                        <hr>

                        <div class="form-group">
                            <label>Extension Due-Date</label>
                            <input type="date" name="extension_due_date" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Closed date</label>
                            <input type="date" name="closed_date" value="" class="form-control" placeholder="">
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="flow_id" value="" class="form-control">
                            <input type="hidden" name="rule_reference" value="" class="form-control">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Send</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal -->

    @push('scripts')
        <script type="text/javascript">
            $(function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // dataTable list
                $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.flows.show', $flow->id) }}",
                        type: 'GET',
                    },
                    columns: [
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                        // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'rule_section', name: 'rule_section'},
                        {data: 'rule_group', name: 'rule_group'},
                        {data: 'rule_reference', name: 'rule_reference'},
                        {data: 'rule_title', name: 'rule_title'},
                        {data: 'rule_manual_reference', name: 'rule_manual_reference'},
                        {data: 'rule_chapter', name: 'rule_chapter'},
                        {data: 'company_manual', name: 'company_manual'},
                        {data: 'company_chapter', name: 'company_chapter'},
                        {data: 'frequency', name: 'frequency'},
                        {data: 'month_quarter', name: 'month_quarter'},
                        {data: 'assigned_auditor', name: 'assigned_auditor'},
                        {data: 'assigned_auditee', name: 'assigned_auditee'},
                        {data: 'comments', name: 'comments'},
                        {data: 'finding', name: 'finding'},
                        {data: 'deviation_statement', name: 'deviation_statement'},
                        {data: 'evidence_reference', name: 'evidence_reference'},
                        {data: 'deviation_level', name: 'deviation_level'},
                        {data: 'safety_level_before_action', name: 'safety_level_before_action'},
                        {data: 'due_date', name: 'due_date'},
                        {data: 'repetitive_finding_ref_number', name: 'repetitive_finding_ref_number'},
                        {data: 'assigned_investigator', name: 'assigned_investigator'},
                        {data: 'corrections', name: 'corrections'},
                        {data: 'rootcause', name: 'rootcause'},
                        {data: 'corrective_actions_plan', name: 'corrective_actions_plan'},
                        {data: 'preventive_actions', name: 'preventive_actions'},
                        {data: 'action_implemented_evidence', name: 'action_implemented_evidence'},
                        {data: 'safety_level_after_action', name: 'safety_level_after_action'},
                        {data: 'effectiveness_review_date', name: 'effectiveness_review_date'},
                        {data: 'response_date', name: 'response_date'},
                        {data: 'extension_due_date', name: 'extension_due_date'},
                        {data: 'closed_date', name: 'closed_date'},
                    ]
                });

                // edit item
                $('body').on('click', '.editModal', function () {
                    var rule_reference = $(this).data('rule_reference');
                    var flow_id = $(this).data('flow_id');

                    var url = "{{ route('admin.flows.show', $flow->id) }}" + '/requirement/' + rule_reference + '/edit';

                    $.ajax({
                        type: 'get',
                        url: url,
                        data: {'rule_reference': rule_reference},
                        success: function (data) {
                            console.log(data);
                            $('.modal-title').text('Edit - ' + data.requirement.rule_reference);

                            $('#editModal').find('[name=rule_section]').val(data.requirement.rule_section);

                            // European Rule
                            $('#editModal').find('[name=rule_group]').val(data.requirement.rule_group);
                            $('#editModal').find('[name=rule_reference]').val(data.requirement.rule_reference);
                            $('#editModal').find('[name=rule_title]').val(data.requirement.rule_title);

                            $('#editModal').find('[name=rule_manual_reference]').val(data.requirement.rule_manual_reference);
                            $('#editModal').find('[name=rule_chapter]').val(data.requirement.rule_chapter);

                            // Company Structure
                            $('#editModal').find('[name=company_manual]').val(data.requirement.company_manual);
                            $('#editModal').find('[name=company_chapter]').val(data.requirement.company_chapter);

                            // Audit Structure
                            $('#editModal').find('[name=frequency]').val(data.requirement.frequency);
                            $('#editModal').find('[name=month_quarter]').val(data.requirement.month_quarter);
                            $('#editModal').find('[name=assigned_auditor]').val(data.requirement.assigned_auditor);
                            $('#editModal').find('[name=assigned_auditee]').val(data.requirement.assigned_auditee);

                            // Auditors Input
                            $('#editModal').find('[name=comments]').val(data.requirement.comments);
                            $('#editModal').find('[name=finding]').val(data.requirement.finding);
                            $('#editModal').find('[name=deviation_statement]').val(data.requirement.deviation_statement);
                            $('#editModal').find('[name=evidence_reference]').val(data.requirement.evidence_reference);
                            $('#editModal').find('[name=deviation_level]').val(data.requirement.deviation_level);
                            $('#editModal').find('[name=safety_level_before_action]').val(data.requirement.safety_level_before_action);
                            $('#editModal').find('[name=due_date]').val(data.requirement.due_date);
                            $('#editModal').find('[name=repetitive_finding_ref_number]').val(data.requirement.repetitive_finding_ref_number);

                            // Auditee Input (NP)
                            $('#editModal').find('[name=assigned_investigator]').val(data.requirement.assigned_investigator);
                            $('#editModal').find('[name=corrections]').val(data.requirement.corrections);
                            $('#editModal').find('[name=rootcause]').val(data.requirement.rootcause);
                            $('#editModal').find('[name=corrective_actions_plan]').val(data.requirement.corrective_actions_plan);
                            $('#editModal').find('[name=preventive_actions]').val(data.requirement.preventive_actions);
                            $('#editModal').find('[name=action_implemented_evidence]').val(data.requirement.preventive_actions);
                            $('#editModal').find('[name=safety_level_after_action]').val(data.requirement.safety_level_after_action);
                            $('#editModal').find('[name=effectiveness_review_date]').val(data.requirement.effectiveness_review_date);
                            $('#editModal').find('[name=response_date]').val(data.requirement.response_date);

                            // Dates
                            $('#editModal').find('[name=extension_due_date]').val(data.requirement.extension_due_date);
                            $('#editModal').find('[name=closed_date]').val(data.requirement.closed_date);

                            // Hidden fields
                            $('#editModal').find('[name=flow_id]').val(data.requirement.flow_id);
                            $('#editModal').find('[name=rule_reference]').val(data.requirement.rule_reference);

                            // $('.classFormUpdate').attr('action', action);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });

                });

            });

        </script>

        <script type="text/javascript">

            // Edit form
            $(document).ready(function () {

                $('.editModal').click(function () {
                    console.log('123');

                    var rule_reference = $(this).data('rule_reference');
                    var flow_id = $(this).data('flow_id');
                    console.log(rule_reference);

                    var url = "{{ route('admin.flows.show', $flow->id) }}" + '/requirement/' + rule_reference + '/edit';

                    // console.log(id,flow_id, url);

                    $.ajax({
                        type: 'get',
                        url: url,
                        data: {'rule_reference': rule_reference},
                        success: function (data) {
                            console.log(data);
                            $('.modal-title').text('Edit - ' + data.requirement.rule_reference);

                            $('#editModal').find('[name=rule_section]').val(data.requirement.rule_section);

                            // European Rule
                            $('#editModal').find('[name=rule_group]').val(data.requirement.rule_group);
                            $('#editModal').find('[name=rule_reference]').val(data.requirement.rule_reference);
                            $('#editModal').find('[name=rule_title]').val(data.requirement.rule_title);

                            $('#editModal').find('[name=rule_manual_reference]').val(data.requirement.rule_manual_reference);
                            $('#editModal').find('[name=rule_chapter]').val(data.requirement.rule_chapter);

                            // Company Structure
                            $('#editModal').find('[name=company_manual]').val(data.requirement.company_manual);
                            $('#editModal').find('[name=company_chapter]').val(data.requirement.company_chapter);

                            // Audit Structure
                            $('#editModal').find('[name=frequency]').val(data.requirement.frequency);
                            $('#editModal').find('[name=month_quarter]').val(data.requirement.month_quarter);
                            $('#editModal').find('[name=assigned_auditor]').val(data.requirement.assigned_auditor);
                            $('#editModal').find('[name=assigned_auditee]').val(data.requirement.assigned_auditee);

                            // Auditors Input
                            $('#editModal').find('[name=comments]').val(data.requirement.comments);
                            $('#editModal').find('[name=finding]').val(data.requirement.finding);
                            $('#editModal').find('[name=deviation_statement]').val(data.requirement.deviation_statement);
                            $('#editModal').find('[name=evidence_reference]').val(data.requirement.evidence_reference);
                            $('#editModal').find('[name=deviation_level]').val(data.requirement.deviation_level);
                            $('#editModal').find('[name=safety_level_before_action]').val(data.requirement.safety_level_before_action);
                            $('#editModal').find('[name=due_date]').val(data.requirement.due_date);
                            $('#editModal').find('[name=repetitive_finding_ref_number]').val(data.requirement.repetitive_finding_ref_number);

                            // Auditee Input (NP)
                            $('#editModal').find('[name=assigned_investigator]').val(data.requirement.assigned_investigator);
                            $('#editModal').find('[name=corrections]').val(data.requirement.corrections);
                            $('#editModal').find('[name=rootcause]').val(data.requirement.rootcause);
                            $('#editModal').find('[name=corrective_actions_plan]').val(data.requirement.corrective_actions_plan);
                            $('#editModal').find('[name=preventive_actions]').val(data.requirement.preventive_actions);
                            $('#editModal').find('[name=action_implemented_evidence]').val(data.requirement.preventive_actions);
                            $('#editModal').find('[name=safety_level_after_action]').val(data.requirement.safety_level_after_action);
                            $('#editModal').find('[name=effectiveness_review_date]').val(data.requirement.effectiveness_review_date);
                            $('#editModal').find('[name=response_date]').val(data.requirement.response_date);

                            // Dates
                            $('#editModal').find('[name=extension_due_date]').val(data.requirement.extension_due_date);
                            $('#editModal').find('[name=closed_date]').val(data.requirement.closed_date);

                            // Hidden fields
                            $('#editModal').find('[name=flow_id]').val(data.requirement.flow_id);
                            $('#editModal').find('[name=requirement_data_id]').val(data.requirement.requirement_data_id);


                            // $('.classFormUpdate').attr('action', action);
                        }
                    });
                });

            });
        </script>
    @endpush

@endsection
