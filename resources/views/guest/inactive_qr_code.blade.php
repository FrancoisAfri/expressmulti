@extends('layouts.main-guest')

@section('page_dependencies')

    <link href="{{ asset('libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('libs/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/summernote/summernote-bs4.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css">
	<link href="https://unpkg.com/cloudinary-video-player@1.9.0/dist/cld-video-player.min.css" rel="stylesheet">
    <link href="{{ asset('libs/kartik-v-bootstrap-star-rating-3642656/css/star-rating.css') }}" media="all"  rel="stylesheet" type="text/css">
    <link href="{{ asset('libs/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.css') }}" media="all"  rel="stylesheet" type="text/css">
	<!-- Bootstrap Css -->
      <link rel="stylesheet" href="{{ asset('libs/eatsome/vender/bootstrap/css/bootstrap.min.css') }}">
      <!-- Icofont -->
      <link rel="stylesheet" href="{{ asset('libs/eatsome/vender/icons/icofont.min.css') }}">
      <!-- Slick SLider Css -->
      <link rel="stylesheet" href="{{ asset('libs/eatsome/vender/slick/slick/slick.css') }}">
      <link rel="stylesheet" href="{{ asset('libs/eatsome/vender/slick/slick/slick-theme.css') }}">
      <!-- Font Awesome Icon -->
      <link href="{{ asset('libs/eatsome/vender/fontawesome/css/all.min.css') }}" rel="stylesheet">
      <!-- Sidebar CSS -->
      <link href="{{ asset('libs/eatsome/vender/sidebar/demo.css') }}" rel="stylesheet">
      <!-- Custom Css -->
      <link rel="stylesheet" href="{{ asset('libs/eatsome/css/style.css') }}">
@endsection
<!-- Begin page -->

@section('content')

    @section('content_data')
        <div class="content-page">
			<div class="content">
				<!-- Start Content-->
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-lg-6 col-xl-4 mb-4">
							<div class="error-text-box">
								<svg viewBox="0 0 600 200">
									<!-- Symbol-->
									<symbol id="s-text">
										<text text-anchor="middle" x="50%" y="50%" dy=".35em">404!</text>
									</symbol>
									<!-- Duplicate symbols-->
									<use class="text" xlink:href="#s-text"></use>
									<use class="text" xlink:href="#s-text"></use>
									<use class="text" xlink:href="#s-text"></use>
									<use class="text" xlink:href="#s-text"></use>
									<use class="text" xlink:href="#s-text"></use>
								</svg>
							</div>
							<div class="text-center">
								<h3 class="mt-0 mb-2">Whoops! Qr code not active </h3>
								<p class="text-muted mb-3">The code you are scanning is not active. Please ask your waiter for assistance.</p>
							</div>
							<!-- end row -->
						</div> <!-- end col -->
					</div>
					<!-- end row -->
				</div> <!-- container -->
			</div> <!-- content -->
		</div>
        <br><br>
    @endsection
@stop

@section('page_script')

    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- Plugins js -->
    <script src="{{ asset('libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('libs/autonumeric/autoNumeric-min.js') }}"></script>
    <script src="{{ asset('js/pages/form-masks.init.js') }}"></script>
    <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('libs/tippy.js/tippy.all.min.js') }}"></script>
    <script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('js/pages/form-fileuploads.init.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('libs/intl-tel-input/build/js/intlTelInput.js') }}"></script>
    <script src="{{ asset('libs/iCheck/icheck.min.js') }}"></script>
	<script src="https://unpkg.com/cloudinary-video-player@1.9.0/dist/cld-video-player.min.js"
            type="text/javascript"></script>
    <script src="{{ asset('libs/kartik-v-bootstrap-star-rating-3642656/js/star-rating.js') }}" type="text/javascript"></script>
    <script src="{{ asset('libs/kartik-v-bootstrap-star-rating-3642656/themes/krajee-svg/theme.js') }}"></script>
	<script src="{{ asset('libs/eatsome/vender/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('libs/eatsome/vender/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<!-- slick Slider JS-->
	<script src="{{ asset('libs/eatsome/vender/slick/slick/slick.min.js') }}"></script>
	<!-- Sidebar JS-->
	<script src="{{ asset('libs/eatsome/vender/sidebar/hc-offcanvas-nav.js') }}"></script>
	<!-- Javascript -->
	<script src="{{ asset('libs/eatsome/js/custom.js') }}"></script>
	<script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
    <script>
	
		
    </script>
@endsection