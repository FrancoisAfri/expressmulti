@extends('layouts.main-layout')

@section('page_dependencies')

    <link href="{{ asset('libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
          rel="stylesheet') }}" type="text/css"/>
    <link href="{{ asset('libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet') }}"
          type="text/css"/>
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
            <form class="needs-validation" novalidate method="Post"
                  action="{{ route('client_details.update', $client->id) }}" enctype="multipart/form-data">
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
                    {{ method_field('PATCH') }}
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h5 class="text-uppercase bg-light p-2 mt-0 mb-3"><i class="mdi mdi-account-circle mr-1">
                                </i> Company Info
                            </h5>
                            <div class="row">
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name"
                                               name="name" placeholder="Enter Name" 
											   value="{{ $client->name ?? '' }}" required>
                                        <div class="invalid-feedback">
                                            Please provide Name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span> </label>
                                        <input type="email" required parsley-type="email" class="form-control"
                                               id="inputEmail3" name="email" 
											   value="{{ $client->email ?? '' }}" placeholder="Enter Email">
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div><!-- end col -->
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="trading_as">Trading As <span class="text-danger"></span></label>
                                        <input type="text" class="form-control" id="trading_as"
                                               name="trading_as" value="{{ $client->name ?? '' }}" placeholder="Enter Trading As">
                                        <div class="invalid-feedback">
                                            Please provide Trading_ As Name.
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vat">Vat <span class="text-danger"></span></label>
                                        <input type="text" class="form-control" id="vat"
                                               name="vat" value="{{ $client->vat ?? '' }}" placeholder="Enter Vat">
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
                                               name="phone_number" value="{{ $client->phone_number ?? '' }}" 
											   placeholder="Enter Phone Number">
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
                                               name="cell_number" value="{{ $client->cell_number ?? '' }}" placeholder="Enter Cell Number" required>
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
                                        <label for="res_address">Residential Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="res_address"
                                               name="res_address" value="{{ $client->res_address ?? '' }}" placeholder="Enter Residential Address" required>

                                        <div class="invalid-feedback">
                                            Please provide Residential Address.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="post_address">Postal Address </label>
                                        <input type="text" class="form-control" id="post_address" name="post_address"
                                               value="{{ $client->post_address ?? '' }}" placeholder="Enter Postal Address">

                                        <div class="invalid-feedback">
                                            Please provide Postal Address.
                                        </div>

                                    </div>
                                </div> <!-- end col -->
                            </div>
							<br>
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
													value="{{ $package->id }}" {{ ($package->id == $client->package_id) ?
                                                ' selected' : '' }}>{{ $package->package_name." | ". $package->no_table." "."Tables" }}
												</option>
											@endforeach
										</select>
                                    </div>
                                </div>
                            </div> <!-- end row -->   
                        </div> <!-- end card-box -->  
                    </div> <!-- end col -->
					<div class="col-lg-6">
                        <div class="card-box">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Logo Image</h5>
                            <div class="form-group mb-3">
                                <div class="mt-3">
                                    <input type="file" name="client_logo"
                                           id="client_logo" data-plugins="dropify"
										   @if(!empty($client->client_logo))
                                               data-default-file="{{ asset('uploads/'.$client->client_logo) }}"
											@endif
									/>
                                    <p class="text-muted text-center mt-2 mb-0">Client Logo</p>
                                </div>
                            </div>
                        </div> <!-- end col-->
                    </div> <!-- end col-->
					<div class="col-lg-12">
						<div class="card">
                            <div class="card-header bg-info py-3 text-white  user_datatable">
                                <div class="card-widgets">
                                    <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                    <a data-toggle="collapse" href="#cardCollpase9" role="button"
                                       aria-expanded="false" aria-controls="cardCollpase2"><i
                                            class="mdi mdi-minus"></i></a>
                                    <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                </div>
                                <h5 class="card-title mb-0 text-white"> Contact Persons </h5>
                            </div>
                            <br>
                            <div class="align-content-end">
                                <br>
                                <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
                                        data-toggle="modal" data-target="#add-new-contact-person-modal">
                                    <i class="mdi mdi-sort-numeric-ascending mr-2 text-muted font-18 vertical-middle"></i>
                                    Add Contact Person
                                </button>
                                <br>
                            </div>
                            <div id="cardCollpase9" class="collapse show">
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                                               id="tickets-table">
                                        <thead>
											<tr>
												<th>First Name</th>
												<th>surname</th>
												<th>Cell Number</th>
												<th>Email</th>
												<th>Action</th>
											</tr>
                                        </thead>
                                        <tbody>
											@foreach ($contactPersons as $key => $person)
												<tr>
													<td>
														{{ $person->first_name ?? ''}}
													</td>
													<td>
														{{ $person->surname ?? ''}}
													</td>
													<td>
														{{ $person->contact_number ?? ''}}
													</td>
													<td>
														{{ $person->email ?? ''}}
													</td>
													<td>
														<div class="btn-group dropdown">
															<div
																class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
																data-toggle="dropdown" aria-expanded="false"><i
																	class="mdi mdi-arrange-bring-to-front"></i></div>
															<div class="dropdown-menu dropdown-menu-right">
																<button class="dropdown-item" type="button"
																		id="delete_button" name="command"
																		onclick="if(confirm('Are you sure you want to delete this record ?'))
																		{ deleteRecord({{$person->id}})} else {return false;}"
																		value="Delete">
																	<i class="mdi mdi-delete-empty mr-2 text-muted font-18 vertical-middle delete_confirm"
																	   data-toggle="tooltip" title='Delete'></i>Delete
																</button>
															</div>
														</div>
													</td>
												</tr>
											@endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <a class="btn w-sm btn-outline-info waves-effect"
                       href="{{ URL::route('clientManagement.index') }}">
                        Back
                    </a>
                    <button type="submit" class="btn w-sm btn-success waves-effect waves-light">Save
                    </button>
                    <button type="button" class="btn w-sm btn-danger waves-effect waves-light">Delete
                    </button>
                </div>
				<br>
				<br>
            </form>
        </div>
        @include('client.partials.add_contact_person')
    @endsection
@stop

@section('page_script')
    <script src="{{ asset('libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('libs/tippy.js/tippy.all.min.js') }}"></script>
    <script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('js/pages/form-fileuploads.init.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('libs/intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('libs/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('js/custom_components/js/dataTable.js') }}"></script>
    <script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
    <script>


        $("#tickets-table").DataTable({
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            }, drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        })
        // $('[data-toggle="select2"]').select2();

        document.querySelectorAll('#contact_number ,#cell_number,#phone_number').forEach(item => {
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

        function deleteRecord(id) {
            location.href = "{{route('contact_person.destroy', '')}}" + "/" + id;
        }

        $(function () {

            $('#add-contact-person').on('click', function () {
                let strUrl = '{{ route('contact_person.store') }}';
                let modalID = 'add-new-contact-person-modal';
                let formName = 'add-contact-person-form';
                let submitBtnID = 'add-contact-person';
                let redirectUrl = '{{route('client_details.show', $client->uuid)}}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been saved successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection