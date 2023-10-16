@include('layouts.partials.head')

<body data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>

<!-- Begin page -->
<div id="wrapper">

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
                                    <text text-anchor="middle" x="50%" y="50%" dy=".35em">403</text>
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
                            <h3 class="mt-0 mb-2">Unathorised - Link expired  </h3>
                            <p class="text-muted mb-3">The Link you clicked has expired,
                                 Contact the system administrator to get a new link</p>

                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->


            </div> <!-- container -->

        </div> <!-- content -->

        <!-- Footer Start -->
        @include('layouts.partials.footer')
        <!-- end Footer -->

    </div>


</div>
<!-- END wrapper -->


<script src="{{ asset('js/vendor.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('js/app.min.js') }}"></script>

</body>

