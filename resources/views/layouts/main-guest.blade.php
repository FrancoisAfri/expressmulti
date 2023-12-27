@include('layouts.partials.head')

@yield('page_dependencies')

<body data-layout-mode="horizontal"
      data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <div class="navbar-custom">
        <div class="container-fluid">
            <ul class="list-unstyled topnav-menu float-right mb-0">

                <li class="dropdown d-none d-lg-inline-block">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                        <i class="fe-maximize noti-icon"></i>
                    </a>
                </li>


                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="pro-user-name ml-1">
                                    Welcome to {{$localName}} <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>

                </li>



            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="index.html" class="logo logo-dark text-center">
                            <span class="logo-sm">
                                <img src="../assets/images/logo-sm.png" alt="" height="22">
                                <!-- <span class="logo-lg-text-light">UBold</span> -->
                            </span>
                    <span class="logo-lg">
                                <img src="../assets/images/logo-dark.png" alt="" height="20">
                        <!-- <span class="logo-lg-text-light">U</span> -->
                            </span>
                </a>

                <a href="index.html" class="logo logo-light text-center">
                            <span class="logo-sm">
                                <img src="../assets/images/logo-sm.png" alt="" height="22">
                            </span>
                    <span class="logo-lg">
                                <img src="../assets/images/logo-light.png" alt="" height="20">
                            </span>
                </a>
            </div>

            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile waves-effect waves-light">
                        <i class="fe-menu"></i>
                    </button>
                </li>

                <li>
                    <!-- Mobile menu toggle (Horizontal Layout)-->
                    <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </li>


            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end Topbar -->

    <div class="topnav shadow-lg">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                <div class="collapse navbar-collapse" id="topnav-menu-content">
                    <ul class="navbar-nav">
					
					@if(count($services) > 0 && !empty($tableID))
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
							   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fe-grid mr-1"></i>  Quick Service Buttons<div class="arrow-down"></div>
							</a>
							<div class="dropdown-menu" aria-labelledby="topnav-components">
								@foreach($services as $service)
									@if ($service->status === 1)
										<a href="/restaurant/service-request/{{$tableID}}/{{$service->id}}" class="dropdown-item"><i class="fe-calendar mr-1"></i> {{ $service->name }}</a>
									@endif
								@endforeach
							</div>
						</li>
					@endif
                    </ul> <!-- end navbar-->
                </div> <!-- end .collapsed-->
            </nav>
        </div> <!-- end container-fluid -->
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
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">

                                    @if (isset($breadcrumb) && count($breadcrumb) > 0)
                                        <ol class="breadcrumb">
                                            @foreach($breadcrumb as $bc_item)

                                                <li class="breadcrumb-item">
                                                    <a href="{{ $bc_item['path'] ?? '' }}">{{ $bc_item['title'] }}</a>
                                                </li>

                                            @endforeach
                                        </ol>
                                    @endif
                                </ol>
                            </div>
                            <h4 class="page-title">
                                {{ $page_title ?? '' }}
                                <small>{{ $page_description ?? '' }}</small>
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





