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
                                <li class="breadcrumb-item active">{{ $history->first()->rule_reference }}</li>
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
                                <div class="col-8 mb-2">
                                    <h4 class="page-title">{{ $history->first()->rule_reference }}</h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    @if($history->isNotEmpty())
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Version</th>
                                                    <th scope="col">Sec #</th>
                                                    <th scope="col">IR/AMC/GM</th>
                                                    <th scope="col">Rule reference</th>
                                                    <th scope="col">Rule title</th>
                                                    <th scope="col">AMC3 ORO.MLR.100 Manual Reference</th>
                                                    <th scope="col">AMC3 ORO.MLR.100 Chapter</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($history as $item)
                                                    <tr>
                                                        <td><a href="{{ route('admin.requirements.show', $item->version_id) }}">{{ $item->version_id }}</td>
                                                        <td>{{ $item->rule_section }}</td>
                                                        <td>{{ $item->rule_group }}</td>
                                                        <td>{{ $item->rule_reference }}</td>
                                                        <td>{{ $item->rule_title }}</td>
                                                        <td>{{ $item->rule_manual_reference }}</td>
                                                        <td>{{ $item->rule_chapter }}</td>
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
            <!-- /page content -->

        </div>

    </div>

    @push('scripts')

    @endpush

@endsection
