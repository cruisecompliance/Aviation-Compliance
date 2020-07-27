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
                                <label>Companies
                                    <small>*</small>
                                </label>
                                <select name="company" class="form-control @error('company') is-invalid @enderror">
                                    <option value="">...</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                @error('company')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Requirements
                                    <small>*</small>
                                </label>
                                <select name="requirement" class="form-control @error('requirement') is-invalid @enderror">
                                    @foreach($requirements as $requirement)
                                        <option value="{{ $requirement->id }}">{{ $requirement->title }}</option>
                                    @endforeach
                                </select>
                                @error('requirement')
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
                            {{--                            <input name="requirement_id" type="hidden" value="{{ $requirement->id }}">--}}
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /form -->

        </div>
    </div>
@endsection
