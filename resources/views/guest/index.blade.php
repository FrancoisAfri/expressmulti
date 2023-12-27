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
@endsection
<!-- Begin page -->

@section('content')

    @section('content_data')
        <div class="container-fluid">
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
						<div class="row align-items-center">
							<div class="col">
								<h5 class="mb-1 mt-2 text-white font-16">Arrived At: </h5>
								<h5 class="mb-1 mt-2 text-white font-16">Counter: </h5>
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
								<div class="table-responsive">
									<table class="table table-centered table-borderless mb-0">
										<thead class="thead-light">
											<tr>
												<th>Project Name</th>
												<th>Start Date</th>
												<th>Due Date</th>
												<th>Team</th>
												<th>Status</th>
												<th>Clients</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>App design and development</td>
												<td>Jan 03, 2015</td>
												<td>Oct 12, 2018</td>
												<td>
													<div class="avatar-group">
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mat Helme">
															<img src="../assets/images/users/user-1.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Michael Zenaty">
															<img src="../assets/images/users/user-2.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="James Anderson">
															<img src="../assets/images/users/user-3.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Username">
															<img src="../assets/images/users/user-5.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
													</div>
												</td>
												<td><span class="badge bg-soft-info text-info p-1">Work in Progress</span></td>
												<td>Halette Boivin</td>
											</tr>
											<tr>
												<td>Coffee detail page - Main Page</td>
												<td>Sep 21, 2016</td>
												<td>May 05, 2018</td>
												<td>
													<div class="avatar-group">
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="James Anderson">
															<img src="../assets/images/users/user-3.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mat Helme">
															<img src="../assets/images/users/user-4.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Username">
															<img src="../assets/images/users/user-5.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
													</div>
												</td>
												<td><span class="badge bg-soft-warning text-warning p-1">Pending</span></td>
												<td>Durandana Jolicoeur</td>
											</tr>
											<tr>
												<th>Poster illustation design</th>
												<td>Mar 08, 2018</td>
												<td>Sep 22, 2018</td>
												<td>
													<div class="avatar-group">
														
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Michael Zenaty">
															<img src="../assets/images/users/user-2.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mat Helme">
															<img src="../assets/images/users/user-6.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Username">
															<img src="../assets/images/users/user-7.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
													</div>
												</td>
												<td><span class="badge bg-soft-success text-success p-1">Completed</span></td>
												<td>Lucas Sabourin</td>
											</tr>
											<tr>
												<td>Drinking bottle graphics</td>
												<td>Oct 10, 2017</td>
												<td>May 07, 2018</td>
												<td>
													<div class="avatar-group">
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mat Helme">
															<img src="../assets/images/users/user-9.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Michael Zenaty">
															<img src="../assets/images/users/user-10.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="James Anderson">
															<img src="../assets/images/users/user-1.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
													</div>
												</td>
												<td><span class="badge bg-soft-info text-info p-1">Work in Progress</span></td>
												<td>Donatien Brunelle</td>
											</tr>
											<tr>
												<td>Landing page design - Home</td>
												<td>Coming Soon</td>
												<td>May 25, 2021</td>
												<td>
													<div class="avatar-group">
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Michael Zenaty">
															<img src="../assets/images/users/user-5.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="James Anderson">
															<img src="../assets/images/users/user-8.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mat Helme">
															<img src="../assets/images/users/user-2.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
				
														<a href="javascript: void(0);" class="avatar-group-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Username">
															<img src="../assets/images/users/user-7.jpg" class="rounded-circle avatar-xs" alt="friend">
														</a>
													</div>
												</td>
												<td><span class="badge bg-soft-dark text-dark p-1">Coming Soon</span></td>
												<td>Karel Auberjo</td>
											</tr>

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

							<div id="cardCollpase6" class="collapse pt-3 show">
								<div class="table-responsive">
									<table class="table table-hover table-centered mb-0">
										<thead>
											<tr>
												<th>Product Name</th>
												<th>Price</th>
												<th>Quantity</th>
												<th>Amount</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>ASOS Ridley High Waist</td>
												<td>$79.49</td>
												<td>82</td>
												<td>$6,518.18</td>
											</tr>
											<tr>
												<td>Marco Lightweight Shirt</td>
												<td>$128.50</td>
												<td>37</td>
												<td>$4,754.50</td>
											</tr>
											<tr>
												<td>Half Sleeve Shirt</td>
												<td>$39.99</td>
												<td>64</td>
												<td>$2,559.36</td>
											</tr>
											<tr>
												<td>Lightweight Jacket</td>
												<td>$20.00</td>
												<td>184</td>
												<td>$3,680.00</td>
											</tr>
											<tr>
												<td>Marco Shoes</td>
												<td>$28.49</td>
												<td>69</td>
												<td>$1,965.81</td>
											</tr>
											<tr>
												<td>ASOS Ridley High Waist</td>
												<td>$79.49</td>
												<td>82</td>
												<td>$6,518.18</td>
											</tr>
											<tr>
												<td>Half Sleeve Shirt</td>
												<td>$39.99</td>
												<td>64</td>
												<td>$2,559.36</td>
											</tr>
											<tr>
												<td>Lightweight Jacket</td>
												<td>$20.00</td>
												<td>184</td>
												<td>$3,680.00</td>
											</tr>
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

							<div id="cardCollpase5" class="collapse pt-3 show">
								<div class="table-responsive">
									<table class="table table-hover table-centered mb-0">
										<thead>
											<tr>
												<th>Product Name</th>
												<th>Price</th>
												<th>Quantity</th>
												<th>Amount</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>ASOS Ridley High Waist</td>
												<td>$79.49</td>
												<td>82</td>
												<td>$6,518.18</td>
											</tr>
											<tr>
												<td>Marco Lightweight Shirt</td>
												<td>$128.50</td>
												<td>37</td>
												<td>$4,754.50</td>
											</tr>
											<tr>
												<td>Half Sleeve Shirt</td>
												<td>$39.99</td>
												<td>64</td>
												<td>$2,559.36</td>
											</tr>
											<tr>
												<td>Lightweight Jacket</td>
												<td>$20.00</td>
												<td>184</td>
												<td>$3,680.00</td>
											</tr>
											<tr>
												<td>Marco Shoes</td>
												<td>$28.49</td>
												<td>69</td>
												<td>$1,965.81</td>
											</tr>
											<tr>
												<td>ASOS Ridley High Waist</td>
												<td>$79.49</td>
												<td>82</td>
												<td>$6,518.18</td>
											</tr>
											<tr>
												<td>Half Sleeve Shirt</td>
												<td>$39.99</td>
												<td>64</td>
												<td>$2,559.36</td>
											</tr>
											<tr>
												<td>Lightweight Jacket</td>
												<td>$20.00</td>
												<td>184</td>
												<td>$3,680.00</td>
											</tr>
										</tbody>
									</table>
								</div> <!-- end table responsive-->
							</div> <!-- collapsed end -->
						</div> <!-- end card-body -->
					</div> <!-- end card-->
				</div>
				<!-- end col -->
			</div>
						
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
    <script>

        document.querySelectorAll('#phone_number ,#cell_number ,#contact_number').forEach(item => {
            window.intlTelInput(item, {
                initialCountry: "auto",
                geoIpLookup: function (success, failure) {
                    $.get("https://ipinfo.io", function () {
                    }, "jsonp").always
                    (
                        function (resp) {
                            let countryCode = (resp && resp.country) ? resp.country : "ZA";
                            success(countryCode);
                        }
                    );
                },
                autoHideDialCode: true,
                nationalMode: false,
                autoPlaceholder: 'aggressive',
                hiddenInput: "fullContactNo",
                utilsScript: "/js/utils.js",
            });
        })

    </script>
@endsection
