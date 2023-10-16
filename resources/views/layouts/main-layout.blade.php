@include('layouts.partials.head')

@yield('page_dependencies')

<body data-layout-mode="horizontal"
      data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    @include('layouts.partials.top-bar')
    <!-- end Topbar -->
    @include('layouts.partials.sub-bar')

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




