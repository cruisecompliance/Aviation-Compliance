@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{--    <div class="container">--}}
        <div class="row justify-content-center">

            <!-- breadcrumb -->
            <div class="col-md-12 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.requirements.index') }}">Requirements</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> {{ $version->title }}</li>
                    </ol>
                </nav>
            </div>
            <!-- /breadcrumb -->

            <!-- file info block -->
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <p><span class="font-weight-bold">Title: </span>{{ $version->title }}</p>
                        <p><span class="font-weight-bold">Description:</span> {{ $version->description }}</p>
                        <p><span class="font-weight-bold">Version:</span> {{ $version->id }}</p>
                        <p><span class="font-weight-bold">File:</span> <a href="/storage/{{ $version->file_name }}">{{ $version->file_name }}</a></p>
                        <p><span class="font-weight-bold>">Date:</span> {{ $version->created_at->format('d.m.Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
            <!-- /file info block -->

            <!-- file data block-->
            <div class="col-md-12 mb-3">
                <div class="card ">
                    <div class="card-body">
                        @if($requirements->isNotEmpty())
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
                                    @foreach($requirements as $requirement)
                                        <tr>
                                            <td>{{ $requirement->rule_section }}</td>
                                            <td>{{ $requirement->rule_group }}</td>
                                            <td>{{ $requirement->rule_reference }}</td>
                                            <td>{{ $requirement->rule_title }}</td>
                                            <td>{{ $requirement->rule_manual_reference }}</td>
                                            <td>{{ $requirement->rule_chapter }}</td>
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
            <!-- file data block-->

        </div>

    </div>

@endsection
