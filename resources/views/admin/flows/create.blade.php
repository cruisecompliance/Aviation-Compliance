@extends('layouts.app')

@section('content')

        <div class="container">
{{--    <div class="container-fluid">--}}

        <div class="row justify-content-center">

            <!-- breadcrumb -->
            <div class="col-md-12 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.flows.index') }}">Flows</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Create</li>
                    </ol>
                </nav>
            </div>
            <!-- /breadcrumb -->

{{--            <!-- file info block -->--}}
{{--            <div class="col-md-12 mb-3">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-body">--}}
{{--                        <p><span class="font-weight-bold">Title: </span>{{ $requirement->title }}</p>--}}
{{--                        <p><span class="font-weight-bold">Description:</span> {{ $requirement->description }}</p>--}}
{{--                        <p><span class="font-weight-bold">Version:</span> {{ $requirement->id }}</p>--}}
{{--                        <p><span class="font-weight-bold">File:</span> <a href="/storage/{{ $requirement->file_name }}">{{ $requirement->file_name }}</a></p>--}}
{{--                        <p><span class="font-weight-bold>">Date:</span> {{ $requirement->created_at->format('d.m.Y H:i:s') }}</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!-- /file info block -->--}}

            <!-- form -->
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h1>Create Flow</h1>
                        <form action="{{ route('admin.flows.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Title
                                    <small>*</small>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="">
                                @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Requirements
                                    <small>*</small>
                                </label>
                                <input type="text" class="form-control @error('version') is-invalid @enderror" name="version" value="version - {{ $requirement->id }} {{ $requirement->title }}" disabled="">
                                @error('version')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
        
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <input name="requirement_id" type="hidden" value="{{ $requirement->id }}">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /form -->

        </div>
    </div>
@endsection
