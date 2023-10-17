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
                                                                @foreach($contacts as $contact)
                                                                    <option
                                                                        value="{{ $contact->email }}">{{ $contact->first_name." ". $contact->surname."|".$contact->company->name }}
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