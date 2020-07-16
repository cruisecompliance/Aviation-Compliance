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
                        <li class="breadcrumb-item active" aria-current="page"> {{ $history->first()->rule_reference }}</li>
{{--                        <li class="breadcrumb-item active" aria-current="page"> {{ $history[0]['rule_reference }}</li>--}}
                    </ol>
                </nav>
            </div>
            <!-- /breadcrumb -->

            <!-- file data block-->
            <div class="col-md-12 mb-3">
                <div class="card ">
                    <div class="card-body">
                        @if($history->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
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
