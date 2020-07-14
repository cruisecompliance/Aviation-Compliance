@extends('layouts.app')

@section('content')
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

            <!-- requirements -->
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
            <!-- requirements -->

        </div>
    </div>
@endsection
