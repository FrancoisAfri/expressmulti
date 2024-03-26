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
                                <p><b>CREDIT ANALYSIS:DETAIL</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mt-4 table-centered">
                                    <thead>
                                    <tr>
                                        <th>Document Number</th>
                                        <th>Description </th>
                                        <th style="width: 10%">Account No</th>
                                        <th style="width: 10%">Medical Scheme </th>
                                        <th style="width: 10%">Amount</th>
                                        <th style="width: 10%">User </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($creditPayments as $credit)
                                        <tr>
                                            <td> {{$date}}<br><br>{{ $credit->invoice->invoice_number ?? '' }}</td>
                                            <td>{{ $credit->description ?? '' }}</td>
                                            <td>{{ $credit->account->account_number ?? '' }}</td>
                                            <td>{{ $credit->patient->getFullNameAttribute() ?? '' }}</td>
                                            @if($credit->invoice->invoice_type === 2)
                                                <td> Private</td>
                                            @elseif($credit->invoice->invoice_type === 3)
                                                <td>Insurance</td>
                                            @elseif($credit->invoice->invoice_type === 1)
                                                    <?php $id = isset($credit->invoice->medical_aid); ?>
                                                    <?php $scheme = \App\Models\MedicalAids::getMedicalAidsBYiD($id)?>
                                                <td>{{ $scheme->name }}</td>
                                            @endif

                                            <td> R {{ number_format($credit->paid  , 2, ',', '.') }} </td>
                                            <td>{{ $user->person->initial ?? $user->person->first_name }}</td>
                                        </tr>
                                        <tr>
                                            <td></td><td></td><td></td><td></td>
                                            <td>Sub Total :</td>
                                            <td > <b>R {{ number_format( $dailySummary, 2, ',', '.') }} </b> </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                    <!-- end row -->
                    <div class="mt-4 mb-1">
                        <div class="text-right d-print-none">
                            <a href="report/creditAnalysis"
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

