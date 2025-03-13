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
                    <div class="card">
                        <div class="card-body">
                            <div class="card-widgets">
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
			@role('Admin|Owner')
				@if($activeModules->where('code_name', 'restaurant')->first())
					<div class="row">
						<!-- end row -->
						<div class="col-xl-12">
							<div class="card">
								<div class="card-body">
									<div class="card-widgets">
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
														<th>Status</th>
														<th>Action</th>
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
																@if (!empty(\App\Models\HRPerson::getWaiterStatus($table->employee_id)) && \App\Models\HRPerson::getWaiterStatus($table->employee_id) == 1)
																	<span style="display: inline-block;width: 15px; height: 15px;  border-radius: 50%; background-color: green; margin-left: 5px;"></span>
																@else
																	<span style="display: inline-block;width: 15px; height: 15px;  border-radius: 50%; background-color: gray; margin-left: 5px;"></span>
																@endif
															</td>
															<td>
																<span>
																	 {{ $table->number_customer ?? ''}} 
																</span>
															</td>
															<td>
																<span>
																	 {{ !empty(\App\Models\TableScans::getTableStatus($table->id)) ? 'Open' : 'Closed'}}
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
																				title='Assign Waiter' data-id="{{ $table->id }}">
																				<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
																				 Assign Waiter
																		</button>
																		<button onclick="postData({{$table->id}}, 'removewaiter');"
																					class="dropdown-item" data-toggle="tooltip"
																					title='Remove Waiter'>
																					<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
																				Remove Waiter
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
    
	
	<script src="{{ asset('js/ion.sound.js') }}"></script>
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
			else if (data == 'removewaiter')
				location.href = "{{route('remove.waiter', '')}}" + "/" + id;
        }
        
		(
		function () {
			// Call the function initially
			//checkAndUpdateTable();
			
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
			
			// Use setInterval to call the function periodically
			//setInterval(checkAndUpdateTable, 10000); // Check every 10 seconds
			setInterval(function () {
				
                fetch('{{ route('service.check') }}')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Handle the response from the server

                        if (data.table_updated === true) {

                            console.log('refreshed');
                            // Reload the table data

                            ion.sound({
                                sounds: [
                                    {
                                        name: "bell_ring"
                                    }
                                ],
                                volume: 0.5,
                                path: "/sounds/",
                                preload: true
                            });

                            ion.sound.play("bell_ring");
                            window.location.reload();
                            // Add sound or any other actions upon table update
                        }

                    })
                    .catch(error => {
                        // Handle errors
                        console.error('Error:', error);
                    });
            }, 10000); // Check every 30 second
        })();

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
