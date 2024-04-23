@include('layouts.partials.head')

@yield('page_dependencies')

<body data-layout-mode="horizontal"
      data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
<!-- Begin page -->
<div id="wrapper">
    <!-- Topbar Start -->
    <div class="navbar-custom">
        <div class="container-fluid">
			</div>
            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile waves-effect waves-light">
                        <i class="fe-menu"></i>
                    </button>
					<img src="{{ $logo }}" style="width:90px;height:70px;">
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end topnav-->
    @include('sweetalert::alert')
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
    @yield('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">
                                {{ $page_title ?? '' }}
                                <small>{{ $page_description ?? '' }}</small>
								@if (!empty($scanned->nickname))
									<a href="/restaurant/close-table/request/{{$tableID}}" style="text-align: right; float: right;">Close Table</a>
								@endif 
                            </h4>
                        </div>
                    </div>
                </div>
                @yield('content_data')
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
    @include('layouts.partials.footer')

</div>
<!-- END wrapper -->

@include('layouts.partials.scripts')

@stack('scripts')

@yield('page_script')
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->

</body>





