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
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-waiter_sales_orders-tab"
                                       data-toggle="pill" href="#custom-v-pills-waiter_sales_orders" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Waiter Sales/Value of orders </a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-res_average_response-tab" data-toggle="pill"
                                       href="#custom-v-pills-res_average_response" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Restaurant Average response time</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-res_sales_orders-tab"
                                       data-toggle="pill" href="#custom-v-pills-res_sales_orders" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Restaurant Sales/Value of orders
                                    </a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-reviews-tab"
                                       data-toggle="pill" href="#custom-v-pills-reviews" role="tab"
                                       aria-controls="custom-v-pills-shipping"
                                       aria-selected="false">
                                        <i class="mdi mdi-truck-fast d-block font-10"></i>
                                        Reviews</a>
                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-table_turn_around_time-tab"
                                       data-toggle="pill" href="#custom-v-pills-table_turn_around_time" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Table turnaround time per table size</a>

                                    <a class="nav-link mt-2 py-2" id="custom-v-pills-popular_dishes-tab"
                                       data-toggle="pill" href="#custom-v-pills-popular_dishes" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Popular dishes</a>
										<a class="nav-link mt-2 py-2" id="custom-v-pills-popular_services-tab"
                                       data-toggle="pill" href="#custom-v-pills-popular_services" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Most popular "quick" service requests</a>
										<a class="nav-link mt-2 py-2" id="custom-v-pills-xpress_usage-tab"
                                       data-toggle="pill" href="#custom-v-pills-xpress_usage" role="tab"
                                       aria-controls="custom-v-pills-payment"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Xpresserv usage by patrons</a>
										<a class="nav-link mt-2 py-2" id="custom-v-pills-xpress_usage-tab"
                                       data-toggle="pill" href="#custom-v-pills-financials" role="tab"
                                       aria-controls="custom-v-pills-financials"
                                       aria-selected="false">
                                        <i class="mdi mdi-cash-multiple d-block font-10"></i>
                                        Financials</a>
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
                                                            <label for="heard"> Date Rangedmnjbfb </label>
                                                            <input type="text" id="dates" name="date_range" class="form-control">
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
                                    <div class="tab-pane fade" id="custom-v-pills-reviews" role="tabpanel"
                                         aria-labelledby="custom-v-pills-reviews-tab">
                                        <div>
                                            <h4 class="header-title">Reviews </h4>
											<form class="needs-validation" novalidate method="Post" action="reports/reviews-app"
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
                                                            <input type="text" id="date_reviews" name="date_range" class="form-control">
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
                                    <div class="tab-pane fade" id="custom-v-pills-res_average_response" role="tabpanel"
                                         aria-labelledby="custom-v-pills-res_average_response-tab">
                                        <div>
                                            <h4 class="header-title">Restaurant Average response time </h4>
                                            <form class="needs-validation" novalidate method="Post" action="reports/restaurant-turnaround-time"
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
                                                            <input type="text" id="date_res_time" name="date_range" class="form-control">
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
                                    <div class="tab-pane fade" id="custom-v-pills-table_turn_around_time" role="tabpanel"
                                         aria-labelledby="custom-v-pills-table_turn_around_time-tab">
                                        <div>
                                            <h4 class="header-title">  Table turnaround time per table size</h4>
                                                <br>
                                                <br>
                                            <form class="needs-validation" novalidate method="Post" action="reports/turnaround-table-size"
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
                                                                <i class="mdi mdi-truck-fast mr-1"></i>Generate</button>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                            </form>
                                            <!-- Pay with Paypal box-->
                                        </div>
                                    </div>
									<div class="tab-pane fade" id="custom-v-pills-popular_dishes" role="tabpanel"
                                         aria-labelledby="custom-v-pills-popular_dishes-tab">
										  <br>
                                                <br>
                                            <form class="needs-validation" novalidate method="Post" action="reports/popular-dish"
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
                                                            <input type="text" id="date_popular" name="date_range" class="form-control">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row mt-4">

                                                    <div class="col-sm-6">
                                                        <div class="text-sm-right mt-2 mt-sm-0">
                                                            <button type="submit"  class="btn btn-success">
                                                                <i class="mdi mdi-truck-fast mr-1"></i>Generate</button>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                            </form>
                                    </div>
									<div class="tab-pane fade" id="custom-v-pills-popular_services" role="tabpanel"
                                         aria-labelledby="custom-v-pills-popular_services-tab">
                                        <div>
                                            <h4 class="header-title"> Popular Quick Service requests</h4>
                                                <br>
                                                <br>
                                            <form class="needs-validation" novalidate method="Post" action="reports/popular-quick-services"
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
                                                            <input type="text" id="date_quick" name="date_range" class="form-control">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                                <div class="row mt-4">

                                                    <div class="col-sm-6">
                                                        <div class="text-sm-right mt-2 mt-sm-0">
                                                            <button type="submit"  class="btn btn-success">
                                                                <i class="mdi mdi-truck-fast mr-1"></i>Generate</button>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                            </form>
                                            <!-- Pay with Paypal box-->
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-v-pills-waiter_sales_orders" role="tabpanel"
                                         aria-labelledby="custom-v-pills-waiter_sales_orders-tab">
                                        <div>
                                            <h4 class="header-title"> Sales/Value of orders placed </h4>
											<form class="needs-validation" novalidate method="Post" action="report/sales-value-ordered"
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
                                                            <input type="text" id="dates_sales" name="date_range" class="form-control">
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
                                    <div class="tab-pane fade" id="custom-v-pills-res_sales_orders" role="tabpanel"
                                         aria-labelledby="custom-v-pills-res_sales_orders-tab">
                                        <div>
                                            <h4 class="header-title">Restaurant Sales/Value of orders </h4>
                                            <form class="needs-validation" novalidate method="Post" action="reports/restaurant-sales-volume"
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
                                                            <input type="text" id="date_res_sale_time" name="date_range" class="form-control">
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
                                    <div class="tab-pane fade" id="custom-v-pills-xpress_usage" role="tabpanel"
                                         aria-labelledby="custom-v-pills-xpress_usage-tab">
                                        <div>
                                            <h4 class="header-title">Xpresserv usage by patrons</h4>
											<form class="needs-validation" novalidate method="Post" action="reports/app-usage"
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
                                                            <input type="text" id="date_usage" name="date_range" class="form-control">
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
									<div class="tab-pane fade" id="custom-v-pills-financials" role="tabpanel"
                                         aria-labelledby="custom-v-pills-financials">
                                        <div>
                                            <h4 class="header-title">Financials</h4>
                                            <form class="needs-validation" novalidate method="Post" action="report/financials">
                                                {{ csrf_field() }}
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
	 $("#dates").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
	 $("#dates_sales").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
	 $("#date_reviews").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
	 $("#date_quick").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
	 $("#date_popular").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
	 $("#date_res_time").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
	 $("#date_res_sale_time").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
	 $("#date_usage").flatpickr({
         altInput: !0,
         mode: "range",
         // altFormat: "F j, y",
         // altFormat: "y,j,f",
         defaultDate: "today"
     });
 </script>
@endsection
