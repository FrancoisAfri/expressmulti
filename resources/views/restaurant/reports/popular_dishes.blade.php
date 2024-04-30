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
            <div class="col-12"  id="libInp">
                <div class="card-box">
                    <!-- Logo & title -->
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
                                <p style="text-align:center"><b>Popular Dishes</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mt-4 table-centered">
                                    <thead>
										<tr>
											<th>Item</th>
											<th>Category</th>
											<th>Menu Type</th>
											<th style="text-align:center">Number Sold</th>
										</tr>
                                    </thead>
                                    <tbody>
										@foreach($dishes as $dish)
											<tr>
												<td>{{ $dish->item->name ?? '' }}</td>
												<td>{{ $dish->item->categories->name ?? '' }}</td>
												<td>{{ $dish->item->menuType->name ?? '' }}</td>
												<td style="text-align:center">{{ $dish->total_sold ?? '' }}</td>
											</tr>
										@endforeach
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                    <div class="text-left">
                        <button type="button" id="back_button" class="btn btn-dark waves-effect waves-light"
                                data-dismiss="modal">
                            <i class="mdi mdi-skip-backward-outline"></i>
                            Back
                        </button>
						
                    </div>
                </div> <!-- end card-box -->
            </div> <!-- end col -->
        </div> <!-- content -->
    </div>
</div>
<!-- END wrapper -->
<script src="{{ asset('js/vendor.min.js') }}"></script>
<script src="{{ asset('libs/print-js/print.js') }}"></script>
<!-- App js -->
<script src="{{ asset('js/app.min.js') }}"></script>
<script src="{{ asset('libs/print-js/print.js') }}"></script>
<script>

    document.getElementById("back_button").onclick = function () {
        location.href = "/restaurant/reports";
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

