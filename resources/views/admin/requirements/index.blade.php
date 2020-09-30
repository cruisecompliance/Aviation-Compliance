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
                                    <h4 class="page-title">Requirements</h4>
                                </div>

                                <div class="col-4">
                                    <a class="btn btn-primary float-right mb-3" href="javascript:void(0)" id="createNewItem"> Import</a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    @if($versions->isNotEmpty())
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th class="border-0">ID</th>
                                                    <th class="border-0">Title</th>
                                                    <th class="border-0">File Name</th>
                                                    <th class="border-0" width="100px">Date</th>
                                                    <th class="border-0" width="40px"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($versions as $version)
                                                    <tr>
                                                        <th>{{ $version->id }}</th>
                                                        <td>{{ $version->title }}</td>
                                                        <td>
                                                            <i data-feather="file-text" class="icon-dual"></i>
                                                            <span class="ml-2 font-weight-semibold">{{ $version->file_name }}</span>
                                                        </td>
                                                        <td>
                                                            {{ $version->created_at->format('d.m.Y') }}
                                                            <span class="text-muted">
                                                                {{ $version->created_at->format('H:i:s') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.requirements.show', $version->id)  }}" class="edit btn btn-primary btn-sm">View</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div><!-- end row-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <h4 class="page-title">Last version of requirements</h4>
                                </div>
                            </div>
                            @if($versions->isNotEmpty())
                                <div class="row">
                                    <div class="col-12">
                                        <table id="basic-datatable" class="table nowrap w-100">
                                            <thead>
                                            <tr>
                                                <th scope="col" width="40px"></th>
                                                <th scope="col">Sec #</th>
                                                <th scope="col">IR/AMC/GM</th>
                                                <th scope="col">Rule Reference</th>
                                                <th scope="col">Rule Title</th>
                                                <th scope="col">AMC3 ORO.MLR.100 Manual Reference</th>
                                                <th scope="col">AMC3 ORO.MLR.100 Chapter</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            @endif


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
                    <form id="ItemForm" action="" name="ItemForm" class="form-horizontal" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="_method" id="_method" value="">
                        <input type="hidden" name="Item_id" id="Item_id">

                        <div class="form-group">
                            <label for="title" class="control-label">Title*</label>
                            <input type="text" class="form-control" id="title" name="title" value="" required>
                        </div>

                        <div class="form-group">
                            <label for="description" class="control-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder=""></textarea>
                        </div>

                        {{--                        <div class="form-group">--}}
                        {{--                            <label>.xlsx file*</label>--}}
                        {{--                            <div class="input-group">--}}
                        {{--                                <div class="custom-file">--}}
                        {{--                                    <input type="file" class="custom-file-input" id="user_file" name="user_file" value="" required>--}}
                        {{--                                    <label class="custom-file-label" for="user_file"></label>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        <div class="form-group">
                            <label for="user_file">.xlsx file*</label>
                            <input type="file" id="user_file" class="form-control" name="user_file">
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
                            url: "{{ route('admin.requirements.index') }}",
                            type: 'GET',
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

                    // modal create
                    $('#createNewItem').click(function () {
                        var action = "{{ route('admin.requirements.store') }}";
                        var method = "POST";

                        resetForm();
                        $('#ItemForm').trigger("reset");

                        $('#modelHeading').html("Import Requirements");
                        $('#ItemForm').attr('action', action); // form action
                        $('#_method').val(method); // form method
                        $('#saveBtn').html("Create"); // form button

                        $('#title').val(''); //Add form data
                        $('#description').val(''); //Add form data
                        $('#user_file').val(''); //Add form data

                        $('#ajaxModel').modal('show');
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


                    // ajax import
                    $('body').on('click', '#saveBtn', function (e) {
                        e.preventDefault();
                        resetForm();

                        var form = $('#ItemForm');
                        var formData = new FormData(form[0]);
                        // var formData = new FormData(document.getElementById("ItemForm"));

                        $.ajax({
                            url: form.attr('action'),
                            type: form.attr('method'),
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function (data, textStatus, jqXHR) {

                                // form errors
                                if (data.success) {
                                    form.before('<div class="alert alert-success" role="alert">' + data.message + '</div>');
                                    table.draw();
                                } else {
                                    $.each(data.errors, function (input_name, input_error) {
                                        form.find("[name='" + input_name + "']").addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
                                    });
                                }

                                // file errors
                                if (data.duplicate) {
                                    $.each(data.duplicate, function (id, rule_reference) {
                                        // console.log(rule_reference);
                                        form.before('<div class="alert alert-danger" role="alert">The ' + rule_reference + ' field is duplicated. </div>');
                                    });
                                }
                                if (data.empty) {
                                    $.each(data.empty, function (index, element) {
                                        // console.log(element.row);
                                        form.before('<div class="alert alert-danger" role="alert">The Rule Reference field is empty on row - ' + element.row + '</div>');
                                    });
                                }
                            },
                        });
                    });

                });// end function

            </script>
    @endpush

@endsection
