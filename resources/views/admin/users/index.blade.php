@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        {{--    <div class="container">--}}
        <div class="row justify-content-center">

            <!-- breadcrumb -->
            <div class="col-md-12 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"> Users</li>
                    </ol>
                </nav>
            </div>
            <!-- /breadcrumb -->


            <!-- data block-->
            <div class="col-md-12 mb-3">
                <div class="card ">
                    <div class="card-body">

                        <!-- header data -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h1>Users</h1>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-primary mt-2 mb-2 float-right" href="javascript:void(0)" id="createNewItem"> Create</a>
                            </div>
                        </div>
                        <!-- /header data -->

                        <!-- table data -->
                        <div class="table">
                            <table class="table table-sm table-bordered" id="datatable">
                                <thead>
                                <tr>
                                    <th scope="col" class="align-middle" width="250px">E-mail</th>
                                    <th scope="col" class="align-middle">Name</th>
                                    <th scope="col" class="align-middle">Company</th>
                                    <th scope="col" class="align-middle">Role</th>
                                    <th scope="col" class="align-middle" width="100px">Status</th>
                                    <th scope="col" class="align-middle" width="40px"></th>
                                </tr>
                                </thead>
                                <tbody>
                            </table>
                        </div>
                        <!-- /table data -->

                    </div>
                </div>
            </div>
            <!-- /data block -->

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
                            <label for="email" class="control-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter E-mail" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="company" class="control-label">Company</label>
                            <select name="company" id="company" class="form-control">
                                @foreach($companies as $company)
                                <option value="{{ $company->id  }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role" class="control-label">Role</label>
                            <select name="role" id="role" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group password">
                            <label for="password" class="control-label">Password</label>
                            <input type="text" class="form-control" id="password" name="password" placeholder="Enter Password" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" selected>Active</option>
                                <option value="0">Disabled</option>
                            </select>
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
                    var table = $('#datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('admin.users.index') }}",
                            type: 'GET',
                        },
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            {data: 'email', name: 'email'},
                            {data: 'name', name: 'name'},
                            {data: 'company', name: 'company'},
                            {data: 'role', name: 'role'},
                            {data: 'status',
                                'render': function (data, type, row) {
                                    return (data == true)
                                        ? 'Active'
                                        : 'Disabled';
                                }
                            },
                            {data: 'action', name: 'action', orderable: false, searchable: false},

                        ]
                    });

                    // modal create
                    $('#createNewItem').click(function () {
                        var action = "{{ route('admin.users.store') }}";
                        var method = "POST";

                        resetForm();
                        $('#ItemForm').trigger("reset");
                        $('.password').show(); // show div password


                        $('#modelHeading').html("Create User");
                        $('#ItemForm').attr('action', action); // form action
                        $('#_method').val(method); // form method
                        $('#saveBtn').html("Create"); // form button
                        $('#name').val(''); //Add form data
                        $('#email').val(''); //Add form data
                        $('#password').val(''); //Add form data
                        $('#ajaxModel').modal('show');
                    });


                    // modal edit
                    $('body').on('click', '.editItem', function () {

                        var user_id = $(this).data('id');
                        var action = "{{ route('admin.users.index') }}" + '/' + user_id;
                        var method = "PATCH";

                        resetForm();
                        $('.password').hide(); // remove div password

                        $.get("{{ route('admin.users.index') }}" + '/' + user_id + '/edit', function (data) {

                            $('#modelHeading').html("Edit User - " + data.user.name); // modal header
                            $('#ItemForm').attr('action', action); // form action
                            $('#_method').val(method); // form method
                            $('#saveBtn').html("Update"); // form button
                            $('#name').val(data.user.name); // form data
                            $('#email').val(data.user.email); // form data
                            $('#company option[value=' + data.company.id + ']').prop('selected', true); // form data - selected user company
                            $('#role option[value="' + data.role.name + '"]').prop('selected', true); // form data - selected user role
                            $('#ajaxModel').modal('show');
                        })
                    });

                    // reset form alert
                    var resetForm = function () {
                        $(".alert-success").remove();
                        $(".text-danger").remove();
                        $("form").find("input").removeClass('is-invalid');
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
                                        form.find("input[name='" + input_name + "']").addClass('is-invalid').after('<span class="text-danger">' + input_error + '</span>');
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
