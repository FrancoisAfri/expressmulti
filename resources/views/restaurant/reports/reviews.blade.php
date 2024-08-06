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
                        <div class="col-md-6">
                            <div class="mt-3 align-content-left">
                                <pstyle="text-align:center"><b>Reviews</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mt-4 table-centered">
                                    <thead>
                                    <tr>
										<th style="text-align:center">Ambience</th>
										<th style="text-align:center">Food</th>
										<th style="text-align:center">Service</th>
										<th style="text-align:center">Rate Our App</th>
                                    </tr>
                                    </thead>
                                    <tbody>
										@if (!empty($ratings))
												@foreach($ratings as $rating)
													<tr>
														<td style="text-align:center">{{ !empty($rating->avg_q_one) ? $rating->avg_q_one : '' }}</td>
														<td style="text-align:center">{{ !empty($rating->avg_q_two) ? $rating->avg_q_two : '' }}</td>
														<td style="text-align:center">{{ !empty($rating->avg_q_three) ? $rating->avg_q_three: '' }}</td>
														<td style="text-align:center">{{ !empty($rating->avg_q_four) ? $rating->avg_q_four : '' }}</td>
													</tr>
												@endforeach
											@else
												<tr>
													<td colspan="10"><p class="dropdown-item">No records to display</p></td>
												</tr>
											@endif
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive -->
                        </div> <!-- end col -->
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
