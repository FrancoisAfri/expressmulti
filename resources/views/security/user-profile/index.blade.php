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
            <div class="col-lg-4 col-xl-4">
                <div class="card-box text-center">
                    <img src="{{ $avatar }}" class="rounded-circle avatar-lg img-thumbnail"
                         alt="profile-image">

                    <h4 class="mb-0"> {{ $user->person->first_name . ' ' . $user->person->surname ?? '' }}</h4>
                    <p class="text-muted"> {{ $user->person->email }}</p>
                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">About Me :</h4>
                        <p class="text-muted font-13 mb-3">
                            {{ $user->person->bio ?? '' }}
                        </p>
                        <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ml-2">
                                        {{ $user->person->first_name . ' ' . $user->person->surname ?? '' }}
                                    </span></p>

                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ml-2">
                                    {{ $user->person->cell_number ?? '' }}
                                    </span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span class="ml-2 ">
                                        {{ $user->person->email ?? '' }}
                                    </span></p>

                        <p class="text-muted mb-1 font-13"><strong> City :</strong> <span
                                class="ml-2">{{ $user->person->res_city }}</span></p>
                    </div>
                </div> <!-- end card-box -->
            </div> <!-- end col-->
            <div class="col-lg-8 col-xl-8">
                <div class="card-box">

                    <div class="tab-content">

                        <div>
                            <form class="needs-validation" novalidate method="Post" action="{{ route('user_profile.store') }}"
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


                                <input type="hidden" name="user_id" value="{{ $user->id }}">

                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal
                                    Info</h5>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">

                                            <label for="firstname">First Name <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="first_name"
                                                   name="first_name" value="{{ $user->person->first_name }}"
                                                   placeholder="Enter first name" required>

                                            <div class="invalid-feedback">
                                                Please provide First Name.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">

                                            <label for="firstname">Initial <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="initial"
                                                   name="initial" value="{{ $user->person->initial }}"
                                                   placeholder="Enter initial" required>

                                            <div class="invalid-feedback">
                                                Please provide initials.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="firstname">Last Name <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="surname"
                                                   name="surname" value="{{ $user->person->surname }}"
                                                   placeholder="Enter last name" required>

                                            <div class="invalid-feedback">
                                                Please provide First Name.
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">Cell Number <span class="text-danger">*</span> </label>
                                            <br>
                                            <input type="text" class="form-control" id="cell_number"
                                                   name="cell_number" value="{{ $user->person->cell_number }}"
                                                   placeholder="Enter Cell Number" required>

                                            <div class="invalid-feedback">
                                                Please provide Cell Number.
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
{{--                                            <label for="id_number">ID Number</label>--}}
                                            <label for="firstname">ID Number <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="id_number"
                                                   value="{{  $user->person->id_number ?? '' }}" name="id_number"
                                                   placeholder="Enter ID Number">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">Passport Number <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="passport_number"
                                                   name="passport_number"
                                                   value="{{  $user->person->passport_number ?? '' }}"
                                                   placeholder="Enter Passport Number (optional)">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <label for="firstname">Date of Birth <span class="text-danger">*</span> </label>
                                            <input type="text"  name="date_of_birth"
                                                   value="{{ ($user->person->date_of_birth) ? date('d/m/Y', strtotime($user->person->date_of_birth)) : '' }}"
                                                   class="form-control human" placeholder="October 9, 2018">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">Email <span class="text-danger">*</span> </label>
                                            <input type="email" class="form-control" id="email"
                                                   name="email" value="{{ $user->person->email }}"
                                                   placeholder="Enter email" required>
                                            <div class="invalid-feedback">
                                                Please provide Email.
                                            </div>
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender"> Gender </label>
                                            <select id="gender" name="gender" class="form-control">
                                                <option value="">Select Your Gender</option>
                                                <option
                                                    value="1" {{ ($user->person->gender === 1) ? ' selected' : '' }}>
                                                    Male
                                                </option>
                                                <option
                                                    value="2" {{ ($user->person->gender === 2) ? ' selected' : '' }}>
                                                    Female
                                                </option>
                                            </select>
                                        </div>
                                    </div> <!-- end col -->
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="heard"> Marital Status </label>
                                            <select id="marital_status" name="marital_status"
                                                    class="form-control">
                                                <option value="">Select Your Marital Status ...</option>
                                                <option
                                                    value="1" {{ ($user->person->marital_status === 1) ? ' selected' : '' }}>
                                                    Single
                                                </option>
                                                <option
                                                    value="2" {{ ($user->person->marital_status === 2) ? ' selected' : '' }}>
                                                    Married
                                                </option>
                                                <option
                                                    value="3" {{ ($user->person->marital_status === 3) ? ' selected' : '' }}>
                                                    Divorced
                                                </option>
                                                <option
                                                    value="4" {{ ($user->person->marital_status === 4) ? ' selected' : '' }}>
                                                    Widower
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="heard"> Ethnicity </label>
                                            <select id="ethnicity" name="ethnicity" class="form-control">
                                                <option value="">Select Your Ethnicity Group</option>
                                                <option
                                                    value="1" {{ ($user->person->ethnicity === 1) ? ' selected' : '' }}>
                                                    African
                                                </option>
                                                <option
                                                    value="2" {{ ($user->person->ethnicity === 2) ? ' selected' : '' }}>
                                                    Asian
                                                </option>
                                                <option
                                                    value="3" {{ ($user->person->ethnicity === 3) ? ' selected' : '' }}>
                                                    Caucasian
                                                </option>
                                                <option
                                                    value="4" {{ ($user->person->ethnicity === 4) ? ' selected' : '' }}>
                                                    Coloured
                                                </option>
                                                <option
                                                    value="5" {{ ($user->person->ethnicity === 5) ? ' selected' : '' }}>
                                                    Indian
                                                </option>
                                                <option
                                                    value="6" {{ ($user->person->ethnicity === 6) ? ' selected' : '' }}>
                                                    White
                                                </option>
                                            </select>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="userbio">Bio</label>
                                            <textarea class="form-control" id="userbio" name="bio" rows="4"
                                                      value=""
                                                      placeholder="Write something...">{{ $user->person->bio }}</textarea>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <!-- end row -->

                                <h5 class="mb-3 text-uppercase bg-light p-2"><i
                                        class="mdi mdi-office-building mr-1"></i> Residential Address</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="res_address">Address </label>

                                            <input type="text" class="form-control" id="res_address"
                                                   name="res_address" value="{{ $user->person->res_address }}"
                                                   placeholder="Enter res_address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="res_suburb">Suburb </label>
                                            <input type="text" class="form-control" id="res_suburb" name="res_suburb"
                                                   value="{{ $user->person->res_suburb }}" placeholder="Enter Suburb ">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="companyname">City </label>
                                            <input type="text" class="form-control" id="res_city" name="res_city"
                                                   value="{{ $user->person->res_city }}" placeholder="Enter City">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="res_postal_code">Postal Code</label>
                                            <input type="number" class="form-control" id="res_postal_code"
                                                   name="res_postal_code"
                                                   value="{{ $user->person->res_postal_code }}"
                                                   placeholder="Enter Postal Code">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="heard"> Provinces </label>
                                            <select id="res_province_id" name="res_province_id"
                                                    class="form-control" required="">
                                                <option value="0">Select Provinces</option>
                                                @foreach($provinces as $province)
                                                    <option
                                                        value="{{ $province->id }}" {{ ($user->person->res_province_id == $province->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> <!-- end col -->

                                </div>

                                <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-earth mr-1"></i> Profile
                                    Image</h5>


                                <div class="form-group mb-3">
                                    <label for="validationCustom04">Profile Picture</label>
                                    <div class="mt-3">
                                        <input type="file" name="profile_pic"
                                               id="profile_pic" data-plugins="dropify"
                                               @if(!empty($user->person->profile_pic))
                                                   data-default-file="{{ asset('uploads/'.$user->person->profile_pic) }}"/>
                                        @endif

                                        <p class="text-muted text-center mt-2 mb-0">Profile Picture</p>
                                    </div>
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
