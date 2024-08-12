<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Montly Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('qoutes/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('qoutes/fonts/font-awesome/css/font-awesome.min.css') }}">

    <!-- Favicon icon -->
{{--    <link rel="shortcut icon" href="{{ asset('qoutes/img/favicon.ico')}}" type="image/x-icon" >--}}

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('qoutes/css/style.css')}}">
</head>
<body>

<!-- Invoice 2 start -->
<div class="invoice-2 invoice-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="invoice-inner clearfix">
                    <div class="invoice-info clearfix" id="invoice_wrapper">
                        <div class="invoice-headar">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="invoice-logo">
                                        <!-- logo started -->
                                        <div class="logo">

                                            <img src="{{ asset($company_details['logo']) }}" alt="logo">
                                        </div>
                                        <!-- logo ended -->
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="invoice-id">
                                        <div class="info">
                                            <h1 class="inv-header-1">Invoice</h1>
                                            <p class="mb-1">Invoice Number: <span>#{{ $invoice_number }}</span></p>
                                            <p class="mb-0">Invoice Date: <span>{{ $date }}</span></p>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($companies as $company)
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="invoice-number mb-30">
                                        <h4 class="inv-title-1">Invoice To</h4>
                                        <h2 class="name"> {{ $company->name ?? '' }}</h2>
                                        <p class="invo-addr-1">
                                            {{ $company->trading_as ?? '' }}<br/>
                                            {{ $company->email ?? '' }} <br/>
                                            {{ $company->res_address ?? '' }} <br/>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="invoice-number mb-30">
                                        <div class="invoice-number-inner">
                                            <h4 class="inv-title-1">Invoice From</h4>
                                            <h2 class="name">{{ $company_details['company_name'] }}</h2>
                                            <p class="invo-addr-1">
                                                {{ $company_details['address'] }} <br/>
                                                {{ $company_details['suburb'] }} <br/>
                                                {{ $company_details['city'] }} <br/>
                                                {{ $company_details['postal_code'] }} <br/>
                                                {{ $company_details['mailing_address'] }} <br/>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-center">
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped invoice-table">
                                    <thead class="bg-active">
                                    <tr class="tr">
                                        <th>No.</th>
                                        <th class="pl0 text-start">Item Description</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="tr">
                                        <td>
                                            <div class="item-desc-1">
                                                <span>{{ $company->packages->id  ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td class="pl0"> {{ $company->packages->package_name ?? '' }}</td>
                                        <td class="text-center">R {{ number_format($company->packages->price, 2 ) ?? '' }}</td>
                                        <td class="text-center">{{ $company->packages->no_table ?? '' }}</td>
                                        <td class="text-end">R {{ number_format($company->packages->price, 2 ) ?? '' }}</td>
                                    </tr>

                                    <tr class="tr2">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">SubTotal</td>
                                        <td class="text-end"> R {{ number_format($company->packages->price, 2 ) ?? '' }}</td>
                                    </tr>

                                    @php
                                        $amount = $company->packages->price ; // Replace with your actual amount or variable
                                        $taxPercentage = 15;
                                        $taxAmount = ($amount * $taxPercentage) / 100;
                                    @endphp

                                    <tr class="tr2">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">Tax</td>
                                        <td class="text-end">R{{ number_format($amount + $taxAmount, 2) }}</td>
                                    </tr>
                                    <tr class="tr2">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center f-w-600 active-color">Grand Total</td>
                                        <td class="f-w-600 text-end active-color">R{{ number_format($amount + $taxAmount, 2) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="invoice-bottom">
                            <div class="row">
                                <div class="col-lg-6 col-md-5 col-sm-5">
                                    <div class="payment-method mb-30">
                                        <h3 class="inv-title-1">Payment Method</h3>
                                        <ul class="payment-method-list-1 text-14">
                                            <li><strong>Account No:</strong> 00 123 647 840</li>
                                            <li><strong>Account Name:</strong> Jhon Doe</li>
                                            <li><strong>Branch Name:</strong> xyz</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-7 col-sm-7">
                                    <div class="terms-conditions mb-30">
                                        <h3 class="inv-title-1">Terms & Conditions</h3>
                                        <p>THANK YOU FOR YOUR BUSINESS <br>
                                            We will automatically charge the outstanding amount to your Bank Account.<br>
                                            If your account has changed or you know of any other problem,<br>
                                            kindly get in touch with us to avoid possible billing problems.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-contact clearfix">
                            <div class="row g-0">
                                <div class="col-sm-12">
                                    <div class="contact-info clearfix">
                                        <a href="tel:+55-4XX-634-7071" class="d-flex"><i class="fa fa-phone"></i> +00 123 647 840</a>
                                        <a href="tel:info@themevessel.com" class="d-flex"><i class="fa fa-envelope"></i> info@themevessel.com</a>
                                        <a href="tel:info@themevessel.com" class="mr-0 d-flex d-none-580"><i class="fa fa-map-marker"></i> 169 Teroghoria, Bangladesh</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Invoice 2 end -->

<script src="{{ asset('qoutes/js/jquery.min.js') }}"></script>
<script src="{{ asset('qoutes/js/jspdf.min.js') }}"></script>
<script src="{{ asset('qoutes/js/html2canvas.js)') }}"></script>
<script src="{{ asset('qoutes/js/app.js') }}"></script>
</body>
</html>
