@extends('layouts.auth')

@section('content')
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="/" class="logo logo-dark text-center">
                                        <span class="">
                                            <img src="{{ asset('images/logo-light.png') }}" alt="" height="35">
                                        </span>
                                    </a>

                                    <a href="/" class="logo logo-light text-center">
                                        <span class="">
                                            <img src="{{ asset('images/logo-light.png') }}" alt="" height="35">
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">Please confirm your password before continuing.</p>
                            </div>

                            <form action="{{ route('password.confirm') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Enter your password" >
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit"> Confirm Password</button>
                                </div>

                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            @if (Route::has('password.request'))
                                <p><a href="{{ route('password.request') }}" class="text-white-50 ml-1">Forgot your password?</a></p>
                            @endif
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->
@endsection


