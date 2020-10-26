@extends('layouts.admin')

@section('content')

<div class="content">

    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Home</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- page content -->
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
        <!-- /page content -->

    </div>

</div>
@endsection
