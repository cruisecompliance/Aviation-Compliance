@extends('layouts.admin')

@section('content')

    <div class="content">

        <div class="container-fluid">

            <!-- page content -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-8">
                                    <h4 class="page-title">Flows</h4>
                                </div>

                                <div class="col-4">
                                    <a class="btn btn-primary float-right" href="javascript:void(0)" id="createNewItem"> Create</a>
                                </div>
                            </div>

                            <div class="border-top my-3"></div>

                            <div class="row">
                                <div class="col-12">
                                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th scope="col" width="400px">Title</th>
                                            <th scope="col">Company</th>
                                            <th scope="col">Requirements</th>
                                            <th scope="col" width="40px"></th>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="ItemForm" action="" name="ItemForm" class="form-horizontal" method="POST">
                        <input type="hidden" name="_method" id="_method" value="">
                        <input type="hidden" name="Item_id" id="Item_id">
                        <div class="form-group">
                            <label for="title" class="control-label">Title*</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="company" class="control-label">Company*</label>
                            <select name="company" id="company" class="form-control">
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="requirements" class="control-label">Requirements*</label>
                            <select name="requirements" id="requirement" class="form-control">
                                @foreach($requirements as $requirement)
                                    <option value="{{ $requirement->id }}">{{ $requirement->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description" class="control-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder=""></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save changes</button>
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
                            url: "{{ route('admin.flows.index') }}",
                            type: 'GET',
                        },
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'title', name: 'title'},
                            {data: 'company', name: 'company.name'},
                            {data: 'requirement', name: 'requirement.title'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],
                        "language": {
                            "paginate": {
                                "previous": "<i class='mdi mdi-chevron-left'>",
                                "next": "<i class='mdi mdi-chevron-right'>"
                            }
                        },
                        "drawCallback": function () {
                            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                        }
                    });

                    // // console log dataTable row
                    // $('#basic-datatable tbody').on('click', 'tr', function () {
                    //     console.log(table.row(this).data());
                    // });

                    // modal create
                    $('#createNewItem').click(function () {
                        var action = "{{ route('admin.flows.store') }}";
                        var method = "POST";

                        resetForm();
                        $('#ItemForm').trigger("reset");

                        $('#modelHeading').html("Create Flow");
                        $('#ItemForm').attr('action', action); // form action
                        $('#_method').val(method); // form method
                        $('#saveBtn').html("Create"); // form button
                        $('#title').val(''); //Add form data
                        $('#description').val(''); //Add form data
                        $('#ajaxModel').modal('show');
                    });

                    // modal edit
                    $('body').on('click', '.editItem', function () {

                        var flow_id = $(this).data('id');
                        var action = "{{ route('admin.flows.index') }}" + '/' + flow_id;
                        var method = "PATCH";

                        resetForm();

                        $.get("{{ route('admin.flows.index') }}" + '/' + flow_id + '/edit', function (data) {

                            $('#modelHeading').html("Edit Flow - " + data.flow.title); // modal header
                            $('#ItemForm').attr('action', action); // form action
                            $('#_method').val(method); // form method
                            $('#saveBtn').html("Update"); // form button
                            $('#title').val(data.flow.title); // form data
                            $('#description').val(data.flow.description); // form data
                            $('#company option[value=' + data.company.id + ']').prop('selected', true);
                            $('#company').prop('disabled', true);
                            $('#requirement option[value="' + data.requirement.id + '"]').prop('selected', true).prop('disabled', true);
                            $('#requirement').prop('disabled', true);
                            $('#ajaxModel').modal('show');
                        })
                    });


                    // reset form alert
                    var resetForm = function () {
                        var form = $('#ItemForm');
                        form.find("input").removeClass('is-invalid');
                        form.find("select").removeClass('is-invalid');
                        form.find("textarea").removeClass('is-invalid');
                        $(".alert-success").remove();
                        $(".alert-danger").remove();
                        $(".text-danger").remove();
                    };

                    // ajax create | save
                    $('body').on('click', '#saveBtn', function (e) {
                        e.preventDefault();
                        resetForm();
                        var form = $('#ItemForm');
                        $.ajax({
                            url: form.attr('action'),
                            type: form.attr('method'),
                            data: form.serialize(),
                            success: function (data) {
                                // console.log(data);
                                if (data.success) {
                                    form.before('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                                    table.draw();
                                } else {
                                    $.each(data.errors, function (input_name, input_error) {
                                        form.find("[name='" + input_name + "']").addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
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
