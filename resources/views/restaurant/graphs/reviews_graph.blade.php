<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Restaurant Management') }}</title>
    {{--    <link rel="shortcut icon" href="{{ $logo }} ">--}}
    <!-- core:css -->
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- App css -->
    <link media="all" href="{{ asset('css/bootstrap-creative.min.css') }}" rel="stylesheet" type="text/css"
          id="bs-default-stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href=" {{ asset('css/app-creative.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet"/>
    <!-- icons -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- core:css -->
</head>
<body data-layout-mode="horizontal"
      data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
<!-- Begin page -->
<div id="wrapper">
    <div class="content-page">
        <div class="content">
            <div class="col-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-4 offset-md-2">
                            <div class="mt-3 float-right">
                                <p class="m-b-10"><strong> Date : {{$date}}</strong> <span
                                            class="float-right"> &nbsp;&nbsp; </span>
                                </p>
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-3 align-content-left">
                                <p><b>Customer Reviews
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                    <div class="card-body">
                                        <div>
                                            <div class="text-center">
												<div style="width: 70px; height: 50px; background-color: #3283f6;">Ambience</div>
												<div style="width: 70px; height: 50px; background-color: #43bee1;">Food</div>
												<div style="width: 70px; height: 50px; background-color: #e3eaef;">Service</div>
												<div style="width: 70px; height: 50px; background-color: #fcc015;">Our App</div>
                                                <div id="total-users" data-colors="#3283f6,#43bee1,#e3eaef,#fcc015"></div>
                                                 <!-- end row -->
                                            </div>
                                        </div> <!-- collapsed end -->
                                    </div> <!-- end card-body -->
                                </div>  <!-- end card-->
                        </div> <!-- end col-->
                    </div>
                    <div class="mt-4 mb-1">
                        <div class="text-right d-print-none">
                            <div class="text-left">
                                <button type="button" id="back_button" class="btn btn-dark waves-effect waves-light"
                                        data-dismiss="modal">
                                    <i class="mdi mdi-skip-backward-outline"></i>
                                    Back
                                </button>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-box -->
            </div> <!-- end col -->
        </div> <!-- content -->
    </div>
</div>
<script src="{{ asset('js/vendor.min.js') }}"></script>
<!-- Plugins js -->
<script src="{{ asset('libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>

<script src="{{ asset('js/custom_components/js/donuts_graph.js') }}"></script>
<!-- App js -->
<script src="{{ asset('js/app.min.js') }}"></script>

<script>
document.getElementById("back_button").onclick = function () {
        location.href = "/restaurant/reports";
    };
</script>

</body>
