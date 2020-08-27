@extends('layouts.admin')

@section('content')

    <div class="content">

        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.flows.index') }}">Flows</a></li>
                                <li class="breadcrumb-item active">Requirements</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- page content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-8">
                                    <h4 class="page-title mb-2">{{ $flow->title }}</h4>
                                    <div class="text-muted font-13">Company: <span class="font-weight-bold">{{ $flow->company->name }}</span></div>
                                    <div class="text-muted font-13">Requirements: <span class="font-weight-bold">version {{ $flow->requirement->id }}</span></div>
                                    <div class="text-muted font-13 mt-1">{{ $flow->description }}</div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <table id="basic-datatable" class="table nowrap w-100">
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
                                            <th scope="col" class="align-middle">Rootcause</th>
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
                                    </table>
                                </div>
                            </div>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div><!-- end row-->

            <!-- /page content -->

        </div>

    </div>

    <!-- modal -->
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>
                <div class="modal-body" id="">
                    <form id="ItemForm" action="" name="ItemForm" class="form-horizontal" method="POST">

                        <input type="hidden" name="_method" id="_method" value="">

                        <div class="form-group">
                            <label for="rule_section" class="control-label">Sec #</label>
                            <input type="text" class="form-control" id="rule_section" name="rule_section" value="" readonly>
                        </div>

                        <h2>European Rule </h2>
                        <div class="form-group">
                            <label for="rule_group" class="control-label">IR/AMC/GM</label>
                            <input type="text" class="form-control" id="rule_group" name="rule_group" value="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="rule_reference" class="control-label">Rule Reference</label>
                            <input type="text" class="form-control" id="rule_reference" name="rule_reference" value="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="rule_title" class="control-label">Rule Title</label>
                            <input type="text" class="form-control" id="rule_title" name="rule_title" value="" readonly>
                        </div>

                        <div class="form-group">
                            <label for="rule_manual_reference" class="control-label">AMC3 ORO.MLR.100 Manual Reference</label>
                            <input type="text" class="form-control" id="rule_manual_reference" name="rule_manual_reference" value="" readonly>
                        </div>

                        <div class="form-group">
                            <label for="rule_chapter" class="control-label">AMC3 ORO.MLR.100 Chapter</label>
                            <input type="text" class="form-control" id="rule_chapter" name="rule_chapter" value="" readonly>
                        </div>

                        <h2>Company Structure</h2>
                        <div class="form-group">
                            <label for="company_manual" class="control-label">Company Manual</label>
                            <input type="text" class="form-control" id="company_manual" name="company_manual" value="">
                        </div>
                        <div class="form-group">
                            <label for="company_chapter" class="control-label">Company Chapter</label>
                            <input type="text" class="form-control" id="company_chapter" name="company_chapter" value="">
                        </div>

                        <h2>Audit Structure</h2>
                        <div class="form-group">
                            <label for="frequency" class="control-label">Frequency</label>
                            <input type="text" class="form-control" id="frequency" name="frequency" value="">
                        </div>
                        <div class="form-group">
                            <label for="month_quarter" class="control-label">Month / Quarter</label>
                            <input type="text" class="form-control" id="month_quarter" name="month_quarter" value="">
                        </div>
                        <div class="form-group">
                            <label for="assigned_auditor" class="control-label">Assigned Auditor</label>
                            <select name="auditor_id" id="assigned_auditor" class="form-control">
                                <option value="">...</option>
                                @if($auditors->isNotEmpty())
                                    @foreach($auditors as $auditor)
                                        <option value="{{ $auditor->id }}">{{ $auditor->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="assigned_auditee" class="control-label">Assigned Auditee</label>
                            <select name="auditee_id" id="assigned_auditee" class="form-control">
                                <option value="">...</option>
                                @if($auditees->isNotEmpty())
                                    @foreach($auditees as $auditee)
                                        <option value="{{ $auditee->id }}">{{ $auditee->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <h2>Auditors Input</h2>
                        <div class="form-group">
                            <label for="comments" class="control-label">Comments / Questions</label>
                            <input type="text" class="form-control" id="comments" name="comments" value="">
                        </div>
                        <div class="form-group">
                            <label for="finding" class="control-label">Finding / Observation</label>
                            <input type="text" class="form-control" id="finding" name="finding" value="">
                        </div>
                        <div class="form-group">
                            <label for="deviation_statement" class="control-label">Deviation Statement</label>
                            <input type="text" class="form-control" id="deviation_statement" name="deviation_statement" value="">
                        </div>
                        <div class="form-group">
                            <label for="evidence_reference" class="control-label">Manual / Evidence Reference</label>
                            <input type="text" class="form-control" id="evidence_reference" name="evidence_reference" value="">
                        </div>
                        <div class="form-group">
                            <label for="deviation_level" class="control-label">Deviation-Level</label>
                            <input type="text" class="form-control" id="deviation_level" name="deviation_level" value="">
                        </div>
                        <div class="form-group">
                            <label for="safety_level_before_action" class="control-label">Safety level before action</label>
                            <input type="text" class="form-control" id="safety_level_before_action" name="safety_level_before_action" value="">
                        </div>
                        <div class="form-group">
                            <label for="due_date" class="control-label">Due-Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" value="">
                        </div>
                        <div class="form-group">
                            <label for="repetitive_finding_ref_number" class="control-label">Repetitive Finding ref Number</label>
                            <input type="text" class="form-control" id="repetitive_finding_ref_number" name="repetitive_finding_ref_number" value="">
                        </div>

                        <h2>Auditee Input (NP)</h2>
                        <div class="form-group">
                            <label for="assigned_investigator" class="control-label">Assigned Investigator</label>
                            <select name="investigator_id" id="assigned_investigator" class="form-control">
                                <option value="">...</option>
                                @if($investigators->isNotEmpty())
                                    @foreach($investigators as $investigator)
                                        <option value="{{ $investigator->id }}">{{ $investigator->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="corrections" class="control-label">Correction(s)</label>
                            <input type="text" class="form-control" id="corrections" name="corrections" value="">
                        </div>
                        <div class="form-group">
                            <label for="rootcause" class="control-label">Rootcause:
                                1. Why?
                                2. Why?
                                3. Why?
                                4. Why?
                                5. Why?
                            </label>
                            <textarea class="form-control" id="rootcause" name="rootcause" rows="3" placeholder=""></textarea>
                        </div>
                        <div class="form-group">
                            <label for="corrective_actions_plan" class="control-label">Corrective Action(s) Plan</label>
                            <input type="text" class="form-control" id="corrective_actions_plan" name="corrective_actions_plan" value="">
                        </div>
                        <div class="form-group">
                            <label for="preventive_actions" class="control-label">Preventive Action(s)</label>
                            <input type="text" class="form-control" id="preventive_actions" name="preventive_actions" value="">
                        </div>
                        <div class="form-group">
                            <label for="action_implemented_evidence" class="control-label">Action implemented evidence</label>
                            <input type="text" class="form-control" id="action_implemented_evidence" name="action_implemented_evidence" value="">
                        </div>
                        <div class="form-group">
                            <label for="safety_level_after_action" class="control-label">Safety level after action</label>
                            <input type="text" class="form-control" id="safety_level_after_action" name="safety_level_after_action" value="">
                        </div>
                        <div class="form-group">
                            <label for="effectiveness_review_date" class="control-label">Effectiveness Review date</label>
                            <input type="date" class="form-control" id="effectiveness_review_date" name="effectiveness_review_date" value="">
                        </div>
                        <div class="form-group">
                            <label for="response_date" class="control-label">Response date</label>
                            <input type="date" class="form-control" id="response_date" name="response_date" value="">
                        </div>

                        <div class="form-group">
                            <label for="extension_due_date" class="control-label">Extension Due-Date</label>
                            <input type="date" class="form-control" id="extension_due_date" name="extension_due_date" value="">
                        </div>
                        <div class="form-group">
                            <label for="closed_date" class="control-label">Closed date</label>
                            <input type="date" class="form-control" id="closed_date" name="closed_date" value="">
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /modal -->

        @push('scripts')
            <script type="text/javascript">

                $(function () {

                    // csrf token
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    // dataTable list
                    var table = $('#basic-datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('admin.flows.table.datatable', $flow) }}",
                            type: 'POST',
                        },
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
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
                            {data: 'auditor', name: 'auditor.name'},
                            {data: 'auditee', name: 'auditee.name'},
                            {data: 'comments', name: 'comments'},
                            {data: 'finding', name: 'finding'},
                            {data: 'deviation_statement', name: 'deviation_statement'},
                            {data: 'evidence_reference', name: 'evidence_reference'},
                            {data: 'deviation_level', name: 'deviation_level'},
                            {data: 'safety_level_before_action', name: 'safety_level_before_action'},
                            {data: 'due_date', name: 'due_date'},
                            {data: 'repetitive_finding_ref_number', name: 'repetitive_finding_ref_number'},
                            {data: 'investigator', name: 'investigator.name'},
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

                        ],
                        scrollY: 600,
                        scrollX: true,
                        iDisplayLength: -1,
                        paging: false,
                        language: {
                            paginate: {
                                previous: "<i class='mdi mdi-chevron-left'>",
                                next: "<i class='mdi mdi-chevron-right'>"
                            }
                        },
                        drawCallback: function () {
                            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                        }
                    });

                    // // console log dataTable row
                    // $('#basic-datatable tbody').on('click', 'tr', function () {
                    //     console.log(table.row(this).data());
                    // });

                    // modal edit
                    $('body').on('click', '.editItem', function () {

                        var rule_reference = $(this).data('rule_reference');
                        var action = "{{ route('admin.flows.table.update', $flow->id) }}";
                        var method = "POST";

                        resetForm();

                        $.get("{{ route('admin.flows.table.index', $flow->id) }}" + '/' + rule_reference + '/edit', function (data) {
                            $('#modelHeading').html("Edit - " + data.resource.rule_reference); // modal header
                            $('#ItemForm').attr('action', action); // form action
                            $('#_method').val(method); // form method
                            $('#saveBtn').html("Update"); // form button

                            $('#rule_group').val(data.resource.rule_group);
                            $('#rule_section').val(data.resource.rule_section);
                            $('#rule_reference').val(data.resource.rule_reference);
                            $('#rule_title').val(data.resource.rule_title);

                            $('#rule_manual_reference').val(data.resource.rule_manual_reference);
                            $('#rule_chapter').val(data.resource.rule_chapter);

                            $('#company_manual').val(data.resource.company_manual);
                            $('#company_chapter').val(data.resource.company_chapter);

                            $('#frequency').val(data.resource.frequency);
                            $('#month_quarter').val(data.resource.month_quarter);
                            if (data.auditor) {
                                $('#assigned_auditor option[value=' + data.auditor.id + ']').prop('selected', true); // form data - selected user company
                            }
                            if (data.auditee) {
                                $('#assigned_auditee option[value=' + data.auditee.id + ']').prop('selected', true); // form data - selected user company
                            }
                            $('#comments').val(data.resource.comments);
                            $('#finding').val(data.resource.finding);
                            $('#deviation_statement').val(data.resource.deviation_statement);
                            $('#evidence_reference').val(data.resource.evidence_reference);
                            $('#deviation_level').val(data.resource.deviation_level);
                            $('#safety_level_before_action').val(data.resource.safety_level_before_action);
                            $('#due_date').val(data.resource.due_date);
                            $('#repetitive_finding_ref_number').val(data.resource.repetitive_finding_ref_number);

                            if (data.investigator) {
                                $('#assigned_investigator option[value=' + data.investigator.id + ']').prop('selected', true); // form data - selected user company
                            }
                            $('#corrections').val(data.resource.corrections);
                            $('#rootcause').val(data.resource.rootcause);
                            $('#corrective_actions_plan').val(data.resource.corrective_actions_plan);
                            $('#preventive_actions').val(data.resource.preventive_actions);
                            $('#action_implemented_evidence').val(data.resource.action_implemented_evidence);
                            $('#safety_level_after_action').val(data.resource.safety_level_after_action);
                            $('#effectiveness_review_date').val(data.resource.effectiveness_review_date);
                            $('#response_date').val(data.resource.response_date);

                            $('#extension_due_date').val(data.resource.extension_due_date);
                            $('#closed_date').val(data.resource.closed_date);

                            $('#ajaxModel').modal('show');
                        })
                    });

                    // reset form alert
                    var resetForm = function () {
                        var form = $('#ItemForm');
                        $(".alert-success").remove();
                        $(".text-danger").remove();
                        form.find("input").removeClass('is-invalid');
                        form.find("select").removeClass('is-invalid');
                        form.find("date").removeClass('is-invalid');
                        form.find("textarea").removeClass('is-invalid');
                    };


                    // ajax create | save
                    $('body').on('click', '#saveBtn', function (e) {
                        e.preventDefault();

                        resetForm();

                        $("#ajaxModel").scrollTop(0);

                        var form = $('#ItemForm');

                        $.ajax({
                            url: form.attr('action'),
                            type: form.attr('method'),
                            data: form.serialize(),
                            success: function (data) {
                                if (data.success) {
                                    form.before('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                                    table.draw();
                                } else {
                                    $.each(data.errors, function (input_name, input_error) {
                                        $("#" + input_name).addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
                                    });
                                }
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    });

                    {{--$('body').on('click', '.deleteItem', function () {--}}

                    {{--    var Item_id = $(this).data("id");--}}
                    {{--    confirm("Are You sure want to delete ?");--}}

                    {{--    $.ajax({--}}
                    {{--        type: "DELETE",--}}
                    {{--        url: "{{ route('ajaxItems.store') }}"+'/'+Item_id,--}}
                    {{--        success: function (data) {--}}
                    {{--            table.draw();--}}
                    {{--        },--}}
                    {{--        error: function (data) {--}}
                    {{--            console.log('Error:', data);--}}
                    {{--        }--}}
                    {{--    });--}}
                    {{--});--}}


                });// end function

            </script>
    @endpush

@endsection
