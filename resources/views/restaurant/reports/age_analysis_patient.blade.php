<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Booking Admin') }}</title>
    {{-- <link rel="shortcut icon" href="{{ $logo }} ">--}}
    <!-- core:css -->
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- App css -->
    <link media="all" href="{{ asset('css/bootstrap-creative.min.css') }}" rel="stylesheet" type="text/css"
          id="bs-default-stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href=" {{ asset('css/app-creative.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet"/>
    <!-- icons -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- core:css -->
</head>
<body data-layout-mode="horizontal"
      data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
<!-- Begin page -->
<div id="wrapper">
    <div class="content-page">
        <div class="content">
            <div class="col-12" id="invoice">
                <div class="card-box">
                    <!-- Logo & title -->
                    <div class="clearfix">
                        <div class="float-left">
                            <div class="auth-logo">
                                <div class="logo logo-dark">
									<span class="logo-lg">
										 @if(!empty($invoiceDetails['letter_head']))
											<img
												src="{{   asset('uploads/'. $invoiceDetails['letter_head'])  ??  $name['logo'] }}"
												alt="" height="70" width="70">
										@else
											<img
												src="{{  $name['logo'] }}" alt="" height="70"
												width="70">
										@endif
									</span>
                                    <h4>{{ $name['header_name_bold'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="float-right">
                            <p class="m-b-10"><strong> </strong> <span
                                    class="float-right"> {{ $name['address'] ?? '' }}</span>
                            </p>
                            <p class="m-b-10"><strong></strong> <span
                                    class="float-right"> {{ $name['suburb'] ?? '' }}</span></p>
                            <p class="m-b-10"><strong> </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp;
                                    {{ $name['city'] ?? '' }}</span>
                            </p>
                            <p class="m-b-10"><strong> </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp;
                                    {{ $name['postal_code'] ?? '' }}</span>
                        </div>
                        <br><br><br>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        </div><!-- end col -->
                        <div class="col-md-4 offset-md-2">
                            <div class="mt-3 float-right">
                                <p class="m-b-10"><strong> Date : </strong> <span
                                        class="float-right"> &nbsp;&nbsp; {{ $date ?? '' }}</span>
                                </p>
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-3 align-content-left">
                                <p><b>Account Patient Age Analysis</b></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mt-4 table-centered">
                                    <thead>
                                    <tr>
                                        <th style="width: 5px; text-align: center;">Account No.</th>
                                        <th style="width: 20%">Account Name</th>
                                        <th style="width: 10%">Total </th>
                                        <th style="width: 10%">Unallocated <br>
                                            Ongeallokeer</th>
                                        <th style="width: 10%">Current</th>
                                        <th style="width: 10%">30 Days</th>
                                        <th style="width: 10%">60 Days</th>
                                        <th style="width: 10%">90 Days</th>
                                        <th style="width: 10%">120 Days</th>
                                        <th style="width: 10%">150+ Days</th>
                                        <th style="width: 10%">150+ Days</th>
                                        <th style="width: 10%">180 Days</th>
                                        <th style="width: 10%">210 Days</th>
                                        <th style="width: 10%">250+ Days</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($accountPayments) > 0)
                                        @foreach ($accountPayments as $key => $account)

                                                <?php $totals = ''; ?>

                                            @foreach($account->invoicePayments as $accountPayments)
                                                    <?php $totals = $accountPayments->date; ?>
                                            @endforeach
                                            <tr>
                                                <td>{{ $account->account_number ?? ''}}</td>
                                                <td>{{ $account->patient->first_name . ' ' . $account->patient->surname  ?? ''}}</td>
                                                <td>{{ number_format($account->total_patient_balance , 2, ',', '.') ?? 0.00 }}</td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ number_format($account->patient_current_analysis , 2, ',', '.') ?? 0.00 }}</td>
                                                <td>
                                                    {{ number_format($account->patient_thirty_analysis , 2, ',', '.') ?? 0.00 }}</td>
                                                <td>
                                                    {{ number_format($account->patient_sixthy_analysis , 2, ',', '.') ?? 0.00 }}</td>
                                                <td>
                                                    {{ number_format($account->patient_ninthy_analysis , 2, ',', '.') ?? 0.00 }}</td>
                                                <td>
                                                    {{ number_format($account->patient_one_twenty_analysis , 2, ',', '.') ?? 0.00 }}</td>

                                                <td>{{ number_format($account->patient_one_fithy_analysis , 2, ',', '.') ?? 0.00 }}</td>

                                                <td>{{ number_format($account->patient_one_eighty_analysis , 2, ',', '.') ?? 0.00 }}</td>

                                                <td>{{ number_format($account->patient_two_hundred_analysis , 2, ',', '.') ?? 0.00 }}</td>

                                                <td>{{ number_format($account->patient_two_hundred_analysis , 2, ',', '.') ?? 0.00 }}</td>


                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="categories-list">
                                            <td colspan="5">
                                                <div class="alert alert-danger alert-dismissable"
                                                     style="text-align: center">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">
                                                        &times;
                                                    </button>
                                                    No Data to display ....
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                    <!-- end row -->
                    <div class="mt-4 mb-1">
                        <div class="text-right d-print-none">
{{--                            <button id="printInvoice" class="btn btn-primary waves-effect waves-light"><i--}}
{{--                                    class="mdi mdi-printer mr-1"></i> Print--}}
{{--                            </button>--}}
                            <a href="{{route('reports.patientAgeAnalysis')}}"
                               class="btn btn-primary waves-effect waves-light"><i
                                    class="mdi mdi-printer mr-1" target="_blank"></i> Generate Report
                            </a>
                        </div>
                    </div>
                </div> <!-- end card-box -->
            </div> <!-- end col -->
        </div> <!-- content -->
    </div>
</div>
<!-- END wrapper -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<!-- App js -->
<script src="{{ asset('js/app.min.js') }}"></script>
<script src="{{ asset('libs/print-js/print.js') }}"></script>
<script>
    $('#printInvoice').click(function () {
        window.print();
        document.margin = 'none';
        return true;
    });
</script>
</body>
