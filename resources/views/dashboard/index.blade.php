@extends('layouts.main-layout')
@section('page_dependencies')
     <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/jquery-toast-plugin/jquery.toast.min.css')}}"
          rel="stylesheet" type="text/css" />
{{--    <link href="{{ asset('copyLink.css') }}" rel="stylesheet" type="text/css"/>--}}
{{--    <link href="{{ asset('toast.css') }}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection
{{-- Page content --}}
@section('content')
    @section('content_data')
        <div class="container-fluid">
			@role('Admin|Owner')
				@if($activeModules->where('code_name', 'restaurant')->first())
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
				@endif
            @endrole
			<div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-widgets">
                                <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                <a data-toggle="collapse" href="#cardCollpase5" role="button" aria-expanded="false"
                                   aria-controls="cardCollpase5"><i class="mdi mdi-minus"></i></a>
                                <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                            </div>
                            <h4 class="header-title mb-0">Service Requests</h4>
                            <div id="cardCollpase5" class="collapse pt-3 show">
                                <div class="table-responsive">
                                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                                           id="data-table">
                                        <thead>
											<tr>
												<th>Request time</th>
												<th>Request type</th>
												<th>Table</th>
												<th>Waiter</th>
												<th>Patron</th>
												<th>Request Details</th>
												<th>Timer</th>
												<th></th>
												<th></th>
											</tr>
                                        </thead>
                                        <tbody>
											<script>
												var time = [];
												var running = [];
											</script>
											@if (!empty($services))
												@foreach($services as $service)
													<script>
														running[{{ $service->id }}] = {{ ($service->status == 1) ? 1 : 0 }};
														time[{{ $service->id }}] = {{ ($service->status == 1) ? ((time() - $service->requested_time) * 10) : 1 * 10 }};
													</script>
													<tr>
														<td  style="color: white;" class="{{ $service->id . 'trElement' }}">{{ !empty($service->requested_time) ? date('d/m/Y', $service->requested_time) : '' }}</td>
														<td  style="color: white;" class="{{ $service->id . 'trElement' }}">{{ !empty($service->service_type) ? $resquest_type[$service->service_type] : '' }}</td>
														<td  style="color: white;" class="{{ $service->id . 'trElement' }}">{{ !empty($service->tables->name) ? $service->tables->name : '' }}</td>
														<td  style="color: white;" class="{{ $service->id . 'trElement' }}">{{ !empty($service->tables->employees->first_name) && !empty($service->tables->employees->surname) ? $service->tables->employees->first_name." ".$service->tables->employees->surname : '' }}</td>
														<td  style="color: white;" class="{{ $service->id . 'trElement' }}">{{ !empty(\App\Models\TableScans::getTableName($service->table_id)) ? \App\Models\TableScans::getTableName($service->table_id) : '' }}</td>
														<td  style="color: white;" class="{{ $service->id . 'trElement' }}">{{ !empty($service->service) ? $service->service : '' }} </br>
															{{ !empty($service->comment) ? $service->comment : '' }}
														</td>
														<td class="{{ $service->id . 'trElement' }}"><input type="text" id="{{ $service->id . 'stopWatchDisplay' }}" style="font-weight:bold; font-family:cursive; width: 120px; height: 23px;" value="" class="input-sm" disabled></td>
														@if ($service->service_type == 1)
															
															<td>
																<button type="button" class="close mark-as-read"
																		onclick="postData({{$service->id}}, 'closeservice');"
																		aria-label="Close">
																	<span aria-hidden="true" class="mdi mdi-check" style="color: #013220;"></span>
																</button>
															</td>
															<td></td>
														@elseif ($service->service_type == 2) 
															<td>
																<button type="button" class="close mark-as-read"
																		onclick="postData({{$service->id}}, 'closeorder');"
																		aria-label="Close">
																	<span aria-hidden="true" class="mdi mdi-check" style="color: #013220;"></span>
																</button>
															</td>
															<td>
																<button type="button" class="close mark-as-read"
																		onclick="postData({{$service->id}}, 'deleteorder');"
																		aria-label="Close">
																	<span aria-hidden="true" class="mdi mdi-delete" style="color: #8B0000;"></span>
																</button>
															</td>
														@elseif ($service->service_type == 3)
															<td>
																<button type="button" class="close mark-as-read"
																		onclick="postData({{$service->id}}, 'closerequest');"
																		aria-label="Close">
																	<span aria-hidden="true" class="mdi mdi-check" style="color: #013220;"></span>
																</button> 
															</td>
															<td>
																<button type="button" class="close mark-as-read"
																		onclick="postData({{$service->id}}, 'closedenied');"
																		aria-label="Close">
																	<span aria-hidden="true" class="mdi mdi-delete" style="color: #8B0000;"></span>
																</button>
															</td>
														@endif
													</tr>
												@endforeach
											@else
												<p class="dropdown-item">There are no new notifications</p>
											@endif
                                        </tbody>
                                    </table>
                                </div> <!-- end table responsive-->
                            </div> <!-- collapsed end -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div>
            </div>
            <!-- end row -->
			@role('Admin|Owner')
				@if($activeModules->where('code_name', 'restaurant')->first())
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
					
					<div class="row">
						<!-- end row -->
						<div class="col-xl-12">
							<div class="card">
								<div class="card-body">
									<div class="card-widgets">
										<a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
										<a data-toggle="collapse" href="#cardCollpase5" role="button" aria-expanded="false"
										   aria-controls="cardCollpase5"><i class="mdi mdi-minus"></i></a>
										<a href="javascript: void(0);" data-toggle="remove"><i
												class="mdi mdi-close"></i></a>
									</div>
									<h4 class="header-title mb-0">Seating plan</h4>
									<div id="cardCollpase5" class="collapse pt-3 show">
										<div class="table-responsive">
											<table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
												   id="tickets-table">
												<thead>
													<tr>
														<th>Name</th>
														<th>Employee</th>
														<th>Number Customers</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													@foreach ($tables as $key => $table)
														<tr>
															<td>
																<span>
																	 {{ $table->name ?? ''}}
																</span>
															</td>
															<td>
																<span>
																	 {{ !empty($table->employees->first_name) && !empty($table->employees->surname) ? $table->employees->first_name." ".$table->employees->surname : ''}}
																</span>
															</td>
															<td>
																<span>
																	 {{ $table->number_customer ?? ''}}
																</span>
															</td>
															<td>
																<div class="btn-group dropdown">
																	<a href="#"
																	   class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
																	   data-toggle="dropdown" aria-expanded="false"><i
																			class="mdi mdi-arrange-bring-to-front"></i></a>
																	<div class="dropdown-menu dropdown-menu-right">
																		<button	class="dropdown-item" data-toggle="modal"
																				data-target="#assign-employees-modal"
																				title='Assign Employee' data-id="{{ $table->id }}">
																				<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
																				 Assign Employee
																		</button>
																		@if ( \App\Models\TableScans::getTableStatus($table->id) == 1 )
																			<button onclick="postData({{$table->id}}, 'closetable');"
																					class="dropdown-item" data-toggle="tooltip"
																					title='change Active status'>
																					<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
																				Close
																			</button>
																		@endif
																	</div>
																</div>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div> <!-- end table responsive-->
									</div> <!-- collapsed end -->
								</div> <!-- end card-body -->
							</div> <!-- end card-->
						</div>
					</div>
				@endif
            @endrole
			@include('dashboard.partials.assign_employee')
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
    <!-- App js-->
    <script src="{{ asset('js/app.min.js')}}"></script>
	<script src="{{ ('resources/js/js/app.js') }}"></script>
    <script>
		function postData(id, data) {

            if (data == 'closetable')
                location.href = "{{route('table.close', '')}}" + "/" + id;
			else if (data == 'closeservice')
				location.href = "{{route('service.close', '')}}" + "/" + id;
			else if (data == 'closerequest')
				location.href = "{{route('request.close', '')}}" + "/" + id;
			else if (data == 'closeorder')
				location.href = "{{route('order.close', '')}}" + "/" + id;
			else if (data == 'closedenied')
				location.href = "{{route('request-denied.close', '')}}" + "/" + id;
			else if (data == 'deleteorder')
				location.href = "{{route('delete.close', '')}}" + "/" + id;
        }
        (
		function () {
            "use strict";
			
			let tableID;
            $('#assign-employees-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                tableID = btnEdit.data('id');
                let modal = $(this);
            });
						 
			 $('#assign-employee').on('click', function () {
				let com = 'table';
                let strUrl = '/restaurant/assign/employee/' + tableID;
                let modalID = 'assign-employees-modal';
                let objData = {
                    employee_id: $('#' + modalID).find('#employee_id').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };

                let submitBtnID = 'assign-employee';
                let redirectUrl = '{{route('home')}}';
                let successMsgTitle = 'Changes Saved!';
                let successMsg = 'The Record has been updated successfully.';
                let Method = 'Post';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
			
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

            //Launch counter for running services
            @foreach($services as $service)
				increment({{ $service->id }});
            @endforeach
			
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
		
		function increment(taskID) {
			if (running[taskID] == 1) {
				setTimeout(function () {
					time[taskID]++;
					var hours = Math.floor(time[taskID] / 10 / 60 / 60) % 60;
					var mins = Math.floor(time[taskID] / 10 / 60) % 60;
					var secs = Math.floor(time[taskID] / 10) % 60;
					var tenths = time[taskID] % 10;
					
					if (hours < 10) {
						hours = "0" + hours;
					}
					if (mins < 10) {
						mins = "0" + mins;
					}
					if (secs < 10) {
						secs = "0" + secs;
					}

					// Update the display
					$('#' + taskID + 'stopWatchDisplay').val(hours + ":" + mins + ":" + secs);
					/*normal_mins']     $data[''] $data['critical_mins*/
					// Change color with timer

					if (mins < {{$normal_mins}}) {
						$('.' + taskID + 'trElement').css('background-color', "{{$normal}}"); // dark Green
					} else if (mins >= {{$normal_mins}} && mins < {{$moderate_mins}}) {
						$('.' + taskID + 'trElement').css('background-color', "{{$moderate}}"); // orange
					} else if (mins >= {{$moderate_mins}} && mins < {{$critical_mins}}) {
						$('.' + taskID + 'trElement').css('background-color', "{{$critical}}"); // dark red 
					}
					else
					{
						$('.' + taskID + 'trElement').css('background-color', "{{$critical}}"); // dark red
					}

					increment(taskID);
				}, 100);
			}
		}

    </script>
@stop
