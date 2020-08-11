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
                                <li class="breadcrumb-item"><a href="{{ route('admin.requirements.index') }}">Requirements</a></li>
                                <li class="breadcrumb-item active">{{ $requirement->title }}</li>
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
                                <div class="col-9">
                                    <h4 class="page-title">{{ $requirement->title }}</h4>
                                    <p class="text-muted font-13">{{ $requirement->description }}</p>
                                </div>
                                <div class="col-3">
                                    <div class="card shadow-none border">
                                        <div class="p-2">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="avatar-sm">
                                                        <span class="avatar-title bg-light text-secondary rounded">
                                                            <i data-feather="file-text" class="icon-dual"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col pl-0">
                                                    <a href="/storage/{{ $requirement->file_name }}" class="text-muted font-weight-bold">{{ $requirement->file_name }}</a>
                                                </div>
                                            </div> <!-- end row -->
                                        </div> <!-- end .p-2-->
                                    </div> <!-- end col -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table id="basic-datatable" class="table nowrap w-100">
                                        <thead>
                                        <tr>
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

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div><!-- end row-->

            <!-- /page content -->

        </div>

    </div>


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
                        url: "{{ route('admin.requirements.show', $requirement->id) }}",
                        type: 'GET',
                    },
                    columns: [
                        // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
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
                // $('#basic-datatable tbody').on( 'click', 'tr', function () {
                //     console.log( table.row( this ).data() );
                // } );

            });// end function

        </script>
    @endpush

@endsection
