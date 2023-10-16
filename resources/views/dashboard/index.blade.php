@extends('layouts.main-layout')
@section('page_dependencies')
    <link href="{{ asset('libs/@fullcalendar/core/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/@fullcalendar/daygrid/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/@fullcalendar/bootstrap/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/@fullcalendar/timegrid/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/@fullcalendar/list/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection
{{-- Page content --}}
@section('content')
    @section('content_data')
        <div class="container-fluid">
            <div class="row">
                

                
                <!-- end col -->
            </div>

            <!-- end row -->

            
			
        </div>
    @endsection
@endsection
@section('page_script')

    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('js/custom_components/js/deleteAlert.js') }}"></script>
    <script src="{{ asset('js/custom_components/js/deleteModal.js') }}"></script>

    <script src="{{ asset('libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('libs/autonumeric/autoNumeric-min.js') }}"></script>
    <script src="{{ asset('js/pages/form-masks.init.js') }}"></script>

    <script src="{{ asset('libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>

    <!-- third party js ends -->
    <!-- Tickets js -->
    <script src="{{ asset('js/pages/tickets.js') }}"></script>

    <script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>

    <script src="{{ asset('libs/intl-tel-input/build/js/intlTelInput.js') }}"></script>

    <script src="{{ asset('js/custom_components/js/sweetalert.min.js') }}"></script>
    <!-- Plugins js-->
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js')}}"></script>
    <script src="{{ asset('libs/chart.js/Chart.bundle.min.js') }} "></script>
    <script src="{{ asset('libs/moment/min/moment.min.js') }} "></script>
    <script src="{{ asset('libs/jquery.scrollto/jquery.scrollTo.min.js') }} "></script>
    <!-- Plugins js-->
    <script src="{{ asset('libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('libs/moment/min/moment.min.js')}}"></script>
    <script src="{{ asset('libs/@fullcalendar/core/main.min.js')}}"></script>
    <script src="{{ asset('libs/@fullcalendar/bootstrap/main.min.js')}}"></script>
    <script src="{{ asset('libs/@fullcalendar/daygrid/main.min.js')}}"></script>
    <script src="{{ asset('libs/@fullcalendar/timegrid/main.min.js')}}"></script>
    <script src="{{ asset('libs/@fullcalendar/list/main.min.js')}}"></script>
    <script src="{{ asset('libs/@fullcalendar/interaction/main.min.js')}}"></script>
    <!-- Calendar init -->
    <script src="{{ asset('js/calendar.js')}}"></script>

    <script src="{{ asset('js/pages/datatables.init.js') }}"></script>
    <!-- Dashboar 1 init js-->
    <script src="{{ asset('js/pages/dashboard-2.init.js')}}"></script>
    <script src="{{ asset('js/pages/dashboard-3.init.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- App js-->
    <script src="{{ asset('js/app.min.js')}}"></script>
    <script>



           
    </script>
@stop
