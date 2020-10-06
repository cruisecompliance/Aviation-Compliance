<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <title>Terms of Use</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Coderthemes" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">


</head>

<body class="loading">

<!-- Begin wrapper -->
<div id="wrapper">


    <div class="content-page">
        <h1>Terms of Use</h1>
    </div>
    <!--  Page Content End-->

</div>
<!-- END wrapper -->


<!-- App js -->
<script src="{{ asset('js/app.js') }}"></script>
{{--@stack('scripts')--}}

</body>

</html>
