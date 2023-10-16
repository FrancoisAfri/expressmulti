@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

{{-- Page content --}}

@section('content')

    @section('content_data')

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">

                            <h4 class="header-title mb-4">Modules</h4>

                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                                   id="tickets-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Module Name</th>
                                    <th>Code Name</th>


                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($modules as $key => $module)
                                    <tr>
                                        <td>
                                            <b>

                                                <a href="{{route('users-access.show', $module->uuid)}}" class="btn btn-soft-primary btn-rounded btn-sm waves-effect waves-light">
                                                    <i class="mdi mdi-package-variant mr-2 text-muted font-18 vertical-middle"></i>
                                                    Go to Access Rights</a>
                                            </b>
                                        </td>


                                        <td>
                                            {{ $module->name ?? ''}}
                                        </td>


                                        <td>
                                            <span>
                                                 {{ $module->code_name ?? ''}}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                @include('security.modules.partials.add_new_module')
                @include('security.modules.partials.edit_new_module')

    @endsection
@stop

@section('page_script')
    <!-- third party js -->

    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
    <!-- third party js ends -->
    <!-- Tickets js -->
    <script src="{{ asset('js/pages/tickets.js') }}"></script>

@endsection




