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

    {{--    <link rel="shortcut icon" href="{{ $logo }} ">--}}

    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" href="https://printjs4de6.kxcdn.com/print.min.css"/>
    <!-- core:css -->
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"/>

    <!-- App css -->
    <link media="all" href="{{ asset('css/bootstrap-creative.min.css') }}" rel="stylesheet" type="text/css"
          id="bs-default-stylesheet"/>

    <style>

    </style>

    <style type="text/css" media="print">

        @media screen and (max-width: 8.5in) {
            /* resize your window until the event is triggered */
            html {
                width: 8.5in;
            }

            body {
                font: 9pt/1.5 Arial, sans-serif;
            }
        }

        /*@page {*/
        /*    size: A4 landscape;*/
        /*}*/
    </style>


    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link href=" {{ asset('css/app-creative.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet"/>
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet') }}"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-select-bs4/css//select.bootstrap4.min.css" rel="stylesheet') }}"
          type="text/css"/>

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

            <div class="col-12" >
                <div class="card-box" id="libInp">
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
                        <br><br><br>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-3" style="text-align: center">
                                <H1><b>Age Analysis</b></H1>
                            </div>
                        </div>
                    </div>


                    <?php $owed_balance = 0; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                {{--                                <table class="table mt-4 table-centered">--}}
                                <table class="table table-striped dt-responsive nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th style="font-size: 12px; width: 2% ">Account No.</th>
                                        <th style="font-size: 12px; width: 2% ">Details</th>
                                        <th style="font-size: 12px; width: 2% ">Total Balance</th>
                                        <th style="font-size: 12px; width: 2% ">Medical Scheme Balance</th>
                                        <th style="font-size: 12px; width: 2% ">Insurance Balance</th>
                                        <th style="font-size: 12px; width: 2% ">Patient Balance</th>
                                        <th style="font-size: 12px; width: 2% ">Current</th><th style="font-size: 12px; width: 2% ">30 Days</th>
                                        <th style="font-size: 12px; width: 2% ">60 Days</th>
                                        <th style="font-size: 12px; width: 2% ">90 Days</th>
                                        <th style="font-size: 12px; width: 2% ">120 Days</th>
                                        <th style="font-size: 12px; width: 2% ">150+ Days</th>
                                        <th style="font-size: 12px; width: 2% ">Last Paid Date</th>
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
                                                <td style=" font-size: 12px;">{{ $account->account_number ?? ''}}</td>
                                                <td style=" font-size: 12px;">{{ $account->patient->first_name . ' ' . $account->patient->surname  ?? ''}}</td>
                                                <td style=" font-size: 12px;">{{ number_format($account->total_balance, 2, ',', '.') ?? 0.00 }}</td>
                                                <td style=" font-size: 12px;">{{ number_format($account->total_medical_aids_balance , 2, ',', '.') ?? 0.00}}</td>
                                                <td style=" font-size: 12px;">{{ number_format($account->total_isuarance , 2, ',', '.') ?? 0.00}}</td>
                                                <td style=" font-size: 12px;">{{ number_format($account->total_patient_balance , 2, ',', '.') ?? 0.00 }}</td>

                                                <td style=" font-size: 12px;">{{ number_format($account->current_balance , 2, ',', '.') ?? 0.00 }}</td>
                                                <td style=" font-size: 12px;">
                                                    {{ number_format($account->thirty_balance , 2, ',', '.') ?? 0.00 }}</td>
                                                <td style=" font-size: 12px;">
                                                    {{ number_format($account->sixthy_balance , 2, ',', '.') ?? 0.00 }}</td>
                                                <td style=" font-size: 12px;">
                                                    {{ number_format($account->ninthy_balance , 2, ',', '.') ?? 0.00 }}</td>
                                                <td style=" font-size: 12px;">
                                                    {{ number_format($account->one_twenty_balance , 2, ',', '.') ?? 0.00 }}</td>
                                                <td style=" font-size: 12px;">
                                                    {{ number_format($account->one_fithy_balance , 2, ',', '.') ?? 0.00 }}</td>

                                                <td style=" font-size: 12px;">{{ $account->last_payment_date  }}</td>

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

                    <div class="row">
                        <div class="col-6">
                            <div class="card-box">

                                <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                                       id="tickets-table">
                                    <thead>
                                    <tr>
                                        <th style="font-size: 12px; width: 2% ">SUMMARY</th>
                                        <th style="font-size: 12px; width: 2% ">TOTAL</th>
                                        <th style="font-size: 12px; width: 2% ">FACTOR</th>
                                        <th style="font-size: 12px; width: 2% ">SCHEME</th>
                                        <th style="font-size: 12px; width: 2% ">FACTOR</th>
                                        <th style="font-size: 12px; width: 2% ">INSURANCE</th>
                                        <th style="font-size: 12px; width: 2% ">FACTOR</th>
                                        <th style="font-size: 12px; width: 2% ">PATIENT</th>
                                        <th style="font-size: 12px; width: 2% ">FACTOR</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <tr>
                                        <td style="font-size: 13px; width: 2% ">CURRENT</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->current_balanceTotal , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->current_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->credit_medical , 2, ',', '.') ?? 0.00 }} </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->current_scheme_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->current_insurance_factor , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format( $account->insuarance_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->credit_patience , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->current_patient_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 13px; width: 2% ">30 Days</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->thirty_balanceTotal , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->thirty_day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->thirty_medical , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->thirty_scheme__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->thirty_insuarance , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->thirty_insurance__day_factor , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->thirty_patience , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->thirty_patient__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 13px; width: 2% ">60 Days</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->sixthy_balance , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->sixthy_day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->sixthy_medical , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->sixthy_scheme__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->sixthy_indurance , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->sixthy_insurance__day_factor , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->sixthy_patience , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->sixthy_patient__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 13px; width: 2% ">90 Days</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->ninthy_balance , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->ninthy_day_factor  , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->ninthy_medical , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->ninthy_scheme__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->ninthy_indurance , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->ninthy_insurance__day_factor , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->ninthy_patience , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->ninthy_patient__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 13px; width: 2% ">120 Days</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_twenty_balance , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_twenty_day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_twenty_medical , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_twenty_scheme__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_twenty_indurance , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_twenty_insurance__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_twenty_patience , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_twenty_patient__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 13px; width: 2% ">150 Days</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_fithy_balance , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_fithy_day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_fithy_medical , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_fithy_scheme__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_fithyy_indurance , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_fithy_insurance__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_fithyy_patience , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->one_fithy_patient__day_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-weight: bold;">Total BALANCE</td>
                                        <td style="font-size: 12px; font-weight: bold; border-style: solid; padding: 2px 4px">
                                            <h5>{{ number_format($account->ageAnalysisTotalBalanceAccount , 2, ',', '.') ?? 0.00 }}</h5>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 12px; font-weight: bold;">PATIENT BALANCE</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->balanceAgeAnalysisTotalPatient , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->patient_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 12px; font-weight: bold;">MEDICAL SCHEME BALANCE</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->total_balance_medical , 2, ',', '.') ?? 0.00 }}</td>
                                        <td style=" font-size: 12px;">{{ number_format($account->medical_factor , 2, ',', '.') ?? 0.00 }}
                                            %
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 12px; font-weight: bold;">INSURANCE BALANCE</td>
                                        <td>{{ number_format($account->totalAgeAnalysisInsuarance , 2, ',', '.') ?? 0.00 }}</td>
                                        <td>{{ number_format( $account->insuarance_factor , 2, ',', '.') ?? 0.00 }}%
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    </tbody>
                                </table>


                            </div>
                        </div><!-- end col -->
                    </div>

                </div>

                <!-- end row -->

                <div class="mt-4 mb-1">
                    <div class="text-right d-print-none">

                        <div class="text-left">
                            <button type="button" id="back_button" class="btn btn-dark waves-effect waves-light"
                                    data-dismiss="modal">
                                <i class="mdi mdi-skip-backward-outline"></i>
                                Back
                            </button>
                        </div>

                        <button id="libBtnWrap" onclick="libPrint()" class="btn btn-primary waves-effect waves-light"><i
                                class="mdi mdi-printer mr-1"></i> Print
                        </button>

                        <a class="btn btn-blue waves-effect waves-light"
                           href="{{route('invoice.downloadInvoice',1)}}" target="_blank">
                            <i class="mdi mdi-arrow-down-circle-outline mr-1"></i>
                            Download
                        </a>

                    </div>
                </div>
                {{--                    @endforeach--}}

            </div> <!-- end card-box -->
        </div> <!-- end col -->

    </div> <!-- content -->

</div>


<!-- END wrapper -->


<script src="{{ asset('js/vendor.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('js/app.min.js') }}"></script>
<script src="{{ asset('libs/print-js/print.js') }}"></script>
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>
<script src="{{ asset('libs/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{ asset('libs/pdfmake/build/vfs_fonts.js')}}"></script>
<!-- third party js ends -->

<!-- Datatables init -->
<script src="{{ asset('js/pages/datatables.init.js')}}"></script>

<script>

    document.getElementById("back_button").onclick = function () {
        location.href = "/billing/reports";
    };

    const libInpEl = document.getElementById("libInp");
    const libBtnWrapEl = document.getElementById("libBtnWrap");

    function libPrint() {
        libBtnWrapEl.style.display = '@page { size: Letter landscape; }'
        printJS('libInp', 'html');
    }

    $('#printInvoice').click(function () {
        window.print();
        document.margin = 'none';
        return true;
    });
</script>

</body>

