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
                        <li class="breadcrumb-item active" aria-current="page"> Flows</li>
                    </ol>
                </nav>
            </div>
            <!-- /breadcrumb -->

            <!-- flow -->
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">

                        <!-- header data -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h1>Flows</h1>
                            </div>
                            <div class="col-md-4">
{{--                                <a href="{{ route('admin.flows.create') }}" class="btn btn-primary mt-2 mb-2 float-right">Create</a>--}}
                            </div>
                        </div>
                        <!-- header data -->

                        <!-- table data -->
                        <div class="row">
                            <div class="col-md-12">
                                @if($flows->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col" width="400px">Title</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Requiremets</th>
                                                <th scope="col"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($flows as $flow)
                                                <tr>
                                                    <th>{{ $flow->id }}</th>
                                                    <td>{{ $flow->title }}</td>
                                                    <td>{{ $flow->created_at->format('d.m.Y H:i:s') }}</td>
                                                    <td>version: {{ $flow->requirement->id }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a id="btnGroupDrop1" type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-h fa-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                                <a class="dropdown-item" href="{{ route('admin.flows.show', $flow->id) }}">View</a>
                                                            </div>
                                                        </div>

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
            <!-- flow -->

        </div>
    </div>
@endsection
