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
    <link href="{{ asset('libs/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css">
@endsection

{{-- Page content --}}
@section('content')

    @section('content_data')
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-4 col-xl-4">
                <div class="card-box text-center">
                    <img src="{{ $avatar }}" class="rounded-circle img-thumbnail"
                         alt="profile-image">
                    <h4 class="mb-0"> {{ $user->person->first_name . ' ' . $user->person->surname ?? '' }}</h4>
                    <p class="text-muted"> {{ $user->person->email }}</p>
                    <div class="text-left mt-3">
                        <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ml-2">
                                        {{ $user->person->first_name . ' ' . $user->person->surname ?? '' }}
                                    </span></p>

                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ml-2">
                                    {{ $user->person->cell_number ?? '' }}
                                    </span></p>
                    </div>
                </div> <!-- end card-box -->
            </div> <!-- end col-->
            <div class="col-lg-8 col-xl-8">
                <div class="card-box">
                    <div class="tab-content">
                        <div>
                            <form class="needs-validation" novalidate method="Post"
                                  action="{{ route('user_profile.store') }}"
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
                                            <label for="firstname">First Name <span class="text-danger">*</span>
                                            </label>
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
                                            <label for="firstname">Cell Number <span class="text-danger">*</span>
                                            </label>
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
                                </div> <!-- end row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name"> Roles</label>
                                            <select id="roles" name="roles"
                                                    class="form-control" required="">
                                                <option value="0">Select Role</option>

                                                @foreach($roles as $role)
                                                    <option
                                                        value="{{ $role->id }}" {{ ($role->id == $user_role) ? ' selected' : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lockout_time">User Time Out minutes <span
                                                    class="text-danger">*</span> </label>
                                            <input type="number" class="form-control" id="lockout_time"
                                                   name="lockout_time" value="{{ $user->lockout_time }}"
                                                   placeholder="Enter lockout time" required>
                                            <div class="invalid-feedback">
                                                Please provide lockout time.
                                            </div>
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                </div> <!-- end row -->
								<div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_number">Employee Number <span
                                                    class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="employee_number"
                                                   name="employee_number" value="{{ $user->person->employee_number }}"
                                                   placeholder="Enter Employee Number" required>
                                            <div class="invalid-feedback">
                                                Please provide Employee Number.
                                            </div>
                                            <span class="form-text text-muted"></span>
                                        </div>
                                    </div>
                                </div> <!-- end row -->
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
