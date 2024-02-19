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
	<!-- Bootstrap Css -->
      <link rel="stylesheet" href="{{ asset('libs/eatsome/vender/bootstrap/css/bootstrap.min.css') }}">
      <!-- Icofont -->
      <link rel="stylesheet" href="{{ asset('libs/eatsome/vender/icons/icofont.min.css') }}">
      <!-- Slick SLider Css -->
      <link rel="stylesheet" href="{{ asset('libs/eatsome/vender/slick/slick/slick.css') }}">
      <link rel="stylesheet" href="{{ asset('libs/eatsome/vender/slick/slick/slick-theme.css') }}">
      <!-- Font Awesome Icon -->
      <link href="{{ asset('libs/eatsome/vender/fontawesome/css/all.min.css') }}" rel="stylesheet">
      <!-- Sidebar CSS -->
      <link href="{{ asset('libs/eatsome/vender/sidebar/demo.css') }}" rel="stylesheet">
      <!-- Custom Css -->
      <link rel="stylesheet" href="{{ asset('libs/eatsome/css/style.css') }}">
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
						<div class="card">
							<div class="card-body">
								<div class="card-widgets">
									<a data-toggle="collapse" href="#cardCollpase4" role="button" aria-expanded="false" aria-controls="cardCollpase4"><i class="mdi mdi-minus"></i></a>
									<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
								</div>
								<h4 class="header-title mb-0">Menu</h4>
								
								<div id="cardCollpase4" class="collapse pt-3 show">
									<div class="bg-light">
										<form class="form-horizontal" method="get" action="{{ route('seating.plan', $table->id) }}">
											
											<div class="col-md-12">
												<div class="form-group">
													<div class="col-sm-4">
														<label>Menu Type</label>
														<select class="form-control select2 " style="width: 100%;"
															   id="type" name="type" data-select2-id="1" tabindex="-1" aria-hidden="true">
															<option value="0">*** Select Type ***</option>
															@foreach ($menuTypes as $type)
																<option value="{{ $type->id }}" {{ ($type->id == $menu_type) ? ' selected' : '' }}>{{ $type->name }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-4">
														<label>Categories</label>
														<select class="form-control select2 " style="width: 100%;"
															 id="categoty" name="categoty"   data-select2-id="1" tabindex="-1" aria-hidden="true">
															<option value="0">*** Select Category ***</option>
															@foreach ($Categories as $cat)
																<option value="{{ $cat->id }}" {{ ($cat->id == $categoty) ? ' selected' : '' }}>{{ $cat->name }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="box-footer">
													<button type="button" class="btn btn-outline-danger fw-bold text-uppercase btn-sm rounded"
															data-toggle="modal" data-target="#view-cart-modal">
														<i class="mdi mdi-sort-numeric-ascending mr-2 text-muted font-18 vertical-middle"></i>
														Cart 
													</button>
													<button type="submit" class="btn btn-primary pull-left">Search</button><br>
												</div>
											</div>
										</form>
									</div>
									<div class="tab-content" id="pills-tabContent" >
										<!-- 1st tab -->
										<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
											<!-- 1st product -->
											<div class="store-list-2">
												@if (!empty($menus))
													@foreach($menus as $menu)
														<div class="row justify-content-between">
															<div class="col-6">
																<h3 class="fw-bold mb-1">{{ $menu->name ?? '' }}</h3>
																<p class="small text-muted mb-3">{{ $menu->categories->name ?? '' }}</p>
																<p class="small text-muted mb-3">{{ $menu->menuType->name ?? '' }}</p>
																<h6 class="fw-bold">{{ (!empty($menu->price)) ? 'R ' .number_format($menu->price, 2) : ''}}</h6>
															</div>
															<div class="col-4">
																<div class="card border-0">
																	<img src="{{ asset('libs/eatsome/img/veg.jpeg') }}"
																   class="card-img-top rounded-3" alt="...">
																	<div class="card-body d-grid px-0 pt-2 pb-0">
																		<button type="button" class="btn btn-outline-danger fw-bold text-uppercase btn-sm rounded"
																		data-bs-toggle="modal" data-bs-target="#view-more-modal"
																		 data-id="{{ $menu->id }}"
																			data-name="{{ $menu->name }}"
																			data-description="{{ $menu->description }}"
																			data-price="{{ (!empty($menu->price)) ? 'R ' .number_format($menu->price, 2) : ''}}"
																			data-calories="{{ $menu->calories }}"
																			data-category="{{ $menu->categories->name }}"
																			data-type="{{ $menu->menuType->name }}"
																			data-quantity="{{ (!empty(\App\Models\cart::getQuantity($menu->id,$table->id))) ? \App\Models\cart::getQuantity($menu->id,$table->id) : 1}}"
																			data-comment="{{ (!empty(\App\Models\cart::getComment($menu->id,$table->id))) ? \App\Models\cart::getComment($menu->id,$table->id) : ''}}"
																			>
																			Add +
																		</button>
																	</div>
																</div>
															</div>
														</div>
													@endforeach
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							@include('guest.partials.add_item')
							@include('guest.partials.cart')
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-6">
						<div class="card">
							<div class="card-body">
								<div class="card-widgets">
									<a data-toggle="collapse" href="#cardCollpase6" role="button" aria-expanded="false" aria-controls="cardCollpase6"><i class="mdi mdi-minus"></i></a>
									<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
								</div>
								<h4 class="header-title mb-0">Order History</h4>

								<div id="cardCollpase6" class="collapse pt-3 show">
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
												@if (count($orders) > 0)
													@foreach($orders as $order)
														@foreach($order->products as $product)
															<tr>
																<td>{{ !empty($product->item->name) ? $product->item->name : ''}}</td>
																<td>{{ (!empty($product->price)) ? 'R ' .number_format($product->price, 2) : ''}}</td>
																<td>{{ $product->quantity ?? ''}}</td>
																<td>{{ $product->comment ?? ''}}</td>
															</tr>
														@endforeach
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
									<a data-toggle="collapse" href="#cardCollpase5" role="button" aria-expanded="false" aria-controls="cardCollpase5"><i class="mdi mdi-minus"></i></a>
									<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
								</div>
								<h4 class="header-title mb-0">Query History & Acknowledgement</h4>

								<div id="cardCollpase5" class="collapse pt-3 show">
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
									<a data-toggle="collapse" href="#cardCollpase7" role="button" aria-expanded="false" aria-controls="cardCollpase6"><i class="mdi mdi-minus"></i></a>
									<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
								</div>
								<h4 class="header-title mb-0">Customer Feedback</h4>
								<div id="cardCollpase7" class="collapse pt-3 show">
									<div class="table-responsive">
										<form action="/restaurant/rate/service/{{$scanned->id}}" method="POST">
										{{ csrf_field() }}
											<div class="box-body">
												<hr class="hr-text" data-content="TELL US ABOUT YOUR EXPERIENCE">
												<div class="form-group">
													<label for="comments" class="col-sm-2 control-label">Feedback</label>
													<div class="col-sm-10">
														<textarea name="comments" id="comments" class="form-control" rows="4" placeholder="Enter Feedback"></textarea>
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
	<script src="{{ asset('libs/eatsome/vender/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('libs/eatsome/vender/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<!-- slick Slider JS-->
	<script src="{{ asset('libs/eatsome/vender/slick/slick/slick.min.js') }}"></script>
	<!-- Sidebar JS-->
	<script src="{{ asset('libs/eatsome/vender/sidebar/hc-offcanvas-nav.js') }}"></script>
	<!-- Javascript -->
	<script src="{{ asset('libs/eatsome/js/custom.js') }}"></script>
	<script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
    <script>
	
		$(function () {
			// get view more modal
			let menuID;
            $('#view-more-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                menuID = btnEdit.data('id');
				let name = btnEdit.data('name');
				let description = btnEdit.data('description');
				let price = btnEdit.data('price');
				let category = btnEdit.data('category');
				let type = btnEdit.data('type');
				let quantity = btnEdit.data('quantity');
				let comment = btnEdit.data('comment');
				
				let calories = btnEdit.data('calories');
                let modal = $(this);
				$('#name').html(name);
				$('#description').html(description);
				$('#price').html(price);
				$('#category').html(category);
				$('#menu_type').html(type);
				$('#calories').html(calories + ' Calories');
				$('#comment').html(comment);
				modal.find('#quantity').val(quantity);
            });
			// add item to cart
			$('#add-item-cart').on('click', function () {
                let strUrl = '/restaurant/add-cart/{{$table->id}}/' + menuID;
                let modalID = 'view-more-modal';
                let formName = 'add-cart-form';
                let submitBtnID = 'add-item-cart';
                let redirectUrl = '{{route('seating.plan', $table->id) }}';
                let successMsgTitle = 'Item Saved!';
                let successMsg = 'The item has been added successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			/// place order
			/*$('#place-order-cart').on('click', function () {
                let strUrl = '/restaurant/place-order/{{$table->id}}/';
                let modalID = 'view-cart-modal';
                let formName = 'cart-form';
                let submitBtnID = 'place-order-cart';
                let redirectUrl = "restaurant/seating_plan/{{$table->id}}";
                let successMsgTitle = 'Order Saved!';
                let successMsg = 'Your order have been submitted successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			*/
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
					//document.getElementById("stopWatchDisplay").innerHTML = mins + ":" + secs + ":" + "0" + tenths; + "." + tenths
					$('#stopWatchDisplay').val(hours + ":" + mins + ":" + secs);
					increment(taskID);
				}, 100);
			}
		}
    </script>
@endsection