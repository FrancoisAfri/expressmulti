<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'xpresserv') }}</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}"> 
    <!-- core:css -->
    <link href="{{ mix('global.css') }}" rel="stylesheet" type="text/css"/>
    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- core:css -->
	
</head>

<div id="preloader">
    <div id="status">
        <div class="spinner">Loading...</div>
    </div>
</div>
@yield('styles')
