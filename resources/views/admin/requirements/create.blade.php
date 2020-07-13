@extends('layouts.app')

@section('content')
    @if (session('status'))
        <!-- alert -->
        <div class="bg-light">
            <div class="container">
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

            <!-- Import form -->
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
                                <input type="text" class="form-control" name="title" placeholder="">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder=""></textarea>
                            </div>
                            <div class="form-group">
                                <label>.xlsx file to import</label>
                                <input type="file" class="form-control-file" name="user_file">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Import form -->

        </div>
    </div>
@endsection
