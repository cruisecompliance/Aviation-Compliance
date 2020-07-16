@extends('layouts.app')

@section('content')

    @if (session('status'))
        <!-- alert -->
        <div class="bg-light">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /alert -->
    @endif

    <div class="container-fluid">
        {{--<div class="container">--}}
        <div class="row justify-content-center">

            <!-- breadcrumb -->
            <div class="col-md-12 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"> Requirements</li>
                    </ol>
                </nav>
            </div>
            <!-- /breadcrumb -->

            <!-- versions -->
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">

                        <!-- header data -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h1>Requirements</h1>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('admin.requirements.create') }}" class="btn btn-primary mt-2 mb-2 float-right">Import</a>
                            </div>
                        </div>
                        <!-- header data -->

                        <!-- table data -->
                        <div class="row">
                            <div class="col-md-12">
                                @if($versions->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col" width="400px">Title</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col">Date</th>
                                                <th scope="col"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($versions as $version)
                                                <tr>
                                                    <th>{{ $version->id }}</th>
                                                    <td>{{ $version->title }}</td>
                                                    <td>{{ $version->file_name }}</td>
                                                    <td>{{ $version->created_at->format('d.m.Y H:i:s') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.requirements.show', $version->id) }}"><i class="fas fa-eye"></i></a>
                                                    </td>
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
                        <!-- /table data -->

                    </div>
                </div>
            </div>
            <!-- versions -->

            <!-- last requirements -->
            <div class="col-md-12 mb-3">
                <div class="card ">
                    <div class="card-body">
                        @if($lastRequirements->isNotEmpty())
                            <h1>Last Requirements Version</h1>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">Sec #</th>
                                        <th scope="col">IR/AMC/GM</th>
                                        <th scope="col">Rule reference</th>
                                        <th scope="col">Rule title</th>
                                        <th scope="col">AMC3 ORO.MLR.100 Manual Reference</th>
                                        <th scope="col">AMC3 ORO.MLR.100 Chapter</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lastRequirements as $lastRequirement)
                                        <tr>
                                            <td>{{ $lastRequirement->rule_section }}</td>
                                            <td>{{ $lastRequirement->rule_group }}</td>
                                            <td><a href="{{ route('admin.requirements.history', $lastRequirement->rule_reference) }}">{{ $lastRequirement->rule_reference }}</a></td>
                                            <td>{{ $lastRequirement->rule_title }}</td>
                                            <td>{{ $lastRequirement->rule_manual_reference }}</td>
                                            <td>{{ $lastRequirement->rule_chapter }}</td>
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

            <!-- last requirements -->

        </div>
    </div>
@endsection
