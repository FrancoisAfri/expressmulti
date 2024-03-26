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
                        <div class="row">
                            <div class="col-md-6">
                            </div><!-- end col -->
                            <div class="col-md-4 offset-md-2">
                                <div class="mt-3 float-right">
                                    <p class="m-b-10"><strong>Period : </strong> <span
                                            class="float-right"> </span>{{ $startDate ?? '' }}
                                        - {{ $endDate ?? ''  }}</span>
                                    </p>
                                </div>
                            </div><!-- end col -->
                        </div>
                        <br><br><br>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-3" style="text-align: center">
                                <H1><b>Daily Audit Consolidated Analysis</b></H1>
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
                                        <th></th>
                                        <th>Ledger</th>
                                        <th>Gross Incl</th>
                                        <th style="width: 10%">Deleted</th>
                                        <th style="width: 10%">Nett Incl </th>
                                        <th style="width: 10%">Vat</th>
                                        <th style="width: 10%">Nett Excl </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td> <h4 style="text-align: left">Income</h4> </td>
                                        <td> {{ 'Procedures / Consultations' ?? '' }} <br></td>
                                        <td> R {{ number_format($dailySummary  , 2, ',', '.') }} </td>
                                        <td></td>
                                        <td>R {{ number_format($dailySummary - ($dailySummary*  0.14) , 2, ',', '.') }}</td>
                                        <td>R {{ number_format( ($dailySummary*  0.14) , 2, ',', '.') }}</td>
                                        <td>R {{ number_format($dailySummary - ($dailySummary*  0.14) , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td style="font-weight: bold;">{{ number_format($dailySummary - ($dailySummary*  0.14) , 2, ',', '.') }}</td>
                                        <td style="font-weight: bold;">{{  number_format(0  , 2, ',', '.') }} </td>
                                        <td style="font-weight: bold;">{{ number_format($dailySummary - ($dailySummary*  0.14) , 2, ',', '.') }}</td>
                                        <td style="font-weight: bold;">{{ number_format( ($dailySummary*  0.14) , 2, ',', '.') }}</td>
                                        <td style="font-weight: bold;">{{ number_format($dailySummary - ($dailySummary*  0.14) , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td> <h4>Bank Account</h4>  </td>
                                    </tr>

                                    @if(!empty($cash))
                                        <tr>
                                            <td>  </td>
                                            <td>{{ 'Payment : Cash - Patient' ?? '' }}</td>
                                            <td> R {{ number_format($cash  , 2, ',', '.') }} </td>
                                            <td>R {{ $dff ?? 0.00 }}</td>
                                            <td>R {{ number_format($cash - ($cash*  0.14) , 2, ',', '.') }}</td>
                                            <td>R {{ number_format( ($cash*  0.14) , 2, ',', '.') }}</td>
                                            <td>R {{ number_format($cash - ($cash*  0.14) , 2, ',', '.') }}</td>
                                        </tr>
                                    @else
                                    @endif



                                    @if(!empty($eft))
                                        <tr>
                                            <td>  </td>
                                            <td>{{ 'Payment : EFT - Patient' ?? '' }}</td>
                                            <td> R {{ number_format($eft  , 2, ',', '.') }} </td>
                                            <td> </td>
                                            <td> R {{ number_format($eft  , 2, ',', '.') }} </td>
                                            <td> </td>
                                            <td> R {{ number_format($eft  , 2, ',', '.') }} </td>
                                        </tr>
                                    @else
                                    @endif

                                    @if(!empty($debitCard))
                                        <tr>
                                            <td>  </td>
                                            <td>{{ 'Payment : Debit Card - Patient' ?? '' }}</td>
                                            <td> R {{ number_format($debitCard  , 2, ',', '.') }} </td>
                                            <td> </td>
                                            <td>R {{ number_format($debitCard - ($debitCard*  0.14) , 2, ',', '.') }}</td>
                                            <td>R {{ number_format( ($debitCard *  0.14) , 2, ',', '.') }}</td>
                                            <td>R {{ number_format($debitCard - ($debitCard *  0.14) , 2, ',', '.') }}</td>
                                        </tr>
                                    @else
                                    @endif

                                    {{--                                    <tr>--}}
                                    {{--                                        <td></td>--}}
                                    {{--                                        <td></td>--}}
                                    {{--                                        <td style="font-weight:bold">R {{ number_format($cash+$eft+$debitCard  , 2, ',', '.') }}</td>--}}
                                    {{--                                        <td style="font-weight:bold">R {{ $deleted ?? 0.00 }}</td>--}}
                                    {{--                                        <td style="font-weight:bold">R {{ number_format($dailySummary - ($dailySummary*  0.14) , 2, ',', '.') }}</td>--}}
                                    {{--                                        <td style="font-weight:bold">R {{ number_format( ($dailySummary*  0.14) , 2, ',', '.') }}</td>--}}
                                    {{--                                        <td style="font-weight:bold">R {{ number_format($dailySummary - ($dailySummary*  0.14) , 2, ',', '.') }}</td>--}}
                                    {{--                                    </tr>--}}

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td style="font-weight:bold">R {{ number_format($cash+$eft+$debitCard  , 2, ',', '.') }}</td>
                                        <td style="font-weight:bold">{{  number_format(0  , 2, ',', '.') }} </td>
                                        <td style="font-weight:bold">R {{ number_format($cash+$eft+$debitCard  , 2, ',', '.') }}</td>
                                        <td style="font-weight:bold">{{  number_format(0  , 2, ',', '.') }} </td>
                                        <td style="font-weight:bold">R {{ number_format($cash+$eft+$debitCard  , 2, ',', '.') }}</td>
                                    </tr>



                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-10">
                            <div class="card-box">

                                <table class="table mt-4 table-centered">
                                    <thead>
                                    <tr>
                                        <th> <h4 style="text-align: left">INVOICE BASIS VAT SUMMARY</h4> </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    <tr>
                                        <td style=" font-size: 12px;">INCOME</td>
                                        <td style=" font-size: 12px;"> {{ number_format(0 , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td style=" font-size: 12px;">EXPENSES</td>
                                        <td style=" font-size: 12px;"> {{ number_format(0 , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td style=" font-size: 12px;">CREDIT NOTES</td>
                                        <td style=" font-size: 12px;"> {{ number_format(0 , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td>TOTAL BASIS VAT SUMMARY</td>

                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>
                                    </tr>


                                    </tbody>
                                </table>

                            </div>
                        </div><!-- end col -->
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="card-box">

                                <table class="table mt-4 table-centered">
                                    <thead>
                                    <tr>
                                        <th> <h4 style="text-align: left">Report Total (Consolidated  for all service providers)</h4> </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    <tr>
                                        <td style=" font-size: 12px;">Total debits (Income , Journal debits)</td>
                                        <td style=" font-size: 12px;"> {{ number_format(0 , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td style=" font-size: 12px;">Total Credits (Expenses , Credit note, Payments received</td>
                                        <td style="font-weight:bold">R {{ number_format($cash+$eft+$debitCard  , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td style=" font-size: 12px;">Change in book</td>
                                        <td style=" font-size: 12px;"> {{ number_format(0 , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td>Total Vat Inclusive-Invoice</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Total number of Transactions</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ $totalTransactions ?? 0 }}</td>
                                    </tr>

                                    <tr>
                                        <td>Total Patients</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ $totalPatients ?? 0 }}</td>
                                    </tr>

                                    <tr>
                                        <td>Total number of Invoices</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td> <h4 style="text-align: left">Income</h4> </td>
                                     <td></td>
                                    </tr>

                                    <tr>
                                        <td> Procedures / Consultations<br></td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td> <h4 style="text-align: left">Bank Account</h4> </td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>Payment : Cash - Patient</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Payment : EFT - Patient</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Payment : Debit - Patient</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td> <h4 style="text-align: left">Opening Balances</h4> </td>
                                        <td style="font-size: 12px; font-weight:bold"><h4>{{ number_format(0  , 2, ',', '.') }}</h4> </td>
                                    </tr>

                                    <tr>
                                        <td>Plus debits</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Less Credits</td>
                                        <td style="font-size: 12px; font-weight:bold"> {{ number_format(0  , 2, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td> <h4 style="text-align: left">Closing Balances</h4> </td>
                                        <td style="font-size: 12px; font-weight:bold"><h4>{{ number_format(0  , 2, ',', '.') }}</h4> </td>
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

