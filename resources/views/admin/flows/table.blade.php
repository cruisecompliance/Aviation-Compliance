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

                            <!-- table header -->
                            <div class="row">
                                <div class="col-7">
                                    <h4 class="page-title mb-2">{{ $flow->title }}</h4>
                                    <div class="text-muted font-13">Company: <span class="font-weight-bold">{{ $flow->company->name }}</span></div>
                                    <div class="text-muted font-13">Requirements: <span class="font-weight-bold">version {{ $flow->requirement->id }}</span></div>
                                    <div class="text-muted font-13 mt-1">{{ $flow->description }}</div>
                                </div>
                            </div>
                            <!-- /table header -->

                            <!-- toolbar -->
                            <div class="row mb-2">
                                <div class="col-8">
                                    @include('components.flows._filter')
                                </div>
                                <div class="col-4 pl-3 text-right">
                                    @include('components.flows._iCal')
                                </div>
                            </div>
                            <!-- /toolbar -->

                            <!-- datatable -->
                            <div class="row">
                                <div class="col-12">

                                    <!-- assign  -->
                                @include('components.flows._assign')
                                <!-- /assign  -->

                                    <table id="basic-datatable" class="table nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="align-middle"></th>
                                            <th scope="col" class="align-middle">action</th>
                                            <th scope="col" class="align-middle">Sec #</th>
                                            <th scope="col" class="align-middle">European rule <br>IR/AMC/GM</th>
                                            <th scope="col" class="align-middle">Rule Reference</th>
                                            <th scope="col" class="align-middle">Status</th>
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
                            <!-- /datatable -->

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div><!-- end row-->

            <!-- /page content -->

        </div>

    </div>

    <!-- modal form -->
    @include('components.flows._modal')
    <!-- /modal form -->

    @push('scripts')
        <script type="text/javascript">

            $(function () {

                // filter form data (for datatable)
                var filterForm = $('#FilterModalForm');

                // dataTable list
                var table = $('#basic-datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.flows.table.datatable', $flow) }}",
                        type: 'POST',
                        data: function (d) {
                            d.rule_reference = filterForm.find('input[name=rule_reference]').val();
                            d.rule_section = filterForm.find('input[name=rule_section]').val();
                            d.assignee = filterForm.find('input[name=assignee]').val();
                            d.status = filterForm.find('input[name=status]').val();
                        }
                    },
                    columnDefs: [
                        {
                            targets: 0,
                            checkboxes: {
                                selectRow: true
                            },
                        }
                    ],
                    select: {
                        style: 'multi'
                    },
                    order: [[1, 'desc']],
                    columns: [
                        // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'id', name: 'id', orderable: false, searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                        {data: 'rule_section', name: 'rule_section'},
                        {data: 'rule_group', name: 'rule_group'},
                        {data: 'rule_reference', name: 'rule_reference'},
                        {data: 'task_status', name: 'task_status'},
                        {data: 'rule_title', name: 'rule_title'},
                        {data: 'rule_manual_reference', name: 'rule_manual_reference'},
                        {data: 'rule_chapter', name: 'rule_chapter'},
                        {data: 'company_manual', name: 'company_manual'},
                        {data: 'company_chapter', name: 'company_chapter'},
                        {data: 'frequency', name: 'frequency'},
                        {data: 'month_quarter', name: 'month_quarter'},
                        {data: 'auditor', name: 'auditor.name'},
                        {data: 'auditee', name: 'auditee.name'},
                        {data: 'questions', name: 'questions'},
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

                // show multiple modal form
                $("#show-multiple-modal").click(function () {
                    // e.preventDefault();

                    // reset form data
                    resetAssignFormAlert();

                    // set form data
                    var form = $('#multiple-modal');
                    var rows_selected = table.column(0).checkboxes.selected();
                    form.find('input[name=tasks_id]').val(rows_selected.join(","));
                    form.find('input[name=tasks_selected]').val(rows_selected.length);

                    // show form
                    form.modal('show');
                });

                // submit multiple modal form
                $('body').on('click', '#MultipleSubmit', function (e) {
                    e.preventDefault();

                    resetAssignFormAlert();

                    var form = $('#MultipleModalForm');
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: form.serialize(),
                        success: function (data) {
                            if (data.success) {
                                form.before('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                                form.find('input[name=due_date]').val('');
                                table.column(0).checkboxes.deselect();
                                table.draw();
                            } else {
                                $.each(data.errors, function (input_name, input_error) {
                                    form.find('input[name=' + input_name + ']').addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
                                });
                            }
                        }
                    });

                });

                function resetAssignFormAlert() {
                    var form = $('#MultipleModalForm');
                    $(".alert-success").remove();
                    $(".text-danger").remove();
                    form.find("input").removeClass('is-invalid');
                }

                ////// TEST SEARCH
                // // rule_reference search helper
                // $("#filter_tasks").keyup(throttle(function () {
                //
                //     var filterForm = $('#FilterModalForm');
                //
                //     var rule_reference = $(this).val();
                //
                //
                //
                //        filterForm.find('input[name=rule_reference]').val(rule_reference);
                //
                //         table.draw();
                //
                //
                // }));
                //
                //
                // // throttle search
                // function throttle(f, delay) {
                //     var timer = null;
                //     return function () {
                //         var context = this, args = arguments;
                //         clearTimeout(timer);
                //         timer = window.setTimeout(function () {
                //                 f.apply(context, args);
                //             },
                //             delay || 1000);
                //     };
                // }


            });// end function

        </script>
    @endpush

@endsection
