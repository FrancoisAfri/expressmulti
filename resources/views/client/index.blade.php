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
<!-- Begin page -->

@section('content')

    @section('content_data')
        <div class="container-fluid">
            <form class="needs-validation" novalidate method="Post" action="{{ route('client_details.store') }}"
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
                                </i> Personal Info
                            </h5>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="firstname">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="first_name"
                                               name="first_name" placeholder="Enter first name" required>

                                        <input type="hidden" id="is_active" name="is_active" value="1">

                                        <div class="invalid-feedback">
                                            Please provide First Name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="firstname">Initials <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" id="initial" maxlength="5"
                                               name="initial" placeholder="Enter Initial" required>

                                        <div class="invalid-feedback">
                                            Please provide Initials.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="surname"
                                               name="surname" placeholder="Enter last name" required>
                                        <div class="invalid-feedback">
                                            Please provide Last Name.
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span> </label>
                                        <input type="email" required parsley-type="email" class="form-control"
                                               id="inputEmail3" name="email" placeholder="Enter Email">
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender"> Gender <span class="text-danger">*</span></label>
                                        <select id="gender" name="gender" class="form-control" required>
                                            <option value="">Select Your Gender</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please provide Gender.
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Birth <span class="text-danger">*</span></label>
                                        <input type="text" id="humanfd-datepicker" name="date_of_birth"
                                               value=""
                                               class="form-control" placeholder="Pick a date" required>
                                        <div class="invalid-feedback">
                                            Please provide Date of Birth.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passport_number"> Age </label>
                                        <input type="text" class="form-control" id="age"
                                               name="age" value=""
                                               placeholder="" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label> Cell Number <span class="text-danger">*</span></label>
                                        <br>
                                        <input type="text" class="form-control" id="phone" maxlength="12"
                                               name="phone_number" value="" placeholder="Enter Cell Number" required>
                                        <div class="invalid-feedback">
                                            Please provide Cell Number.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row d-flex justify-content-center">
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="id_number" name="radioInline" checked>
                                    <label for="inlineRadio1"> ID NUMBER <span class="text-danger">*</span></label>
                                </div>
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="passport" name="radioInline">
                                    <label for="inlineRadio2"> PASSPORT <span class="text-danger">*</span> </label>
                                </div>
                            </div>
                            <br>
                            <div id="b-id_number">
                                <div class="row id_number box">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_number" id="lbl_id_number">ID Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="id_number"
                                                   minlength="10" maxlength="13" value="" name="id_number"
                                                   placeholder="Enter ID Number">
                                        </div>
                                    </div> <!-- end col -->
                                </div>
                            </div>
                            <div id="passport">
                                <div class="row passport box">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="passport_number" id="lbl_pass"> Passport Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="passport_number"
                                                   name="passport_number" value=""
                                                   placeholder="Enter Passport Number (optional)">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="heard"> Country of Origin <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="passport_origin_country_id"
                                                    name="passport_origin_country_id"
                                                    data-toggle="select2"
                                                    class="form-control" required="">
                                                <option value="0">Select Country</option>
                                                @foreach($country as $countries)
                                                    <option
                                                        value="{{ $countries->id }}">{{ $countries->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> <!-- end col -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="res_address">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="res_address"
                                               name="res_address" value="" placeholder="Enter res_address" required>

                                        <div class="invalid-feedback">
                                            Please provide Address.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="res_suburb">Suburb <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="res_suburb" name="res_suburb"
                                               value="" placeholder="Enter Suburb " required>

                                        <div class="invalid-feedback">
                                            Please provide Suburb.
                                        </div>

                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="companyname">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="res_city" name="res_city"
                                               value="" placeholder="Enter City" required>
                                        <div class="invalid-feedback">
                                            Please provide Suburb.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="res_postal_code">Postal Code <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="res_postal_code"
                                               name="res_postal_code"
                                               value="" placeholder="Enter Postal Code" required>
                                        <div class="invalid-feedback">
                                            Please provide Postal Code.
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div> <!-- end card-box -->
                        <div class="card-box">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Emergency Contact </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="emergency_contact_name"
                                               name="emergency_contact_name" placeholder="Enter first name" required>
                                        <div class="invalid-feedback">
                                            Please provide Emergency Contact First Name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="emergency_contact_surname"
                                               name="emergency_contact_surname" placeholder="Enter last name" required>
                                        <div class="invalid-feedback">
                                            Please provide Emergency Contact Last Name.
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender"> Relationship to Client <span class="text-danger">*</span></label>
                                        <select id="relation" name="relation" class="form-control" required>
                                            <option value="0">Select Relationship to Client</option>
                                            <option value="Spouse">Spouse</option>
                                            <option value="mother">Mother</option>
                                            <option value="father">Father</option>
                                            <option value="sister">Sister</option>
                                            <option value="brother">Brother</option>
                                            <option value="child">Child</option>
                                            <option value="relative">Relative</option>
                                            <option value="friend">Friend</option>
                                            <option value="employer">Employer</option>
                                            <option value="employee">Employee</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please provide Relationship to Client.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="emergency_no"
                                               name="emergency_contact_cell_number" value=""
                                               placeholder="Enter contact Number" maxlength="13" required>
                                        <div class="invalid-feedback">
                                            Please provide Emergency Contact Number.
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                        </div> <!-- end card-box -->
                        <div class="card-box">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Family Doctor </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname"> Name</label>
                                        <input type="text" class="form-control" id="doc_name"
                                               name="doc_name" placeholder="Enter first name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label> Contact Number</label>
                                        <input type="text" class="form-control" id="phone" maxlength="13"
                                               name="doc_phone" value="" placeholder="Enter contact Number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Profile Images</h5>
                            <div class="form-group mb-3">
                                <div class="mt-3">
                                    <input type="file" name="profile_pic"
                                           id="profile_pic" data-plugins="dropify"/>
                                    <p class="text-muted text-center mt-2 mb-0">Profile Picture</p>
                                </div>
                            </div>
                        </div> <!-- end col-->
                    </div> <!-- end col-->
                </div>
                <div class="text-center mb-3">
                    <a class="btn w-sm btn-outline-info waves-effect"
                       href="{{ URL::route('patientManagement.index') }}">
                        Back
                    </a>
                    <button type="submit" class="btn w-sm btn-success waves-effect waves-light">Save
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

        // $('[data-toggle="select2"]').select2();

        document.querySelectorAll('#phone ,#emergency_no').forEach(item => {
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

        // $('input').iCheck({
        //     checkboxClass: 'icheckbox_square-blue',
        //     radioClass: 'iradio_square-blue',
        //     increaseArea: '20%' // optional
        // });


        /**
         * change radio buttons for id number and passport
         * @type {*|jQuery|HTMLElement}
         */
        let id_number = $('#b-id_number');
        let passport = $('#passport');
        let guarantor_id_number = $('#guarantor_id_number');
        let lbl_guarantor_id_number = $('#lbl_guarantor_id_number');
        let medical = $("#b-medical");
        let some_one_guarantor = $("#b-some_one_guarantor");
        let guarantor_personal_details = $("#b-guarantor-personal_details");
        let guarantor_contact_details = $("#b-guarantor-contact_details");
        let guarantor_employment_details = $("#b-guarantor-employment_details");
        let other_payment = $("#b-other-payment");
        let guarantor_id = $("#b-guarantor_id");
        let guarantor_passport = $("#b-guarantor_passport");
        let whose_paying = $("#b-whose_paying");
        let main_member = $("#b-main_member");
        let is_employed = $("#b-is_employed");
        let main_member_empl_det = $("#b-main_member_empl_det");


        window.onload = function () {
            passport.hide();
            main_member.hide();
            some_one_guarantor.hide();
            guarantor_personal_details.hide();
            guarantor_contact_details.hide();
            guarantor_employment_details.hide();
            guarantor_passport.hide();
            other_payment.hide();

        };

        $(function () {
            let $radios = $('input:radio[name=radio_main_member][value=me]');
            if ($radios.is(':checked') === false) {
                $radios.filter('[value=me]').prop('checked', true);
            }
        });

        $(function () {
            let $radios = $('input:radio[name=radio_insurance][value=medical_aid]');
            if ($radios.is(':checked') === false) {
                $radios.filter('[value=medical_aid]').prop('checked', true);
            }
        });

        $(function () {
            let $radios = $('input:radio[name=radioInline][value=id_number]');
            if ($radios.is(':checked') === false) {
                $radios.filter('[value=id_number]').prop('checked', true);
            }
        });


        $(function () {

            /**
             * listen to firstname keys
             */
            $('#first_name').keyup(function () {
                update();
            });

            function update() {
                $('#label').text($('#first_name').val());
            }

            $(document).ready(function () {
                $('input[type="radio"]').click(function () {
                    const inputValue = $(this).attr("value");
                    if (inputValue == 'id_number') {
                        id_number.show();
                        passport.hide();
                    } else if (inputValue == 'passport') {
                        passport.show();
                        id_number.hide();
                    }
                });

            });


            $(document).ready(function () {
                $('input[type="radio"]').click(function () {
                    const inputValue = $(this).attr("value");
                    if (inputValue == 'medical_aid') {
                        medical.show();
                        main_member.show();
                        some_one_guarantor.hide();
                        guarantor_personal_details.hide();
                        guarantor_contact_details.hide();
                        guarantor_employment_details.hide();
                        other_payment.hide();
                    } else if (inputValue == 'paying') {
                        medical.hide();
                        other_payment.hide();
                        whose_paying.hide();
                        some_one_guarantor.show();
                        some_one_guarantor.show();
                        guarantor_personal_details.show();
                        guarantor_contact_details.show();
                        guarantor_employment_details.show();
                    } else if (inputValue == 'other') {
                        other_payment.show();
                        medical.hide();
                        main_member.hide();
                        some_one_guarantor.hide();
                        guarantor_personal_details.hide();
                        guarantor_contact_details.hide();
                        guarantor_employment_details.hide();
                    }
                });

            });

            $(document).ready(function () {
                $('input[type="radio"]').click(function () {
                    const inputValue = $(this).attr("value");
                    if (inputValue == 'guarantor_id_number') {
                        guarantor_id.show();
                        guarantor_passport.hide();
                    } else if (inputValue == 'guarantor_passport') {
                        guarantor_id.hide();
                        guarantor_passport.show();
                    }
                });

            });

            $(document).ready(function () {
                $('input[type="radio"]').click(function () {
                    const inputValue = $(this).attr("value");
                    if (inputValue == 'me') {
                        medical.show();
                        some_one_guarantor.hide();
                        some_one_guarantor.hide();
                        guarantor_personal_details.hide();
                        guarantor_contact_details.hide();
                        guarantor_employment_details.hide();
                        main_member.hide();
                    } else if (inputValue == 'some_one_else') {
                        medical.show();
                        main_member.show();
                        // some_one_guarantor.show();
                        // some_one_guarantor.show();
                        // guarantor_personal_details.show();
                        // guarantor_contact_details.show();
                        // guarantor_employment_details.show();
                    }
                });

            });

            $(document).ready(function () {
                $('input[type="radio"]').click(function () {
                    const inputValue = $(this).attr("value");
                    if (inputValue == 'employed') {
                        is_employed.show();

                    } else if (inputValue == 'un-employed') {
                        is_employed.hide();

                    }
                });

            });

            $(document).ready(function () {
                $('input[type="radio"]').click(function () {
                    const inputValue = $(this).attr("value");
                    if (inputValue == 'main_employed') {
                        main_member_empl_det.show();

                    } else if (inputValue == 'main_un_employed') {
                        main_member_empl_det.hide();

                    }
                });

            });


            $("#humanfd-datepicker").change(function () {
                let date = $("#humanfd-datepicker").datepicker({dateFormat: 'MM,dd,yyyy'}).val();
                let getAge = GetAge(new Date(date))
                return $('#age').val(getAge);
            });

            function GetAge(birthDate) {
                const dob = birthDate;
                const month_diff = Date.now() - dob.getTime();
                const age_dt = new Date(month_diff);
                const year = age_dt.getUTCFullYear();
                const age = Math.abs(year - 1970);
                return age;
            }


        });


    </script>
@endsection
