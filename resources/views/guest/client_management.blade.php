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
            <form class="needs-validation" novalidate method="Post" action="/client/client_registration"
                  enctype="multipart/form-data">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h1>Fix bellow errors before continuing</h1>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    {{ csrf_field() }}
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h5 class="text-uppercase bg-light p-2 mt-0 mb-3"><i class="mdi mdi-account-circle mr-1">
                                </i> Company Details
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name"
                                               name="name" placeholder="Enter Name" required>
                                        <div class="invalid-feedback">
                                            Please provide Name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span> </label>
                                        <input type="email" required parsley-type="email" class="form-control"
                                               id="inputEmail3" name="email" placeholder="Enter Email">
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div><!-- end col -->
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="trading_as">Trading As <span class="text-danger"></span></label>
                                        <input type="text" class="form-control" id="trading_as"
                                               name="trading_as" placeholder="Enter Trading As">
                                        <div class="invalid-feedback">
                                            Please provide Trading_ As Name.
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vat">Vat <span class="text-danger"></span></label>
                                        <input type="text" class="form-control" id="vat"
                                               name="vat" placeholder="Enter Vat">
                                        <div class="invalid-feedback">
                                            Please provide VAT.
                                        </div>
                                    </div>
                                </div><!-- end col -->
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label> Phone Number </label>
                                        <br>
                                        <input type="text" class="form-control" id="phone_number" maxlength="15"
                                               name="phone_number" value="" placeholder="Enter Phone Number">
                                        <div class="invalid-feedback">
                                            Please provide Phone Number.
                                        </div>
                                    </div>
                                </div>
								<div class="col-lg-6">
                                    <div class="form-group">
                                        <label> Cell Number <span class="text-danger">*</span></label>
                                        <br>
                                        <input type="text" class="form-control" id="cell_number" maxlength="15"
                                               name="cell_number" value="" placeholder="Enter Cell Number" required>
                                        <div class="invalid-feedback">
                                            Please provide Cell Number.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="res_address">Physical Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="res_address"
                                               name="res_address" value="" placeholder="Enter Physical Address" required>

                                        <div class="invalid-feedback">
                                            Please provide Physical Address.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="post_address">Postal Address </label>
                                        <input type="text" class="form-control" id="post_address" name="post_address"
                                               value="" placeholder="Enter Postal Address">

                                        <div class="invalid-feedback">
                                            Please provide Postal Address.
                                        </div>

                                    </div>
                                </div> <!-- end col -->
                            </div>
                        </div> <!-- end card-box -->
                    </div> <!-- end col -->
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Contact Person </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name"
                                               name="first_name" placeholder="Enter First name" required>
                                        <div class="invalid-feedback">
                                            Please provide First Name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="surname">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="surname"
                                               name="surname" placeholder="Enter Surname" required>
                                        <div class="invalid-feedback">
                                            Please provide Surname.
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="contact_number"
                                               name="contact_number" value=""
                                               placeholder="Enter Contact Number" maxlength="15" required>
                                        <div class="invalid-feedback">
                                            Please provide Contact Number.
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label> Contact Email <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="contact_email"
                                               name="contact_email" value=""
                                               placeholder="Enter Email" required>
                                        <div class="invalid-feedback">
                                            Please provide Contact email.
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                        </div>
						<div class="card-box">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Subscription Type </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Packages </label>
										<select class="form-control select2-multiple" name="package_id"
											  id="package_id"  data-toggle="select2"
												data-placeholder="Choose ...">
											<option value="">Select a Package ...</option>
											@foreach($packages as $package)
												<option
													value="{{ $package->id }}">{{ $package->package_name." | ". $package->no_table." "."Tables"." | "."R".$package->price  }}
												</option>
											@endforeach
										</select>
                                    </div>
                                </div>

                            </div>
                         </div>
                    </div> <!-- end col-->
                </div>
                <div class="text-center mb-3">
                    <button type="submit" id="saveButton" class="btn w-sm btn-success waves-effect waves-light">Save
                    </button>
                </div>
            </form>
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
		document.querySelector('form').addEventListener('submit', function(e) {
			var form = this;

			// If form is not valid, don't disable the button
			if (!form.checkValidity()) {
				// Allow the browser to show the validation messages
				return;
			}

			var saveButton = document.getElementById('saveButton');
			saveButton.disabled = true;
			saveButton.innerHTML = 'Saving...'; // Optional: change the button text while saving
		});
    </script>
@endsection
