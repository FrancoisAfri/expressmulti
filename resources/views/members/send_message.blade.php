@extends('layouts.main-layout')
@section('page_dependencies')
    <link href="{{ asset('libs/mohithg-switchery/switchery.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/summernote/summernote-bs4.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/mystyle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/anotherStyle.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    @section('content_data')
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="nav nav-pills flex-column navtab-bg nav-pills-tab text-center"
                                             id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <br>
                                            <a class="nav-link active show py-2" id="custom-v-pills-billing-tab"
                                               data-toggle="pill" href="#custom-v-pills-billing" role="tab"
                                               aria-controls="custom-v-pills-billing"
                                               aria-selected="true">
                                                <i class="mdi mdi-email-edit d-block font-24"></i>
                                               Send Emails
                                            </a>
                                            <br> <br>
                                            <div class="card-box">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="avatar-sm bg-soft-info rounded">
                                                            <i class="fe-message-square font-22 text-info"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-right">
                                                            <h3 class="text-dark my-1"><span data-plugin="counterup">{{ !empty($SmsTracker->sms_count) ? $SmsTracker->sms_count: 0 }}</span></h3>
                                                            <p class="text-muted mb-1 text-truncate">Available Sms Units</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--<div class="mt-3">
                                                    <h6 class="text-uppercase">Target <span class="float-right">49%</span></h6>
                                                    <div class="progress progress-sm m-0">
                                                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="49" aria-valuemin="0" aria-valuemax="100" style="width: 49%">
                                                            <span class="sr-only">49% Available</span>
                                                        </div>
                                                    </div>
                                                </div>-->
                                            </div>
                                            <br>
                                            <a class="nav-link mt-2 py-2" id="custom-v-pills-shipping-tab"
                                               data-toggle="pill" href="#custom-v-pills-shipping" role="tab"
                                               aria-controls="custom-v-pills-shipping"
                                               aria-selected="false">
                                                <i class="mdi mdi-cellphone-message d-block font-24"></i>
                                                Send Sms</a>
                                        </div>
                                    </div> <!-- end col-->
                                    <div class="col-lg-8">
                                        <div class="tab-content p-3">
                                            <div class="tab-pane fade active show" id="custom-v-pills-billing"
                                                 role="tabpanel" aria-labelledby="custom-v-pills-billing-tab">
                                                <div>
                                                    <form class="needs-validation" novalidate method="post"
                                                          action="{{ route('sendMessages.sendEmail') }}">
                                                        {{ csrf_field() }}
                                                        @if (session('error'))
                                                            <div class="alert alert-danger">
                                                                {{ session('error') }}
                                                            </div>
                                                        @endif
                                                        <div class="row ">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body ">
                                                                        <h4 class="header-title">Send to all Clients</h4>
                                                                        <div class="row">
                                                                            <div class="checkbox checkbox-primary form-check-inline">
                                                                                <input  class="toggle-button" id="toggle" type="checkbox"
                                                                                        name="toggle" data-checked="allMessages,coreAnnuallyText"
                                                                                        data-not-checked="coreMonthlyPrice,coreMonthlyText">
                                                                                <label for="toggle">  </label>
                                                                            </div>
                                                                        </div>
                                                                    </div> <!-- end card-body -->
                                                                </div> <!-- end card-->
                                                            </div> <!-- end col -->
                                                        </div>
                                                        <div id="allMessages" class="form-group">
                                                            <label>Send To </label>
                                                            <select class="form-control select2-multiple" name="email_address[]"
                                                                  id="email_address"  data-toggle="select2" multiple="multiple"
                                                                    data-placeholder="Choose ...">
                                                                <option value="">Select email address...</option>
                                                                @foreach($users as $userEmails)
                                                                    <option
                                                                        value="{{ $userEmails->email }}">{{ $userEmails->first_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div> <!-- end col -->
                                                        <input type="hidden" id="type" name="communication_type"
                                                               value="1">
                                                        <div class="form-group">
                                                            <input type="text" name="subject" class="form-control"
                                                                   placeholder="Subject" required>
                                                            <div class="invalid-feedback">
                                                                Please provide Subject.
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h4 class="header-title">Default Editor</h4>
                                                                        <!-- basic summernote-->
                                                                        <textarea id="summernote-basic" name="details">
                                                                        </textarea>
                                                                    </div> <!-- end card-body-->
                                                                </div> <!-- end card-->
                                                            </div><!-- end col -->
                                                        </div>
                                                        <div class="form-group m-b-0">
                                                            <div class="text-right">
                                                                <button
                                                                    class="btn btn-primary waves-effect waves-light">
                                                                    <span>Send</span> <i
                                                                        class="mdi mdi-send ml-2"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="custom-v-pills-shipping" role="tabpanel"
                                                 aria-labelledby="custom-v-pills-shipping-tab">
                                                <div>
                                                    <form class="needs-validation" novalidate method="post"
                                                          action="{{ route('sendMessages.sendSms') }}">
                                                        {{ csrf_field() }}
                                                        @if (session('error'))
                                                            <div class="alert alert-danger">
                                                                {{ session('error') }}
                                                            </div>
                                                        @endif
                                                        <div class="row ">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body ">
                                                                        <h4 class="header-title">Send to all Clients</h4>
                                                                        <div class="row">
                                                                            <div class="checkbox checkbox-primary form-check-inline">
                                                                                <input  class="toggle-button" id="toggle_sms" type="checkbox"
                                                                                        name="toggle_sms" data-checked="allMessages,coreAnnuallyText"
                                                                                        data-not-checked="coreMonthlyPrice,coreMonthlyText">
                                                                                <label for="toggle_sms">  </label>
                                                                            </div>
                                                                        </div>
                                                                    </div> <!-- end card-body -->
                                                                </div> <!-- end card-->
                                                            </div> <!-- end col -->
                                                        </div>
                                                        <div id="allSmsmMessages" class="form-group">
                                                            <label>Send To </label>
                                                            <select class="form-control select2-multiple" name="cell_number[]"
                                                                    data-toggle="select2" multiple="multiple"
                                                                    data-placeholder="Choose ...">
                                                                @foreach($users as $smsContacts)
                                                                    <option
                                                                        value="{{ $smsContacts->phone_number }}">{{ $smsContacts->first_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div> <!-- end col -->
                                                        <input type="hidden" id="type" name="communication_type"
                                                               value="2">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h4 class="header-title">Default Editor</h4>
                                                                        <!-- basic summernote-->
                                                                        <textarea class="form-control" id="example-textarea" name="sms_content" rows="5">
                                                                        </textarea>
                                                                    </div> <!-- end card-body-->
                                                                </div> <!-- end card-->
                                                            </div><!-- end col -->
                                                        </div>
                                                        <div class="form-group m-b-0">
                                                            <div class="text-right">
                                                                <button
                                                                    class="btn btn-primary waves-effect waves-light">
                                                                    <span>Send</span> <i
                                                                        class="mdi mdi-send ml-2"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end col-->
                                </div> <!-- end row-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div> <!-- container -->
        </div> <!-- content -->
    @endsection
@stop
@section('page_script')
{{--    <script src="{{ asset('js/vendor.min.js') }}"></script>--}}
    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('libs/mohithg-switchery/switchery.min.js')}}"></script>
    <script src="{{ asset('libs/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/pages/form-summernote.init.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js')}}"></script>
    <script src="{{ asset('libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ asset('js/pages/form-advanced.init.js')}}"></script>
    <script>
        $(function () {
            $(document).ready(function() {
                let checkBoxes = $("input[name='toggle']");
                toggle();
                $("#toggle").click(function() {
                    toggle();
                });
                function toggle() {
                    if (checkBoxes.prop("checked")) {
                        $('#allMessages').hide();
                    } else {
                        $('#allMessages').show();
                    }
                }
            });

            $(document).ready(function() {
                let checkBoxes = $("input[name='toggle_sms']");
                toggle();
                $("#toggle_sms").click(function() {
                    toggle();
                });

                function toggle() {
                    if (checkBoxes.prop("checked")) {
                        $('#allSmsmMessages').hide();
                    } else {
                        $('#allSmsmMessages').show();
                    }
                }
            });
        });
    </script>
@endsection