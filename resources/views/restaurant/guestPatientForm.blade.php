@extends('layouts.main-guest')

@section('page_dependencies')

    <link href="{{ asset('libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>

    {{--    <link rel="stylesheet" href="{{ asset('libs/datatables/dataTables.bootstrap.css') }}">--}}

    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
          rel="stylesheet') }}" type="text/css" />
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
                  action="{{ route('patient_details.update', $patient->id) }}" enctype="multipart/form-data">

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
                                </i> Personal Info
                            </h5>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="firstname">First Name</label>
                                        <input type="text" class="form-control" id="first_name"
                                               value="{{ $patient->first_name ?? ''}}"
                                               name="first_name" placeholder="Enter first name">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="firstname">Initials </label>
                                        <input type="text" class="form-control" id="initial"
                                               value="{{ $patient->initial ?? ''}}"
                                               name="initial" placeholder="Enter Initial">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" class="form-control" id="surname"
                                               value="{{ $patient->surname ?? '' }}"
                                               name="surname" placeholder="Enter last name">
                                    </div>
                                </div> <!-- end col -->
                            </div>

                            <input type="hidden" id="is_active" name="is_active" value="1">


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email"
                                               value="{{ $patient->email ?? '' }}"
                                               name="email" placeholder="Enter email">
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender"> Gender </label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="">Select Your Gender</option>
                                            <option value="{{ ($patient->gender === 1) ? '1' : '' }}">Male
                                            </option>
                                            <option value="{{ ($patient->gender === 2) ? '2' : '' }}">Female
                                            </option>
                                        </select>
                                    </div>
                                </div> <!-- end col -->
                            </div>


                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="text" id="humanfd-datepicker" name="date_of_birth"
                                               value="{{ ($patient->date_of_birth) ? date('d/m/Y', strtotime($patient->date_of_birth)) : '' }}"
                                               class="form-control" placeholder="Pick a date">
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
                                        <label> Cell Number</label>
                                        <br>
                                        <input type="text" class="form-control" data-toggle="input-mask" id="phone"
                                               name="phone_number" value="{{ $patient->phone_number ?? '' }}"
                                               placeholder="Enter Cell Number">
                                    </div>
                                </div>
                            </div>


                            <br>
                            <div class="row d-flex justify-content-center">
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="id_number" name="radioInline" checked>
                                    <label for="inlineRadio1"> ID NUMBER</label>
                                </div>
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="passport" name="radioInline">
                                    <label for="inlineRadio2"> PASSPORT </label>
                                </div>
                            </div>
                            <br>

                            <div id="b-id_number">
                                <div class="row id_number box">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_number" id="lbl_id_number">ID Number</label>
                                            <input type="text" class="form-control" id="id_number"
                                                   maxlength="13" value="{{ $patient->id_number ?? '' }}"
                                                   name="id_number"
                                                   placeholder="Enter ID Number">
                                        </div>
                                    </div> <!-- end col -->
                                </div>
                            </div>


                            <div id="passport">
                                <div class="row passport box">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="passport_number" id="lbl_pass"> Passport Number </label>
                                            <input type="text" class="form-control" id="passport_number"
                                                   name="passport_number" value="{{ $patient->passport_number ?? '' }}"
                                                   placeholder="Enter Passport Number (optional)">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="heard"> Country of Origin </label>
                                            <select class="form-control" id="passport_origin_country_id"
                                                    name="passport_origin_country_id"
                                                    data-toggle="select2"
                                                    class="form-control" required="">
                                                <option value="0">Select Country</option>
                                                @foreach($country as $province)
                                                    <option
                                                        value="{{ $province->id }}"{{ ($patient->passport_origin_country_id == $patient->id) ? ' selected' : '' }}>{{ $province->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div> <!-- end col -->
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="res_address">Address </label>

                                        <input type="text" class="form-control" id="res_address"
                                               name="res_address" value="{{ $patient->res_address ?? '' }}"
                                               placeholder="Enter res_address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="res_suburb">Suburb </label>
                                        <input type="text" class="form-control" id="res_suburb" name="res_suburb"
                                               value=" {{ $patient->res_suburb ?? '' }}" placeholder="Enter Suburb ">
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="companyname">City </label>
                                        <input type="text" class="form-control" id="res_city" name="res_city"
                                               value="{{ $patient->res_city ?? '' }}" placeholder="Enter City">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="res_postal_code">Postal Code</label>
                                        <input type="number" class="form-control" id="res_postal_code"
                                               name="res_postal_code"
                                               value="{{ $patient->res_postal_code ?? '' }}"
                                               placeholder="Enter Postal Code">
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->


                        </div> <!-- end card-box -->

                        @foreach($patient->emergency as $emergency)
                            <div class="card-box">
                                <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Emergency Contact </h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">First Name</label>
                                            <input type="text" class="form-control" id="emergency_contact_name"
                                                   value="{{ $emergency->emergency_contact_name ?? '' }}"
                                                   name="emergency_contact_name" placeholder="Enter first name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastname">Last Name</label>
                                            <input type="text" class="form-control" id="emergency_contact_surname"
                                                   value="{{ $emergency->emergency_contact_surname ?? '' }}"
                                                   name="emergency_contact_surname" placeholder="Enter last name">
                                        </div>
                                    </div> <!-- end col -->
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender"> Relationship to Client </label>
                                            <select id="relation" name="relation" class="form-control">
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
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Contact Number</label>
                                            <input type="text" class="form-control" data-toggle="input-mask"
                                                   id="emergency_no" name="emergency_contact_cell_number"
                                                   value="{{ $emergency->emergency_contact_cell_number ?? '' }}"
                                                   placeholder="Enter contact Number">
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>

                            </div> <!-- end card-box -->
                        @endforeach

                        @foreach($patient->doctor as $doctor)
                            <div class="card-box">
                                <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Family Doctor </h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname"> Name</label>
                                            <input type="text" class="form-control" id="doc_name"
                                                   value="{{ $doctor->doc_name ?? '' }}"
                                                   name="doc_name" placeholder="Enter first name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Contact Number</label>
                                            <input type="text" class="form-control" data-toggle="input-mask"
                                                   id="phone" value="{{ $doctor->doc_phone ?? '' }}"
                                                   name="doc_phone" placeholder="Enter contact Number">
                                        </div>
                                    </div>
                                </div>


                            </div>
                        @endforeach

                        <div class="card">

                            <div class="card-header bg-info py-3 text-white  user_datatable">
                                <div class="card-widgets">
                                    <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                    <a data-toggle="collapse" href="#cardCollpase9" role="button"
                                       aria-expanded="false" aria-controls="cardCollpase2"><i
                                            class="mdi mdi-minus"></i></a>
                                    <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                </div>
                                <h5 class="card-title mb-0 text-white">Add Dependencies </h5>
                            </div>
                            <div id="cardCollpase9" class="collapse show">
                                <br>
                                <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
                                        data-toggle="modal" data-target="#add-new-dependencies-modal">
                                    <i class="mdi mdi-sort-numeric-ascending mr-2 text-muted font-18 vertical-middle"></i>
                                    Add Dependency
                                </button>
{{--                                <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"--}}
{{--                                        data-toggle="modal" data-target="#full-width-modal">--}}
{{--                                    <i class="mdi mdi-sort-numeric-ascending mr-2 text-muted font-18 vertical-middle"></i>--}}
{{--                                    Add--}}
{{--                                </button>--}}
                                <div class="card-body">


                                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100 display"
                                           id="">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>surname</th>
                                            <th>date of birth</th>
                                            <th>Dependency Code</th>
                                            <th>Action</th>

                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($dependencies as $key => $module)
                                            <tr>
                                                <td>
                                                    {{ $module->dependency_first_name ?? ''}}
                                                </td>

                                                <td>
                                            <span>
                                                 {{ $module->dependency_surname ?? ''}}
                                            </span>
                                                </td>

                                                <td>
                                            <span>
                                              {{ !empty($module->dependency_date_of_birth) ? date(' d M Y', $module->dependency_date_of_birth) : '' }}
                                            </span>
                                                </td>

                                                <td>
                                            <span>
                                                 {{ $module->dependency_code ?? ''}}
                                            </span>
                                                </td>

                                                <td>
                                                    <div class="btn-group dropdown">
                                                        <div
                                                            class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                                            data-toggle="dropdown" aria-expanded="false"><i
                                                                class="mdi mdi-arrange-bring-to-front"></i></div>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            {{--                                                            <button class="dropdown-item"--}}
                                                            {{--                                                                    data-toggle="modal"--}}
                                                            {{--                                                                    data-target="#edit-new-module-modal"--}}
                                                            {{--                                                                    data-id="{{ $module->id }}"--}}
                                                            {{--                                                                    data-name="{{ $module->dependency_first_name }}"--}}
                                                            {{--                                                                    data-path="{{ $module->path }}"--}}
                                                            {{--                                                                    data-font_awesome="{{ $module->font_awesome }}"--}}
                                                            {{--                                                                    data-code_name="{{ $module->code_name }}">--}}
                                                            {{--                                                                <i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>--}}
                                                            {{--                                                                Edit Dependency--}}
                                                            {{--                                                            </button>--}}

                                                            {{--                                                            <button class="dropdown-item" id="delete_button" name="command"--}}
                                                            {{--                                                                    onclick="if(confirm('Are you sure you want to delete this Induction ?'))--}}
                                                            {{--                                                                    { deleteRecord({{$module->id}})} else {return false;}"--}}
                                                            {{--                                                                    value="Delete">--}}
                                                            {{--                                                                <i class="mdi mdi-delete-empty mr-2 text-muted font-18 vertical-middle delete_confirm"--}}
                                                            {{--                                                                   data-toggle="tooltip" title='Delete'></i>--}}
                                                            {{--                                                                Delete--}}
                                                            {{--                                                            </button>--}}

                                                            <button class="dropdown-item"  type="button"
                                                                    id="delete_button" name="command"
                                                                    onclick="if(confirm('Are you sure you want to delete this Induction ?'))
                                                                    { deleteRecord({{$module->id}})} else {return false;}"
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

                    </div> <!-- end col -->

                    <div class="col-lg-6">

                        <div class="card-box">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Profile Images</h5>

                            <div class="form-group mb-3">

                                <div class="mt-3">
                                    <input type="file" name="profile_pic"
                                           id="profile_pic" data-plugins="dropify"
                                           @if(!empty($patient->profile_pic))
                                               data-default-file="{{ asset('uploads/'.$patient->profile_pic) }}"/>
                                    @endif
                                </div>
                            </div>

                        </div> <!-- end col-->

                        <!-- end card-box -->

                        <div class="card-box">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">How are you insured?</h5>

                            <br>
                            <div class="row d-flex justify-content-center">
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="medical_aid" name="radio_insurance" checked>
                                    <label for="inlineRadio1"> Medical Aid</label>
                                </div>
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="paying" name="radio_insurance">
                                    <label for="inlineRadio2"> Self Funding </label>
                                </div>
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="other" name="radio_insurance">
                                    <label for="inlineRadio2"> Other </label>
                                </div>
                            </div>
                            <br>
                            @foreach($patient->medicalAid as $medical)
                                <div id="b-medical">
                                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2 text-center">Medical Aid </h5>

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstname">Medical Aid Scheme</label>

                                                <select class="form-control" data-toggle="select2" name="medical_aid_scheme_name"
                                                        id="medical_aid_scheme_name" required>
                                                    <option value=" " selected>Select Medical Aid...</option>
                                                    @foreach($medicalAids as $medicalAid)
                                                        <option
                                                            value="{{ $medicalAid->id }}">{{ $medicalAid->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastname">Medical Aid Plan</label>
                                                <input type="text" class="form-control" id="medical_aid_plan"
                                                       value="{{ $medical->medical_aid_plan ?? '' }}"
                                                       name="medical_aid_plan" placeholder="Enter plan name">
                                            </div>
                                        </div> <!-- end col -->
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstname">Medical Aid Number </label>
                                                <input type="text" class="form-control" id="medical_aid_no"
                                                       value="{{ $medical->medical_aid_no ?? '' }}"
                                                       name="medical_aid_no" placeholder="Enter medical aid number">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">

                                                <label> Medical Aid Dependant Code
                                                    <span class="badge badge-info rounded-circle noti-icon-badge"
                                                          data-toggle="tooltip" title='A dependant is any person,
                                                   usually a member of the family that qualifies for cover because they depend
                                                   financially on the main member. Dependants could be adult or child dependants.
                                                  The main member dependant code is 00, and the other members are 01,
                                                   02... The information is usually displayed on the back
                                                   of your medical aid card.'>?</span>
                                                </label>

                                                <input type="number" class="form-control" id="medical_aid_dep_code"
                                                       value="{{ $medical->medical_aid_dep_code ?? '' }}"
                                                       name="medical_aid_dep_code" placeholder="Enter number">
                                            </div>

                                        </div>
                                        <!-- end col -->
                                    </div>

                                </div>
                            @endforeach

                            <div id="b-whose_paying">
                                <div>
                                    <label> Who is the Medical Aid Main Member ? </label>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <br>
                                    <div class="radio radio-info form-check-inline">
                                        <input type="radio" id="radio" value="me" name="radio_main_member" checked>
                                        <label id="label" for="inlineRadio1"> Me</label>
                                    </div>
                                    <br>
                                    <div class="radio radio-info form-check-inline">
                                        <input type="radio" id="radio" value="some_one_else" name="radio_main_member">
                                        <label for="inlineRadio2"> SomeOne Else </label>
                                    </div>
                                </div>
                            </div>
                            <br><br>

                            @foreach($patient->mainMember as $mainMember)
                                <div id="b-main_member">
                                    <div class="card">
                                        <div class="card-header bg-info py-3 text-white">
                                            <div class="card-widgets">
                                                <a href="javascript:;" data-toggle="reload"><i
                                                        class="mdi mdi-refresh"></i></a>
                                                <a data-toggle="collapse" href="#cardCollpase1" role="button"
                                                   aria-expanded="false" aria-controls="cardCollpase2"><i
                                                        class="mdi mdi-minus"></i></a>
                                                <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                            </div>
                                            <h5 class="card-title mb-0 text-white">Main member personal details </h5>
                                        </div>
                                        <div id="cardCollpase1" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="firstname">First Name66</label>
                                                            <input type="text" class="form-control"
                                                                   id="main_member_first_name"
                                                                   value="{{ $mainMember->main_member_first_name ?? '' }}"
                                                                   name="main_member_first_name"
                                                                   placeholder="Enter first name">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="firstname">Initials </label>
                                                            <input type="text" class="form-control"
                                                                   id="main_member_initial"
                                                                   value="{{ $mainMember->main_member_initial ?? '' }}"
                                                                   name="main_member_initial"
                                                                   placeholder="Enter first name">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="lastname">Last Name</label>
                                                            <input type="text" class="form-control"
                                                                   id="main_member_surname"
                                                                   value="{{ $mainMember->main_member_surname ?? '' }}"
                                                                   name="main_member_surname"
                                                                   placeholder="Enter last name">
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gender"> Title </label>
                                                            <select id="main_member_title" name="main_member_title"
                                                                    class="form-control">
                                                                <option value="0">Select Title</option>
                                                                <option value="Ms">Ms</option>
                                                                <option value="Miss">Miss</option>
                                                                <option value="Mrs">Mrs</option>
                                                                <option value="Mr">Mr</option>
                                                                <option value="Mast">Mast</option>
                                                                <option value="Dr">Dr</option>
                                                                <option value="Prof">Prof</option>
                                                                <option value="Adv">Adv</option>
                                                                <option value="Hon">Hon</option>
                                                                <option value="Rev">Rev</option>
                                                                <option value="Baby">Baby</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gender"> Relationship to Client </label>
                                                            <select id="main_member_relation"
                                                                    name="main_member_relation"
                                                                    class="form-control">
                                                                <option value="0">Select Relationship to Client
                                                                </option>
                                                                <option value="1">Spouse</option>
                                                                <option value="2">Mother</option>
                                                                <option value="3">Father</option>
                                                                <option value="4">Sister</option>
                                                                <option value="4">Brother</option>
                                                                <option value="5">Child</option>
                                                                <option value="6">Relative</option>
                                                                <option value="7">Friend</option>
                                                                <option value="8">Employer</option>
                                                                <option value="9">Employee</option>
                                                                <option value="10">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- end col -->
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstname"> Email</label>
                                                            <input type="email" class="form-control"
                                                                   id="main_member_email"
                                                                   value="{{ $mainMember->main_member_email ?? '' }}"
                                                                   name="main_member_email" placeholder="Enter Email">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Contact Number</label>
                                                            <input type="text" class="form-control"
                                                                   data-toggle="input-mask"
                                                                   data-mask-format="(000) 000-0000" id="phone"
                                                                   name="main_member_phone"
                                                                   value="{{ $mainMember->main_member_phone ?? '' }}"
                                                                   placeholder="Enter contact Number">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div> <!-- end card-->

                                    <div class="card">
                                        <div class="card-header bg-info py-3 text-white">
                                            <div class="card-widgets">
                                                <a href="javascript:;" data-toggle="reload"><i
                                                        class="mdi mdi-refresh"></i></a>
                                                <a data-toggle="collapse" href="#cardCollpase5" role="button"
                                                   aria-expanded="false" aria-controls="cardCollpase"><i
                                                        class="mdi mdi-minus"></i></a>
                                                <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                            </div>
                                            <h5 class="card-title mb-0 text-white">Main member employer details</h5>
                                        </div>
                                        <div id="cardCollpase5" class="collapse show">
                                            <br>
                                            <div class="row d-flex justify-content-center">
                                                <br>
                                                <div class="radio radio-info form-check-inline">
                                                    <input type="radio" id="radio" value="main_employed"
                                                           name="main_isEmployed"
                                                           checked>
                                                    <label for="radio_isEmployed"> Employed</label>
                                                </div>
                                                <br>
                                                <div class="radio radio-info form-check-inline">
                                                    <input type="radio" id="radio" value="main_un_employed"
                                                           name="main_isEmployed">
                                                    <label for="radio_isEmployed"> UnEmployed </label>
                                                </div>
                                            </div>
                                            <br>
                                            <div id="b-main_member_empl_det">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="firstname"> Employer Name</label>
                                                                <input type="text" class="form-control"
                                                                       id="main_member_employer_name"
                                                                       name="main_member_employer_name"
                                                                       value="{{ $mainMember->main_member_employer_name ?? '' }}"
                                                                       placeholder="Enter first name">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Employer Phone</label>
                                                                <input type="text" class="form-control"
                                                                       data-toggle="input-mask"
                                                                       data-mask-format="(000) 000-0000" id="phone"
                                                                       value="{{ $mainMember->main_member_employer_phone ?? '' }}"
                                                                       name="main_member_employer_phone"
                                                                       placeholder="Enter contact Number">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="res_address">Address </label>

                                                                <input type="text" class="form-control"
                                                                       id="main_member_employer_address"
                                                                       name="main_member_employer_address"
                                                                       value="{{ $mainMember->main_member_employer_address ?? '' }}"
                                                                       placeholder="Enter res_address">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="res_suburb">Suburb </label>
                                                                <input type="text" class="form-control"
                                                                       id="main_member_employer_suburb"
                                                                       name="main_member_employer_suburb"
                                                                       value="{{ $mainMember->main_member_employer_suburb ?? "" }}"
                                                                       placeholder="Enter Suburb ">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="companyname">City </label>
                                                                <input type="text" class="form-control"
                                                                       id="main_member_contact_city"
                                                                       name="main_member_contact_city"
                                                                       value="{{ $mainMember->main_member_contact_city ?? " " }}"
                                                                       placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="res_postal_code">Postal Code</label>
                                                                <input type="number" class="form-control"
                                                                       id="main_member_contact_postalCode"
                                                                       name="main_member_contact_postalCode"
                                                                       value="{{ $mainMember->main_member_contact_postalCode ?? "" }}"
                                                                       placeholder="Enter Postal Code">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                </div>
                                            </div>

                                        </div>
                                    </div> <!-- end card-->
                                </div>
                            @endforeach

                            @foreach($patient->guarantor as $guarantor)
                                <div id="b-some_one_guarantor">

                                    <br>
                                    <br>
                                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2 text-center">Someone Else
                                        (Guarantor) </h5>
                                    <br>
                                    <div class="row d-flex justify-content-center">
                                        <br>
                                        <div class="radio radio-info form-check-inline">
                                            <input type="radio" id="radio" value="guarantor_id_number"
                                                   name="radioInguarantor" checked>
                                            <label for="inlineRadio1"> ID NUMBER</label>
                                        </div>
                                        <br>
                                        <div class="radio radio-info form-check-inline">
                                            <input type="radio" id="radio" value="guarantor_passport"
                                                   name="radioInguarantor">
                                            <label for="inlineRadio2"> PASSPORT </label>
                                        </div>
                                    </div>

                                    <div id="b-guarantor_id">
                                        <div class="row id_number box">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_number" id="lbl_guarantor_id_number">ID
                                                        Number</label>
                                                    <input type="text" class="form-control" maxlength="13"
                                                           id="guarantor_id_number"
                                                           value="{{ $guarantor->guarantor_id_number ?? "" }}"
                                                           name="guarantor_id_number"
                                                           placeholder="Enter ID Number">
                                                </div>
                                            </div> <!-- end col -->
                                        </div>
                                    </div>

                                    <div id="b-guarantor_passport">
                                        <div class="row passport box">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="passport_number" id="lbl_pass"> Passport Number </label>
                                                    <input type="text" class="form-control"
                                                           id="guarantor_passport_number"
                                                           name="guarantor_passport_number"
                                                           value="{{ $guarantor->guarantor_passport_number ?? "" }}"
                                                           placeholder="Enter Passport Number (optional)">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="heard"> Country of Origin </label>
                                                    <select class="form-control"
                                                            id="guarantor_passport_origin_country_id"
                                                            name="guarantor_passport_origin_country_id"
                                                            data-toggle="select2"
                                                            class="form-control">
                                                        <option value="0">Select Provinces</option>
                                                        @foreach($country as $province)
                                                            <option
                                                                value="{{ $province->id }}">{{ $province->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- end col -->
                                        </div>
                                    </div>

                                    <br>
                                </div>


                                <div id="b-guarantor-personal_details">
                                    <div class="card">
                                        <div class="card-header bg-info py-3 text-white">
                                            <div class="card-widgets">
                                                <a href="javascript:;" data-toggle="reload"><i
                                                        class="mdi mdi-refresh"></i></a>
                                                <a data-toggle="collapse" href="#cardCollpase1" role="button"
                                                   aria-expanded="false" aria-controls="cardCollpase2"><i
                                                        class="mdi mdi-minus"></i></a>
                                                <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                            </div>
                                            <h5 class="card-title mb-0 text-white">Guarantor personal details</h5>
                                        </div>
                                        <div id="cardCollpase1" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="firstname">First Name</label>
                                                            <input type="text" class="form-control"
                                                                   id="guarantor_first_name"
                                                                   name="guarantor_first_name"
                                                                   value="{{ $guarantor->guarantor_first_name ?? ""  }}"
                                                                   placeholder="Enter first name">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="firstname">Initials </label>
                                                            <input type="text" class="form-control"
                                                                   id="guarantor_initial"
                                                                   value="{{ $guarantor->guarantor_initial ?? "" }}"
                                                                   name="guarantor_initial"
                                                                   placeholder="Enter first name">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="lastname">Last Name</label>
                                                            <input type="text" class="form-control"
                                                                   id="guarantor_surname"
                                                                   value="{{ $guarantor->guarantor_surname ?? "" }}"
                                                                   name="guarantor_surname"
                                                                   placeholder="Enter last name">
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gender"> Title </label>
                                                            <select id="guarantor_title" name="guarantor_title"
                                                                    class="form-control">
                                                                <option value="0">Select Title</option>
                                                                <option value="Ms">Ms</option>
                                                                <option value="Miss">Miss</option>
                                                                <option value="Mrs">Mrs</option>
                                                                <option value="Mr">Mr</option>
                                                                <option value="Mast">Mast</option>
                                                                <option value="Dr">Dr</option>
                                                                <option value="Prof">Prof</option>
                                                                <option value="Adv">Adv</option>
                                                                <option value="Hon">Hon</option>
                                                                <option value="Rev">Rev</option>
                                                                <option value="Baby">Baby</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gender"> Relationship to Client </label>
                                                            <select id="guarantor_relation" name="guarantor_relation"
                                                                    class="form-control">
                                                                <option value="0">Select Relationship to Client
                                                                </option>
                                                                <option value="1">Spouse</option>
                                                                <option value="2">Mother</option>
                                                                <option value="3">Father</option>
                                                                <option value="4">Sister</option>
                                                                <option value="4">Brother</option>
                                                                <option value="5">Child</option>
                                                                <option value="6">Relative</option>
                                                                <option value="7">Friend</option>
                                                                <option value="8">Employer</option>
                                                                <option value="9">Employee</option>
                                                                <option value="10">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- end col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end card-->
                                </div>

                                <!-- end col -->
                                <div id="b-guarantor-contact_details">
                                    <div class="card">
                                        <div class="card-header bg-info py-3 text-white">
                                            <div class="card-widgets">
                                                <a href="javascript:;" data-toggle="reload"><i
                                                        class="mdi mdi-refresh"></i></a>
                                                <a data-toggle="collapse" href="#cardCollpase2" role="button"
                                                   aria-expanded="false" aria-controls="cardCollpase2"><i
                                                        class="mdi mdi-minus"></i></a>
                                                <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                            </div>
                                            <h5 class="card-title mb-0 text-white">Guarantor contact details </h5>
                                        </div>
                                        <div id="cardCollpase2" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstname"> Email</label>
                                                            <input type="email" class="form-control"
                                                                   id="guarantor_email"
                                                                   value="{{ $guarantor->guarantor_email ?? "" }}"
                                                                   name="guarantor_email"
                                                                   placeholder="Enter first name">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Contact Number</label>
                                                            <input type="text" class="form-control"
                                                                   data-toggle="input-mask"
                                                                   data-mask-format="(000) 000-0000" id="phone"
                                                                   name="guarantor_phone"
                                                                   value="{{ $guarantor->guarantor_phone ?? "" }}"
                                                                   placeholder="Enter contact Number">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end card-->
                                </div>

                                <div id="b-guarantor-employment_details">
                                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2 text-center">Guarantor employer
                                        details </h5>

                                    <br>
                                    <div class="row d-flex justify-content-center">
                                        <br>
                                        <div class="radio radio-info form-check-inline">
                                            <input type="radio" id="radio" value="employed" name="radio_isEmployed"
                                                   checked>
                                            <label for="radio_isEmployed"> Employed</label>
                                        </div>
                                        <br>
                                        <div class="radio radio-info form-check-inline">
                                            <input type="radio" id="radio" value="un-employed" name="radio_isEmployed">
                                            <label for="radio_isEmployed"> UnEmployed </label>
                                        </div>
                                    </div>
                                    <br>
                                    <!-- end col -->
                                    <div id="b-is_employed">
                                        <div class="card">
                                            <div class="card-header bg-info py-3 text-white">
                                                <div class="card-widgets">
                                                    <a href="javascript:;" data-toggle="reload"><i
                                                            class="mdi mdi-refresh"></i></a>
                                                    <a data-toggle="collapse" href="#cardCollpase5" role="button"
                                                       aria-expanded="false" aria-controls="cardCollpase"><i
                                                            class="mdi mdi-minus"></i></a>
                                                    <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                                </div>
                                                <h5 class="card-title mb-0 text-white">Employer Details </h5>
                                            </div>
                                            <div id="cardCollpase5" class="collapse show">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="firstname"> Employer Name</label>
                                                                <input type="text" class="form-control"
                                                                       id="guarantor_employer_first_name"
                                                                       value="{{ $guarantor->guarantor_employer_first_name ?? "" }}"
                                                                       name="guarantor_employer_first_name"
                                                                       placeholder="Enter first name">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Employer Phone</label>
                                                                <input type="text" class="form-control"
                                                                       data-toggle="input-mask"
                                                                       data-mask-format="(000) 000-0000" id="phone"
                                                                       name="guarantor_employer_phone"
                                                                       value="{{ $guarantor->guarantor_employer_phone ?? "" }}"
                                                                       placeholder="Enter contact Number">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="res_address">Address </label>

                                                                <input type="text" class="form-control"
                                                                       id="employer_address"
                                                                       name="guarantor_employer_address"
                                                                       value="{{ $guarantor->guarantor_employer_address ?? "" }}"
                                                                       placeholder="Enter res_address">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="res_suburb">Suburb </label>
                                                                <input type="text" class="form-control" id="res_suburb"
                                                                       name="guarantor_employer_suburb"
                                                                       value="{{ $guarantor->guarantor_employer_suburb ?? "" }}"
                                                                       placeholder="Enter Suburb ">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="companyname">City </label>
                                                                <input type="text" class="form-control"
                                                                       id="employer_city"
                                                                       name="guarantor_employer_city"
                                                                       value="{{ $guarantor->guarantor_employer_city ?? "" }}"
                                                                       placeholder="Enter City">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="res_postal_code">Postal Code</label>
                                                                <input type="number" class="form-control"
                                                                       id="employer_postal_code"
                                                                       name="guarantor_employer_postal"
                                                                       value="{{ $guarantor->guarantor_employer_postal ?? "" }}"
                                                                       placeholder="Enter Postal Code">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                </div>
                                            </div>
                                        </div> <!-- end card-->
                                    </div>
                                </div>
                            @endforeach


                            <div id="b-other-payment">
                                <h5 class="text-uppercase mt-0 mb-3 bg-light p-2 text-center">Other Payment
                                    Methods </h5>

                                <div class="card">
                                    <div class="card-header bg-info py-3 text-white">
                                        <div class="card-widgets">
                                            <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                            <a data-toggle="collapse" href="#cardCollpase9" role="button"
                                               aria-expanded="false" aria-controls="cardCollpase2"><i
                                                    class="mdi mdi-minus"></i></a>
                                            <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                        </div>
                                        <h5 class="card-title mb-0 text-white">Other Payment Methods </h5>
                                    </div>
                                    <div id="cardCollpase9" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="example-textarea">
                                                            Please specify the details of the party paying for your
                                                            medical
                                                            care.
                                                            eg. give details of workman's compensation act claim.
                                                        </label>
                                                        <br>
                                                        <textarea class="form-control" id="payment_details"
                                                                  data="{{ $patients->payment_details ?? "" }}"
                                                                  name="payment_details" rows="5"></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div> <!-- end card-->

                            </div>


                        </div> <!-- end card-box -->

                    </div> <!-- end col-->


                </div>

                <div class="text-center mb-3">

                    <a class="btn w-sm btn-outline-info waves-effect"
                       href="{{ URL::route('patientManagement.index') }}">
                        Back
                    </a>
                    <button type="submit" class="btn w-sm btn-success waves-effect waves-light">Save
                    </button>
{{--                    <button type="button" class="btn w-sm btn-danger waves-effect waves-light">Delete--}}
{{--                    </button>--}}
                </div>

            </form>
        </div>
        <br><br>
        @include('patients.partials.add_dependencies')
        @include('patients.partials.terms_modal')
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

    {{--    <script src="../assets/"></script>--}}
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- Plugins js -->
    {{--    <script src="{{ asset('libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>--}}
    {{--    <script src="{{ asset('libs/autonumeric/autoNumeric-min.js') }}"></script>--}}
    {{--    <script src="{{ asset('js/pages/form-masks.init.js') }}"></script>--}}

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

        function deleteRecord(id) {
            console.log(id)
            location.href = "{{route('dependencies.destroy', '')}}"+"/"+id;
        }


        /**
         * change radio buttons for id number and passport
         * @type {*|jQuery|HTMLElement}
         */

        let d_id_number = $('#d_id_number');
        let d_passport = $('#d_passport');

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
            d_passport.hide();
            passport.hide();
            main_member.hide();
            some_one_guarantor.hide();
            guarantor_personal_details.hide();
            guarantor_contact_details.hide();
            guarantor_employment_details.hide();
            other_payment.hide();

        };
            @if( $patient->is_accepted === 0)
                {
                    window.onload = function () {
                        $('#terms-conditions-modal').modal('show');
                    };
                }
           @endif

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
            let $radios = $('input:radio[name=radioInline][value=d_id_number]');
            if ($radios.is(':checked') === false) {
                $radios.filter('[value=d_id_number]').prop('checked', true);
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
                    if (inputValue == 'd_id_number') {
                        d_id_number.show();
                        d_passport.hide();
                    } else if (inputValue == 'd_passport') {
                        d_passport.show();
                        d_id_number.hide();
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

            $('#add-dependencies').on('click', function () {
                let strUrl = '{{ route('dependencies.store') }}';
                let modalID = 'add-new-dependencies-modal';
                let formName = 'add-dependencies-form';
                let submitBtnID = 'add-dependencies';
                let redirectUrl = '{{route('patient_details.show', $patient->uuid)}}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            $('#save_terms').on('click', function () {
                // console.log('terms');
                let strUrl = '{{ route('acceptTerms') }}';
                let modalID = 'terms-conditions-modal';
                let formName = 'terms-conditions-form';
                let submitBtnID = 'save_terms';
                let redirectUrl = '';
                let successMsgTitle = 'Terms and Conditions accepted';
                let successMsg = 'Terms and Conditions accepted.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

        });


    </script>
@endsection
