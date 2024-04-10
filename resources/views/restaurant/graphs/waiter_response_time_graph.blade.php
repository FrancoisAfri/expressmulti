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
            <div class="col-12" id="invoice">
                <div class="card-box">
                    <!-- Logo & title -->
                    <div class="clearfix">
                        <div class="float-left">
                            <div class="auth-logo">
                                <div class="logo logo-dark">
									<span class="logo-lg">

									</span>
                                    <h4></h4>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="float-right">
                            <p class="m-b-10"><strong> </strong> <span
                                    class="float-right"></span>
                            </p>
                            <p class="m-b-10"><strong></strong> <span
                                    class="float-right"> </span></p>
                            <p class="m-b-10"><strong> </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp;
                                   </span>
                            </p>
                            <p class="m-b-10"><strong> </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp;
                                    </span>
                        </div>
                        <br><br><br>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        </div><!-- end col -->
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
                                <p><b>Waiter Response Time</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Projections Vs Actuals</h4>
                                    <div class="row mt-4 text-center">
                                        <div class="col-4">
                                            <p class="text-muted font-15 mb-1 text-truncate">Target</p>
                                            <h4><i class="fe-arrow-down text-danger mr-1"></i>
                                               </h4>
                                        </div>
                                        <div class="col-4">
                                        </div>
                                        <div class="col-4">
                                        </div>
                                    </div>
                                    <div class="mt-3 chartjs-chart">
                                        <canvas id="projections-actuals-chart" data-colors="#44cf9c,#e3eaef"
                                                height="300"></canvas>
                                    </div>
                                </div>
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
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
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
<script src="{{ asset('js/custom_components/js/deleteAlert.js') }}"></script>
<script src="{{ asset('js/custom_components/js/deleteModal.js') }}"></script>
<script src="{{ asset('libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('js/pages/form-masks.init.js') }}"></script>
<script src="{{ asset('libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<!-- third party js ends -->
<!-- Tickets js -->
<script src="{{ asset('js/pages/tickets.js') }}"></script>
<script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('libs/intl-tel-input/build/js/intlTelInput.js') }}"></script>
<script src="{{ asset('js/custom_components/js/sweetalert.min.js') }}"></script>
<!-- Plugins js-->
<script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('libs/selectize/js/standalone/selectize.min.js')}}"></script>
<script src="{{ asset('libs/chart.js/Chart.bundle.min.js') }} "></script>
<script src="{{ asset('libs/moment/min/moment.min.js') }} "></script>
<script src="{{ asset('libs/jquery.scrollto/jquery.scrollTo.min.js') }} "></script>
<!-- Plugins js-->
<script src="{{ asset('libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('libs/moment/min/moment.min.js')}}"></script>
<!--<script src="{{ mix('js/app.js') }}"></script>
     Calendar init 13trElement
    <script src="{{ asset('js/calendar.js')}}"></script>
    <script src="{{ asset('libs/jquery-toast-plugin/jquery.toast.min.js')}}"></script>-->
<!-- toastr init js-->
<!-- <script src="{{ asset('js/pages/toastr.init.js')}}"></script>
    <script src="{{ asset('js/pages/datatables.init.js') }}"></script>-->
<!-- Dashboar 1 init js-->
<script src="{{ asset('js/pages/dashboard-2.init.js')}}"></script>
<script src="{{ asset('js/pages/dashboard-3.init.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>

    document.getElementById("back_button").onclick = function () {
        location.href = "/restaurant/reports";
    };


</script>
</body>
