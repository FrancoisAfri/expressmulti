@extends('layouts.main-guest')

@section('page_dependencies')

    <link href="{{ asset('libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('libs/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/summernote/summernote-bs4.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css">
	<link href="https://unpkg.com/cloudinary-video-player@1.9.0/dist/cld-video-player.min.css" rel="stylesheet">
    <link href="{{ asset('libs/kartik-v-bootstrap-star-rating-3642656/css/star-rating.css') }}" media="all"  rel="stylesheet" type="text/css">
    <link href="{{ asset('libs/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.css') }}" media="all"  rel="stylesheet" type="text/css">
@endsection
<!-- Begin page -->

@section('content')

    @section('content_data')
        <div class="container-fluid">
			@if (!empty($localName))
				<div class="row">
					<div class="col-md-6 col-xl-3">
						<div class="widget-rounded-circle card-box">
							<div class="row align-items-center">
								<div class="col">
									<h5 class="mb-1 mt-2 font-16">{{$tableDetails->name}}</h5>
									<p class="mb-2 text-muted">{{$tableDetails->number_customer}} Seat(s)</p>
									<input type="submit" id="submit-review" name="submit-review" class="btn btn-primary btn-flat pull-right" value="Save">
								</div>
							</div> <!-- end row-->
						</div> <!-- end widget-rounded-circle-->
					</div>
					<div class="col-md-6 col-xl-3">
						<div class="widget-rounded-circle card-box bg-blue">
							<script>
								var time = 0;
								var running = 0;
							</script>
							<div class="row align-items-center">
							<script>
								running = 1;
								time = {{ (time() - $scanned->scan_time)  * 10}};
								console.log(time);
							</script>
								<div class="col">
									<h5 class="mb-1 mt-2 text-white font-16">Arrived At: {{date('Y-m-d H-s-i', $scanned->scan_time)}}</h5>
									<h5 class="mb-1 mt-2 text-white font-16">Counter: <input type="text" id="stopWatchDisplay" style="font-weight:bold; font-family:cursive; width: 120px; height: 23px;" value="" class="input-sm" disabled></h5>
								</div>
							</div> <!-- end row-->
						</div> <!-- end widget-rounded-circle-->
					</div> <!-- end col-->
					<div class="col-md-6 col-xl-3">
						<div class="widget-rounded-circle card-box">
							<div class="row align-items-center">
								<div class="col-auto">
									<div class="avatar-lg">
										<img src="{{$profilePic}}" class="img-fluid rounded-circle" alt="user-img">
									</div>
								</div>
								<div class="col">
									<h5 class="mb-1 mt-2 font-16">{{ !empty($tableDetails->employees->first_name) && !empty($tableDetails->employees->surname) ? $tableDetails->employees->first_name.' '.$tableDetails->employees->surname : ''}}</h5>
									<p class="mb-2 text-muted">Waiter</p>
								</div>
							</div> <!-- end row-->
						</div> <!-- end widget-rounded-circle-->
					</div>
					<div class="col-md-6 col-xl-3">
						<div class="widget-rounded-circle card-box">
							<div class="row align-items-center">
								<div class="col-auto">
									<div class="avatar-lg">
										<img src="{{$avatar}}" class="img-fluid rounded-circle" alt="user-img">
									</div>
								</div>
								<div class="col">
									<h5 class="mb-1 mt-2 font-16">{{!empty($manager->first_name) && !empty($manager->surname) ? $manager->first_name.' '.$manager->surname : ''}}</h5>
									<p class="mb-2 text-muted">Manager</p>
								</div>
							</div> <!-- end row-->
						</div> <!-- end widget-rounded-circle-->
					</div>
					<!-- end col-->
				</div>
				<div class="row">
					<div class="col-12">
						<!-- Portlet card -->
						<div class="card">
							<div class="card-body">
								<div class="card-widgets">
									<a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
									<a data-toggle="collapse" href="#cardCollpase4" role="button" aria-expanded="false" aria-controls="cardCollpase4"><i class="mdi mdi-minus"></i></a>
									<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
								</div>
								<h4 class="header-title mb-0">Menu</h4>
								<div id="cardCollpase4" class="collapse pt-3 show">
									<div class="table-responsive" style="height:500px; overflow-y: scroll;">
										<table class="table table-centered table-borderless mb-0">
											<thead class="thead-light">
												<tr>
													<th></th>
													<th></th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												@if (!empty($menus))
													@foreach($menus as $menu)
														<tr>
															<td>
																<div class="popup-thumbnail img-responsive">
																	<img src="{{ asset('storage/assets/Images/'.$menu->image) }} "
																		 height="35px" width="40px" alt="Image">
																</div>
															</td>
															<td>{{ $menu->name ?? ''}}
															<br>{{ (!empty($menu->price)) ? 'R ' .number_format($menu->price, 2) : ''}}
															<br>{{ (!empty($menu->ingredients)) ? $menu->ingredients : ''}}
															<br>{{ (!empty($menu->calories)) ? $menu->calories : ''}} Calories
															</td>
															<td>
															
															</td>
														</tr>
													@endforeach
												@endif
											</tbody>
										</table>
									</div> <!-- .table-responsive -->
								</div> <!-- end collapse-->
							</div> <!-- end card-body-->
						</div> <!-- end card-->
					</div> <!-- end col-->
				</div>
				<div class="row">
					<div class="col-xl-6">
						<div class="card">
							<div class="card-body">
								<div class="card-widgets">
									<a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
									<a data-toggle="collapse" href="#cardCollpase6" role="button" aria-expanded="false" aria-controls="cardCollpase6"><i class="mdi mdi-minus"></i></a>
									<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
								</div>
								<h4 class="header-title mb-0">Order History</h4>

								<div id="cardCollpase6" class="collapse pt-3 hide">
									<div class="table-responsive">
										<table class="table table-hover table-centered mb-0">
											<thead>
												<tr>
													<th>Name</th>
													<th>Price</th>
													<th>Quantity</th>
													<th>Comment</th>
												</tr>
											</thead>
											<tbody>
												@if (!empty($orders))
													@foreach($orders as $order)
														<tr>
															<td>{{ $order->products->items->name ?? ''}}</td>
															<td>{{ (!empty($order->products->price)) ? 'R ' .number_format($order->products->price, 2) : ''}}</td>
															<td>{{ $order->products->quantity ?? ''}}</td>
															<td>{{ $order->products->comment ?? ''}}</td>
														</tr>
													@endforeach
												@endif
											</tbody>
										</table>
									</div> <!-- end table responsive-->
								</div> <!-- collapsed end -->
							</div> <!-- end card-body -->
						</div> <!-- end card-->
					</div>
					<div class="col-xl-6">
						<div class="card">
							<div class="card-body">
								<div class="card-widgets">
									<a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
									<a data-toggle="collapse" href="#cardCollpase5" role="button" aria-expanded="false" aria-controls="cardCollpase5"><i class="mdi mdi-minus"></i></a>
									<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
								</div>
								<h4 class="header-title mb-0">Query History & Acknowledgement</h4>

								<div id="cardCollpase5" class="collapse pt-3 hide">
									<div class="table-responsive">
										<table class="table table-hover table-centered mb-0">
											<thead>
												<tr>
													<th>Service</th>
													<th>Requested</th>
													<th>Acknowledged</th>
												</tr>
											</thead>
											<tbody>
												@if (!empty($ordersServices))
													@foreach($ordersServices as $service)
														<tr>
															<td>{{ $service->services->name ?? ''}}</td>
															<td>{{ $service->created_at ?? ''}}</td>
															<td>{{ $service->updated_at ?? ''}}</td>
														</tr>
													@endforeach
												@endif
											</tbody>
										</table>
									</div> <!-- end table responsive-->
								</div> <!-- collapsed end -->
							</div> <!-- end card-body -->
						</div> <!-- end card-->
					</div>
					<!-- end col -->
				</div>
				<div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-body">
								<div class="card-widgets">
									<a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
									<a data-toggle="collapse" href="#cardCollpase7" role="button" aria-expanded="false" aria-controls="cardCollpase6"><i class="mdi mdi-minus"></i></a>
									<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
								</div>
								<h4 class="header-title mb-0">Customer Feedback</h4>
								<div id="cardCollpase7" class="collapse pt-3 hide">
									<div class="table-responsive">
										<form action="/restaurant/rate/service/{{$scanned->id}}" method="POST">
										{{ csrf_field() }}
											<div class="box-body">
												<hr class="hr-text" data-content="TELL US ABOUT YOUR EXPERIENCE">
												<div class="form-group">
													<label for="additional_comments" class="col-sm-2 control-label">Comments</label>
													<div class="col-sm-10">
														<textarea name="comments" id="comments" class="form-control" rows="4"></textarea>
													</div>
												</div>
											</div>
											<div class="box-footer">
												<input type="submit" id="submit-review" name="submit-review" class="btn btn-primary btn-flat pull-right" value="Submit Feedback">
											</div>
										</form>
									</div> <!-- end table responsive-->
								</div> <!-- collapsed end -->
							</div> <!-- end card-body -->
						</div> <!-- end card-->
					</div>
				</div>	
			@else
				<div class="row">
				<div class="col-xl-12">
					<div class="card">
						<div class="card-body">
							<div class="card-widgets">
								<a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
								<a data-toggle="collapse" href="#cardCollpase7" role="button" aria-expanded="false" aria-controls="cardCollpase7"><i class="mdi mdi-minus"></i></a>
								<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
							</div>
							<h4 class="header-title mb-0">Fill in the form</h4>
							<div id="cardCollpase7" class="collapse pt-3 show">
								<div class="table-responsive">
									<form action="/restaurant/add-table-name/{{$scanned->id}}" method="POST">
									{{ csrf_field() }}
										<div class="box-body">
											<div class="form-group">
												<label for="table_name" class="col-sm-2 control-label"></label>
												<div class="col-sm-10">
												<input type="text" name="nickname" id="nickname" value="" class="form-control"  placeholder="Please Enter Your Name">
												</div>
											</div>
										</div>
										<div class="box-footer">
											<input type="submit" id="submit-review" name="submit-review" class="btn btn-primary btn-flat pull-right" value="Save">
										</div>
									</form>
								</div> <!-- end table responsive-->
							</div> <!-- collapsed end -->
						</div> <!-- end card-body -->
					</div> <!-- end card-->
				</div>
			</div>
			@endif
        </div>
        <br><br>
    @endsection
@stop

@section('page_script')

    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- Plugins js -->
    <script src="{{ asset('libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('libs/autonumeric/autoNumeric-min.js') }}"></script>
    <script src="{{ asset('js/pages/form-masks.init.js') }}"></script>
    <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('libs/tippy.js/tippy.all.min.js') }}"></script>
    <script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('js/pages/form-fileuploads.init.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('libs/intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('libs/iCheck/icheck.min.js') }}"></script>
	<script src="https://unpkg.com/cloudinary-video-player@1.9.0/dist/cld-video-player.min.js"
            type="text/javascript"></script>
    <script src="{{ asset('libs/kartik-v-bootstrap-star-rating-3642656/js/star-rating.js') }}" type="text/javascript"></script>
    <script src="{{ asset('libs/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.js') }}"></script>
    <script>
		$(function () {
			
            //Launch counter for running tasks
            increment();
        });
		function increment(taskID) {
			if (running == 1) {
				setTimeout(function() {
					time++;
					var hours = Math.floor(time / 10 / 60 / 60) % 60;
					var mins = Math.floor(time/ 10 / 60) % 60;
					var secs = Math.floor(time / 10) % 60;
					var tenths = time % 10;

					if (hours < 10) {
						hours = "0" + hours;
					}
					if (mins < 10) {
						mins = "0" + mins;
					}
					if (secs < 10) {
						secs = "0" + secs;
					}
					//document.getElementById("stopWatchDisplay").innerHTML = mins + ":" + secs + ":" + "0" + tenths;
					$('#stopWatchDisplay').val(hours + ":" + mins + ":" + secs + "." + tenths);
					increment(taskID);
				}, 100);
			}
		}
    </script>
@endsection
