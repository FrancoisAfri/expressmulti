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

                            <!-- end row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-3">
                                <p style="text-align:center;"><b>Customer Reviews:<strong> {{$date}}</strong></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body" dir="ltr">
                                    <div id="cardCollpase3" class="collapse pt-3 show">
                                        <div class="text-center">
                                            <div class="row mt-2">
                                            </div> <!-- end row -->
                                            <div id="statistics-chart" data-colors="#44cf9c" style="height: 270px;"
                                                 class="morris-chart mt-3"></div>
                                        </div>
										<div class="mt-3">
											<p style="text-align:center;"><b>Star Rating</b></p>
										</div>
                                    </div> <!-- end collapse-->
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div>
                    <!-- end row -->
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
<script src="{{ asset('js/vendor.min.js') }}"></script>
<!-- Plugins js -->
<script src="{{ asset('libs/morris.js06/morris.min.js') }}"></script>
<script src="{{ asset('libs/raphael/raphael.min.js') }}"></script>
<!-- App js -->
<script src="{{ asset('js/app.min.js') }}"></script>

<script>
    document.getElementById("back_button").onclick = function () {
        location.href = "/restaurant/reports";
    };
	
    window.addEventListener('DOMContentLoaded', (event) => {
        // Your JavaScript code to initialize the chart with chartData
		!function (e) {
    "use strict";

    function a() {
    }
    async function testedDoc() {
        let url = 'tester';

        try {
            let response = await fetch(url);
            if (response.ok) { // if HTTP-status is 200-299
                return await response.json();
            } else {
                // alert("HTTP-Error: " + response.status);
            }
        } catch (error) {
            console.log(error);
        }
    }
    a.prototype.createBarChart = function (a, t, e, o, r, i) {
        Morris.Bar({
            element: a,
            data: t,
            xkey: e,
            ykeys: o,
            labels: r,
            dataLabels: !1,
            hideHover: "auto",
            resize: !0,
            gridLineColor: "rgba(65, 80, 95, 0.07)",
            barSizeRatio: .2,
            barColors: i
        })
    }, 
	a.prototype.init = async function () {
    let chartColors = ["#182ea4"];
	const chartData = {!! json_encode($dataArray) !!};
    const dataArray = chartData; // Use the data passed from the controller

    const chart = e("#statistics-chart");
    const customColors = chart.data("colors");
    if (customColors) {
        chartColors = customColors.split(",");
    }

    this.createBarChart("statistics-chart", dataArray, "y", ["a"], ["Statistics"], chartColors);
}, e.Dashboard4 = new a, e.Dashboard4.Constructor = a
}(window.jQuery), function () {
    "use strict";
    window.jQuery.Dashboard4.init()
}();
    });
</script>

</body>
