@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/mohithg-switchery/switchery.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/summernote/summernote-bs4.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

@section('content')

    @section('content_data')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form method="post" action="{{ route('setup.store') }}" class=" card-body needs-validation"
                          novalidate enctype="multipart/form-data">
                        {{ csrf_field() }}

                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    &times;
                                </button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="projectname">Company Name </label>
                                    <input type="text" name="company_name" id="company_name"
                                           class="form-control"
                                           value="{{ $companyDetails->company_name ?? '' }}"
                                           placeholder="Enter company name">
                                </div>
                                <div class="form-group">
                                    <label for="projectname">Full Company Name</label>
                                    <input type="text" name="full_company_name" id="full_company_name"
                                           class="form-control"
                                           value="{{ $companyDetails->full_company_name ?? '' }}"
                                           placeholder="Enter full company name">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">Name On Header (Bold) <span
                                                    class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="header_name_bold"
                                                   name="header_name_bold"
                                                   value="{{ $companyDetails->header_name_bold ?? '' }}"
                                                   placeholder="Enter name of eader">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">Acronym On Header (Bold) <span
                                                    class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="header_acronym_bold"
                                                   name="header_acronym_bold"
                                                   value="{{ $companyDetails->header_acronym_bold ?? '' }}"
                                                   placeholder="Enter header acronym">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">Name On Header (Regular) <span
                                                    class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" id="header_name_regular"
                                                   name="header_name_regular"
                                                   value="{{ $companyDetails->header_name_regular ?? '' }}"
                                                   placeholder="Enter name of eader">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname">Acronym On Header (Regular)<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="header_acronym_regular"
                                                   name="header_acronym_regular"
                                                   value="{{ $companyDetails->header_acronym_regular ?? '' }}"
                                                   placeholder="Enter header acronym">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="projectname">Mailing Name </label>
                                    <input type="text" name="mailing_name" id="mailing_name"
                                           class="form-control"
                                           value="{{ $companyDetails->full_company_name ?? '' }}"
                                           placeholder="Enter mailing name ">
                                </div>
                                <div class="form-group">
                                    <label for="projectname">Mailing Address </label>
                                    <input type="email" name="mailing_address" id="mailing_address"
                                           class="form-control"
                                           value="{{ $companyDetails->mailing_address ?? '' }}"
                                           parsley-type="email" placeholder="Enter mailing Address ">
                                </div>
                                <div class="form-group">
                                    <label> Support E-Mail</label>
                                    <div>
                                        <input type="email" name="support_email" id="support_email"
                                               class="form-control" required
                                               value="{{ $companyDetails->support_email ?? '' }}"
                                               parsley-type="email" placeholder="Enter a valid support e-mail"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="project-budget">Password Duration (Months)</label>
                                    <input type="number" name="password_expiring_month"
                                           id="password_expiring_month" class="form-control"
                                           value="{{ $companyDetails->password_expiring_month ?? '' }}"
                                           placeholder="Enter project budget">
                                </div>
                            </div> <!-- end col-->
                            <div class="col-xl-6">
                                <div class="form-group mb-3">
                                    <label for="validationCustom01">Company Website</label>
                                    <input type="text" class="form-control" name="company_website"
                                           id="company_website"
                                           value="{{ $companyDetails->company_website ?? '' }}"
                                           placeholder="Compony Website " required>
                                    <div class="valid-feedback">
                                        Looks good!!!
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="res_address">Address </label>

                                            <input type="text" class="form-control" id="address"
                                                   name="address" value="{{ $companyDetails->address ?? '' }}"
                                                   placeholder="Enter address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="res_suburb">Suburb </label>
                                            <input type="text" class="form-control" id="suburb" name="suburb"
                                                   value="{{ $companyDetails->suburb ?? '' }}"
                                                   placeholder="Enter Suburb ">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="companyname">City </label>
                                            <input type="text" class="form-control" id="city" name="city"
                                                   value="{{ $companyDetails->city ?? '' }}" placeholder="Enter City">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="res_postal_code">Postal Code</label>
                                            <input type="number" class="form-control" id="postal_code"
                                                   name="postal_code" value="{{ $companyDetails->postal_code ?? '' }}"
                                                   placeholder="Enter Postal Code">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->
                                <div class="form-group mb-3">
                                    <label for="validationCustom04">Company Logo</label>
                                    <div class="mt-3">
                                        <input type="file" id="company_logo" name="company_logo"
                                               data-plugins="dropify"
                                               @if(!empty($companyDetails->company_logo))
                                                   data-default-file="{{ asset('uploads/'.$companyDetails->company_logo) }}"/>
                                        @endif
                                        <p class="text-muted text-center mt-2 mb-0">Company Logo</p>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="validationCustom04">Login Background Image</label>
                                    <div class="mt-3">
                                        <input type="file" name="login_background_image"
                                               id="login_background_image" data-plugins="dropify"
                                               @if(!empty($companyDetails->login_background_image))
                                                   data-default-file="{{ asset('uploads/'.$companyDetails->login_background_image) }}"/>
                                               @endif
                                        <p class="text-muted text-center mt-2 mb-0">Login Background Image</p>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                            <!-- end row -->
                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <button type="submit"
                                            class="btn btn-success waves-effect waves-light m-1"><i
                                            class="fe-check-circle mr-1"></i> Save
                                    </button>
                                    <button type="button"
                                            class="btn btn-light waves-effect waves-light m-1"><i
                                            class="fe-x mr-1"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form><!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    @stop
@endsection

@section('page_script')
    <script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>

    <!-- Validation init js-->
    <script src=" {{ asset('js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>

    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('libs/mohithg-switchery/switchery.min.js')}}"></script>
    <script src="{{ asset('libs/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/pages/form-summernote.init.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js')}}"></script>
    <script src="{{ asset('libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ asset('js/pages/form-advanced.init.js')}}"></script>

    <!-- Init js-->
    <script src="{{ asset('js/pages/form-fileuploads.init.js') }}"></script>

    <script>
        console.log('welcome')
    </script>
@stop




