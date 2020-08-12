<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Coderthemes" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">


</head>

<body class="loading">

    <!-- Begin wrapper -->
    <div id="wrapper">

        <!-- Topbar Start -->
        @include('user.__parts._topbar')
        <!-- Topbar End -->

        <!-- Left Sidebar Start-->
        @include('user.__parts._left_sidebar')
        <!-- Left Sidebar End -->

        <!-- Page Content Start -->
        <div class="content-page">
            <!-- content -->
            @yield('content')
            <!-- /content -->

            <!-- Footer Start -->
            {{-- @include('admin.__parts._footer') --}}
            <!-- end Footer -->
        </div>
        <!--  Page Content End-->

    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    @include('user.__parts._right_sidebar')
    <!-- /Right-bar -->

    <!-- App js -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')

</body>

</html>
