<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Restaurant Management') }}</title>
    {{--    <link rel="shortcut icon" href="{{ $logo }} ">--}}
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
										
									</span>
                                    <h4></h4>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="float-right">
                            <p class="m-b-10"><strong> </strong> <span
                                    class="float-right"></span>
                            </p>
                            <p class="m-b-10"><strong></strong> <span
                                    class="float-right"> </span></p>
                            <p class="m-b-10"><strong> </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp;
                                   </span>
                            </p>
                            <p class="m-b-10"><strong> </strong> <span class="float-right"> &nbsp;&nbsp;&nbsp;&nbsp;
                                    </span>
                        </div>
                        <br><br><br>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        </div><!-- end col -->
                        <div class="col-md-4 offset-md-2">
                            <div class="mt-3 float-right">
                                <p class="m-b-10"><strong> Date : {{$date}}</strong> <span
                                        class="float-right"> &nbsp;&nbsp; </span>
                                </p>
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-xl-4 col-md-6 order-1">
							<div class="card">
								<div class="card-body">
									<div class="card-widgets">
										<a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
										<a data-toggle="collapse" href="#cardCollpase3" role="button" aria-expanded="false" aria-controls="cardCollpase3"><i class="mdi mdi-minus"></i></a>
										<a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
									</div>
									<h4 class="header-title mb-0">Reviews</h4>

									<div id="cardCollpase3" class="collapse pt-3 show">
										<div class="text-center">
											<div id="total-users" data-colors="#3283f6,#43bee1,#e3eaef,#fcc015"></div>
											<div class="row mt-3">
												<div class="col-4">
													<p class="text-muted font-15 mb-1 text-truncate">Target</p>
													<h4><i class="fe-arrow-down text-danger mr-1"></i>18k</h4>
												</div>
												<div class="col-4">
													<p class="text-muted font-15 mb-1 text-truncate">Last week</p>
													<h4><i class="fe-arrow-up text-success mr-1"></i>3.25k</h4>
												</div>
												<div class="col-4">
													<p class="text-muted font-15 mb-1 text-truncate">Last Month</p>
													<h4><i class="fe-arrow-up text-success mr-1"></i>28k</h4>
												</div>
											</div> <!-- end row -->
										</div>
									</div> <!-- collapsed end -->
								</div> <!-- end card-body -->
							</div> <!-- end card-->
						</div> <!-- end col-->
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
							   href="" target="_blank">
								<i class="mdi mdi-arrow-down-circle-outline mr-1"></i>
								Download
							</a>

						</div>
					</div>
                </div> <!-- end card-box -->
            </div> <!-- end col -->
        </div> <!-- content -->
    </div>
</div>
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
