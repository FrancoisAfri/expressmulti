@extends('layouts.main-layout')
@section('page_dependencies')
     <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/jquery-toast-plugin/jquery.toast.min.css')}}"
          rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection
{{-- Page content --}}
@section('content')
    @section('content_data')
        <div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card-box widget-inline">
						<div class="row">
							<div class="col-sm-6 col-xl-3">
								<div class="p-2 text-center">
									<i class="mdi mdi-cash text-primary mdi-24px"></i>
									<h3>R {{ number_format($totalOrders  , 2, ',', '.')  ?? 0}}</h3>
									<p class="text-muted font-15 mb-0">Total Orders</p>
								</div>
							</div>

							<div class="col-sm-6 col-xl-3">
								<div class="p-2 text-center">
									<i class="mdi mdi-eye-outline text-success mdi-24px"></i>
									<h3>R {{ number_format($monthlyOrders  , 2, ',', '.')  ?? 0}}</h3>
									<p class="text-muted font-15 mb-0">Monthly Total Orders </p>
								</div>
							</div>

							<div class="col-sm-6 col-xl-3">
								<div class="p-2 text-center">
									<i class="mdi mdi-cart-arrow-down text-danger mdi-24px"></i>
									<h3>R {{ number_format($monthlyIncompleteOrders  , 2, ',', '.')  ?? 0}}</h3>
									<p class="text-muted font-15 mb-0">Monthly Incomplete Orders</p>
								</div>
							</div>

							<div class="col-sm-6 col-xl-3">
								<div class="p-2 text-center">
									<i class="mdi mdi-basket text-blue mdi-24px"></i>
									<h3>R {{ number_format($totalIncompleteOrders  , 2, ',', '.')  ?? 0}}</h3>
									<p class="text-muted font-15 mb-0">Total Incomplete</p>
								</div>
							</div>

						</div> <!-- end row -->
					</div> <!-- end card-box-->
				</div> <!-- end col-->
			</div>
			<div class="row">
				<div class="col-lg-4">
					<div class="card-box">
						<div class="dropdown float-right">
							<a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown"
							   aria-expanded="false">
								<i class="mdi mdi-dots-vertical"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right">
								<!-- item-->
								<a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
								<!-- item-->
								<a href="javascript:void(0);" class="dropdown-item">Export Report</a>
								<!-- item-->
								<a href="javascript:void(0);" class="dropdown-item">Profit</a>
								<!-- item-->
								<a href="javascript:void(0);" class="dropdown-item">Action</a>
							</div>
						</div>
						<h4 class="header-title mb-0">Total Daily Revenue</h4>
						<div class="widget-chart text-center" dir="ltr">
							<div id="total-revenue" class="mt-0" data-colors="#f86262"></div>
							<h5>Total Revenue Made Today</h5>
							<h2> R {{ number_format($totalPayment  , 2, ',', '.')  ?? 0}}</h2>
						</div>
					</div> <!-- end card-box -->
				</div> <!-- end col-->
				<div class="col-xl-8">
					<div class="card">
						<div class="card-body">
							<h4 class="header-title">Projections Vs Actuals</h4>
							<div class="row mt-4 text-center">
								<div class="col-4">
									<p class="text-muted font-15 mb-1 text-truncate">Target</p>
									<h4><i class="fe-arrow-down text-danger mr-1"></i>
									R {{ number_format($targetRevenue , 2, ',', '.')  }} </h4>
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
			<div class="text-left">
			<button type="button" id="back_button" class="btn btn-dark waves-effect waves-light"
					data-dismiss="modal">
				<i class="mdi mdi-skip-backward-outline"></i>
				Back
			</button> </br></br>
		</div>
        </div>
    @endsection
@endsection
@section('page_script')

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
    <script src="{{ asset('js/pages/dashboard-2.init.js')}}"></script>
    <script src="{{ asset('js/pages/dashboard-3.init.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
		 document.getElementById("back_button").onclick = function () {
			location.href = "/restaurant/reports";
		};
        (
		function () {
			// Call the function initially
			//checkAndUpdateTable();
			
            "use strict";
            function copyToClipboard(elem) {
                const target = elem;
                const currentFocus = document.activeElement;
                target.focus();
                target.setSelectionRange(0, target.value.length);
                let succeed;
                try {
                    succeed = document.execCommand("copy");
                } catch (e) {
                    console.warn(e);
                    succeed = false;
                }
                // Restore original focus
                if (currentFocus && typeof currentFocus.focus === "function") {
                    currentFocus.focus();
                }
                if (succeed) {
                   console.log("copied")
                }
                return succeed;
            }

        })();


        (dataColors = $("#total-revenue").data("colors")) && (colors = dataColors.split(","));
        let options = {
            series: [{{$dailyData}}],
            chart: {height: 220, type: "radialBar"},
            plotOptions: {radialBar: {hollow: {size: "65%"}}},
            colors: ["#04499a"],
            labels: ["Revenue"]
        };
        (chart = new ApexCharts(document.querySelector("#total-revenue"), options)).render();
		
    </script>
@stop
