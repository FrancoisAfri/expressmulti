@extends('layouts.main-layout')

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
    <link  href="{{ asset('libs/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css">
@endsection

{{-- Page content --}}
@section('content')

    @section('content_data')
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="card-box">
                    <div class="tab-content">
                        <div>
                            <form class="needs-validation" novalidate method="Post" action="/restaurant/update/category/{{$category->id}}"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Edit Category</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
											<label for="name">Name <span class="text-danger">*</span></label>
											<input type="text" class="form-control"
												   id="name" name="name" value="{{ $category->name ?? ''}}" placeholder="Enter Name" required>

											<div class="invalid-feedback">
												Please provide Name.
											</div>
                                        </div>
                                    </div>
									<div class="form-group mb-6">
										<label for="validationCustom04">Image</label>
										<div class="mt-3">
											<input type="file" name="image"
												   id="image" data-plugins="dropify"
												   @if(!empty($category->image))
													   data-default-file="{{ asset('Images/categories/'.$category->image) }}"/>
													@endif
													@if(!empty($category->image))
														<button type="button" onclick="postData({{$category->id}}, 'delete');"class="btn btn-danger waves-effect waves-light mt-2"><i
															class="mdi mdi-content-save"></i> Delete Image
														</button>
													@endif
											<p class="text-muted text-center mt-2 mb-0"><strong> Allowed filetypes are jpg, jpeg, png.</strong></p>
										</div>
									</div>
                                </div> <!-- end row -->
                            </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success waves-effect waves-light mt-2"><i
                                            class="mdi mdi-content-save"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- end settings content-->

                    </div> <!-- end tab-content -->
                </div> <!-- end card-box-->

            </div> <!-- end col -->
        </div>
        <!-- end row-->
    @endsection
@stop
<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->

@section('page_script')
    <!-- third party js -->

    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- Plugins js -->
    <script src="{{ asset('libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('libs/autonumeric/autoNumeric-min.js') }}"></script>
    <script src="{{ asset('js/pages/form-masks.init.js') }}"></script>

    {{--    <script src=" {{ asset('js/pages/form-validation.init.js') }}"></script>--}}
    <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>

    <!-- Init js-->
    <script src="{{ asset('js/pages/form-fileuploads.init.js') }}"></script>

    <!-- Init js-->
    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>


    <script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>

    <script src="{{ asset('libs/intl-tel-input/build/js/intlTelInput.js') }}"></script>

    <script>
		function postData(id, data) {

            if (data == 'delete')
                location.href = "{{route('category.delete-image', '')}}" + "/" + id;
        }
        document.querySelectorAll('#phone ,#cell_number').forEach(item => {
            window.intlTelInput(item, {
                initialCountry: "auto",
                geoIpLookup: function (success, failure) {
                    $.get("https://ipinfo.io", function () {
                    }, "jsonp").always
                    (
                        function (resp) {
                            let countryCode = (resp && resp.country) ? resp.country : "us";
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
