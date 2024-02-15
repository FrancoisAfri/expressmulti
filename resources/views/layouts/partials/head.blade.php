<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'xpresserv') }}</title>

        <link rel="shortcut icon" href="{{ $logo }} "> 

    <!-- core:css -->
    <link href="{{ mix('global.css') }}" rel="stylesheet" type="text/css"/>
{{--    <link href="{{ asset('libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css"/>--}}
    <!-- App css -->
{{--    <link href=" {{ asset('css/bootstrap-creative.min.css') }}" rel="stylesheet" type="text/css"--}}
{{--          id="bs-default-stylesheet"/>--}}

{{--    <link rel="stylesheet" href="{{ mix('css/app.css') }}">--}}

{{--    <link href=" {{ asset('css/app-creative.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet"/>--}}

{{--    --}}{{--    <link href=" {{ asset('css/bootstrap-creative-dark.min.css') }}" rel="stylesheet" type="text/css"--}}
{{--    --}}{{--          id="bs-dark-stylesheet" disabled/>--}}

{{--    --}}{{--    <link href=" {{ asset('css/app-creative-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet"--}}
{{--    --}}{{--          disabled/>--}}
{{--    <!-- icons -->--}}
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- core:css -->

</head>

<div id="preloader">
    <div id="status">
        <div class="spinner">Loading...</div>
    </div>
</div>
@yield('styles')
