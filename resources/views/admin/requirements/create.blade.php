@extends('layouts.app')

@section('content')

        <div class="container">
{{--    <div class="container-fluid">--}}

        <div class="row justify-content-center">

            <!-- breadcrumb -->
            <div class="col-md-12 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.requirements.index') }}">Requirements</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Import</li>
                    </ol>
                </nav>
            </div>
            <!-- /breadcrumb -->

            <!-- form -->
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h1>Import Requirements</h1>
                        <form action="{{ route('admin.requirements.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label>Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>.xlsx file to import</label>
                                <input type="file" class="form-control-file @error('user_file') is-invalid @enderror" name="user_file">
                                @error('user_file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- / form -->

        </div>
    </div>
@endsection
