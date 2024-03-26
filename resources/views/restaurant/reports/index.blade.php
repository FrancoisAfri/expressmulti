@extends('layouts.main-layout')
@section('page_dependencies')
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection
{{-- Page content --}}
@section('content')
    @section('content_data')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="nav nav-pills flex-column navtab-bg nav-pills-tab text-center"
                                     id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active show py-2" id="custom-v-pills-waiter_responsi_time-tab"
                                       data-toggle="pill" href="#custom-v-pills-waiter_responsi_time" role="tab"
                                       aria-controls="custom-v-pills-billing"
                                       aria-selected="true">
                                        <i class="mdi mdi-account-circle d-block font-6"></i>
                                        Waiter Response time
                                    </a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-age_analysis_patient-tab"
                                       data-toggle="pill" href="#custom-v-pills-age_analysis_patient" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Waiter Sales/Value of orders </a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-medical_aid-tab" data-toggle="pill"
                                       href="#custom-v-pills-medical_aid" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Restaurant Average response time</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-daily_analsis-tab"
                                       data-toggle="pill" href="#custom-v-pills-daily_analsis" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Restaurant Sales/Value of orders
                                    </a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-account_detailed-tab"
                                       data-toggle="pill" href="#custom-v-pills-account_detailed" role="tab"
                                       aria-controls="custom-v-pills-shipping"
                                       aria-selected="false">
                                        <i class="mdi mdi-truck-fast d-block font-10"></i>
                                        Reviews</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-account_summary-tab"
                                       data-toggle="pill" href="#custom-v-pills-account_summary" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Table turnaround time per table size</a>

                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-credit_analysis-tab"
                                       data-toggle="pill" href="#custom-v-pills-credit_analysis" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Popular dishes</a>
										<a class="nav-link mt-2 py-2" id="custom-v-pills-credit_analysis-tab"
                                       data-toggle="pill" href="#custom-v-pills-credit_analysis" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Most popular "quick" service requests</a>
										<a class="nav-link mt-2 py-2" id="custom-v-pills-credit_analysis-tab"
                                       data-toggle="pill" href="#custom-v-pills-credit_analysis" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Xpresserv usage by patrons</a>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-sm-10">
                                <div class="tab-content p-3">
                                    <div class="tab-pane fade active show" id="custom-v-pills-waiter_responsi_time"
                                         role="tabpanel" aria-labelledby="custom-v-pills-waiter_responsi_time-tab">
                                        <div>
                                            <h4 class="header-title">Waiter Response time</h4>
											<form class="needs-validation" novalidate method="Post" action="report/waiter-response"
                                                  enctype="multipart/form-data" >
                                                {{ csrf_field() }}
                                                @if (count($errors) > 0)
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-lg-8 float-sm-right">
                                                        <div class="form-group">
                                                            <label for="employee_id"> Employees </label>
                                                            <select class="form-control select2 " style="width: 100%;"
																 id="employee_id" name="employee_id"   data-select2-id="1" tabindex="-1" aria-hidden="true">
																<option value="0">*** Select Employee ***</option>
																@foreach ($employees as $employee)
																	<option value="{{ $employee->id }}">{{ $employee->first_name." ".$employee->surname }}</option>
																@endforeach
															</select>
                                                        </div>
														<div class="form-group">
                                                            <label for="heard"> Date Range </label>
                                                            <input type="text" id="date" name="date_range" class="form-control">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row mt-4">

                                                    <div class="col-sm-6">
                                                        <div class="text-sm-right mt-2 mt-sm-0">
                                                            <button type="submit"  class="btn btn-success">
                                                                <i class="mdi mdi-truck-fast mr-1"></i>Genarate Report</button>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-v-pills-account_detailed" role="tabpanel"
                                         aria-labelledby="custom-v-pills-account_detailed-tab">
                                        <div>
                                            <h4 class="header-title">Accounts Transaction Detailed </h4>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-v-pills-daily_analsis" role="tabpanel"
                                         aria-labelledby="custom-v-pills-daily_analsis-tab">
                                        <div>
                                            <h4 class="header-title">Daily Audit Consolidated </h4>
                                            <form class="needs-validation" novalidate method="Post" action=""
                                                  enctype="multipart/form-data" >
                                                {{ csrf_field() }}
                                                @if (count($errors) > 0)
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-lg-8 float-sm-right">
                                                        <div class="form-group">
                                                            <label for="heard"> Date Range </label>
                                                            <input type="text" id="date" name="date_range" class="form-control">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row mt-4">

                                                    <div class="col-sm-6">
                                                        <div class="text-sm-right mt-2 mt-sm-0">
                                                            <button type="submit"  class="btn btn-success">
                                                                <i class="mdi mdi-truck-fast mr-1"></i>Genarate Report</button>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-v-pills-account_summary" role="tabpanel"
                                         aria-labelledby="custom-v-pills-account_summary-tab">
                                        <div>
                                            <h4 class="header-title">  Account Transaction Summary</h4>
                                                <br>
                                                <br>
                                            <form class="needs-validation" novalidate method="Post" action=""
                                                  enctype="multipart/form-data" >
                                                {{ csrf_field() }}
                                                @if (count($errors) > 0)
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <input type="hidden" name="client_id" value="">
                                                    <div class="col-lg-8 float-sm-right">
                                                        <div class="form-group">
                                                            <label for="heard"> Date Range </label>
                                                            <input type="text" id="range-datepicker" name="date_range" class="form-control">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row mt-4">

                                                    <div class="col-sm-6">
                                                        <div class="text-sm-right mt-2 mt-sm-0">
                                                            <button type="submit"  class="btn btn-success">
                                                                <i class="mdi mdi-truck-fast mr-1"></i>Print</button>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                            </form>
                                            <!-- Pay with Paypal box-->
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-v-pills-age_analysis_patient" role="tabpanel"
                                         aria-labelledby="custom-v-pills-age_analysis_patient-tab">
                                        <div>
                                            <h4 class="header-title">Patient Age Analysis </h4>
                                            <!-- Pay with Paypal box-->
                                            <!-- end Pay with Paypal box-->

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-v-pills-medical_aid" role="tabpanel"
                                         aria-labelledby="custom-v-pills-medical_aid-tab">
                                        <div>
                                            <h4 class="header-title">Medical Aid Analysis </h4>
                                            <!-- Pay with Paypal box-->
                                            <!-- end Pay with Paypal box-->

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-v-pills-credit_analysis" role="tabpanel"
                                         aria-labelledby="custom-v-pills-credit_analysis-tab">
                                        <div>
                                            <h4 class="header-title">Credit Analysis : Detail</h4>

                                            <!-- end Pay with Paypal box-->
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->
                    </div>
                </div>
            </div>
        </div>
    @endsection
@stop
@section('page_script')
    <!-- third party js -->
    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script
        src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script
        src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
    <!-- third party js ends -->
    <!-- Tickets js -->
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/pages/tickets.js') }}"></script>
    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>
 <script>
     $("#date").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
 </script>
@endsection
