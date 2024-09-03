<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-family: Arial;
        }

        header {
            padding: 10px 0;
            margin-bottom: 30px;
        }

        #logo {
            text-align: right;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        h1 {
            border-top: 1px solid  #5D6975;
            border-bottom: 1px solid  #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url(dimension.png);
        }

        #project {
            float: left;
        }

        #project span {
            color: #5D6975;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }

        #company {
            float: right;
            text-align: right;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 20px;
            text-align: right;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table td.grand {
            border-top: 1px solid #5D6975;;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>
<body>
<header class="clearfix">
    <div id="logo">
		@if(file_exists(public_path($logo)))
			<img src="{{ public_path($logo) }}" class="img-fluid" alt="Company Logo">
			
		@else
			<p>No logo available</p>
		@endif
        <p class="mb-1" align="left">Invoice Number: <span>#{{ $invoice_number }}</span></p>
        <p class="mb-1" align="left">Invoice Date: <span>{{ $date }}</span></p>
    </div>
    <h1></h1>

    <div id="company" class="clearfix">
		<h4 class="inv-title-1">Invoice To</h4>
        <div>{{ $company->trading_as ?? '' }}</div>
        <div>{{ $company->res_address ?? '' }}</div>
        <div><a href="mailto:{{ $company->email ?? '' }}">{{ $company->email ?? '' }}</a></div>
    </div>
    <div id="project">
        <h4 class="inv-title-1">Invoice From</h4>
        <div>{{ $company_details['company_name'] }}</div>
        <div>{{ $company_details['address'] }}</div>
        <div>{{ $company_details['suburb'] }}</div>
        <div>{{ $company_details['city'] }}</div>
        <div>{{ $company_details['postal_code'] }}</div>
        <div><a href="mailto:{{ $company_details['mailing_address'] }}">{{ $company_details['mailing_address'] }}</a></div>

    </div>

</header>
<main>
    <table>
        <thead>
			<tr>
				<th style="text-align: center">Item</th>
				<th style="text-align: center">PRICE</th>
				<th style="text-align: center">QTY</th>
				<th style="text-align: right">TOTAL</th>
			</tr>
        </thead>
        <tbody>
			<tr>
				<td style="text-align: center">{{ $company->packages->package_name ?? '' }}</td>
				<td style="text-align: center">R {{ $company->packages->price ?? '' }}</td>
				<td style="text-align: center">1</td>
				<td style="text-align: right">R {{ $company->packages->price ?? '' }}</td>
			</tr>
			<tr>
				<td colspan="3">SUBTOTAL</td>
			   <td class="total">R {{ number_format($company->packages->price, 2 ) ?? '' }}</td>
			</tr>
			@php
				$amount = $company->packages->price;
				$taxPercentage = 15;
				$taxAmount = ($amount * $taxPercentage) / 100;
			@endphp
			<tr>
				<td colspan="3">VAT 15%</td>
			   <td class="total">R{{ number_format($taxAmount, 2) }}</td>
			</tr>
			<tr>
				<td colspan="3" class="grand total">GRAND TOTAL</td>
				<td class="grand total">R{{ number_format($amount + $taxAmount, 2) }}</td>
			</tr>
        </tbody>
    </table>
    <div id="notices">
        <div>Term & Conditions:</div>
        <div class="notice">THANK YOU FOR YOUR BUSINESS <br>
            We will automatically charge the outstanding amount to your Bank Account.<br>
            If your account has changed or you know of any other problem,<br>
            kindly get in touch with us to avoid possible billing problems..</div>
    </div>
</main>
<footer>

</footer>
</body>
</html>
