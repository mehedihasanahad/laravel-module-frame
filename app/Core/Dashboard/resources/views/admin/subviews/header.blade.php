<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel Module Frame')</title>
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">
    <!-- commonFunction -->
    <link rel="stylesheet" href="{{ asset('assets/css/common.css') }}">
    <style>
        .button {
            outline: none;
            border: none;
            border-radius: 0.22rem;
            padding: 0.3rem;
            box-shadow: 0px 0px 10px 5px rgba(0,0,0,0.1);
        }
    </style>
    @yield('custom-css')
</head>
<body class="hold-transition sidebar-mini">
