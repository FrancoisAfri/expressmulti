@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link  href="{{ asset('libs/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css">

@endsection

{{-- Page content --}}
@section('content')

    @section('content_data')

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <form class="form-horizontal" method="POST" action="{{ route('users-access.store') }}">
                                {{ csrf_field() }}

                                <input type="hidden" name="module_id" value="{{ $module->id ?? ''  }}">
                            <h2>Users Access (Module: {{ $module->name ?? '' }})</h2>

                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                                   id="tickets-table">
                                <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th style="vertical-align: middle; text-align: center;">None</th>
                                    <th style="vertical-align: middle; text-align: center;">Read</th>
                                    <th style="vertical-align: middle; text-align: center;">Write</th>
                                    <th style="vertical-align: middle; text-align: center;">Modify </th>
                                    <th style="vertical-align: middle; text-align: center;">Admin </th>
									@if ($rights == 1)
										<th style="vertical-align: middle; text-align: center;">SuperUser </th>
									@endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $key => $employee)
                                    <tr>
                                        <td>
                                            {{ $employee->first_name .' ' . $employee->surname ?? ''}}
                                        </td>

                                        <td style="vertical-align: middle; text-align: center;">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_none' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="0" {{ $employee->access_level == 0 ? ' checked' : '' }}></label>
                                        </td>
                                        <td style="vertical-align: middle; text-align: center;">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_read' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="1" {{ $employee->access_level == 1 ? ' checked' : '' }}></label>
                                        </td>
                                        <td style="vertical-align: middle; text-align: center;">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_write' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="2" {{ $employee->access_level == 2 ? ' checked' : '' }}></label>
                                        </td>
                                        <td style="vertical-align: middle; text-align: center;">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_modify' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="3" {{ $employee->access_level == 3 ? ' checked' : '' }}></label>
                                        </td>
                                        <td style="vertical-align: middle; text-align: center;">
                                            <label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_admin' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="4" {{ $employee->access_level == 4 ? ' checked' : '' }}></label>
                                        </td>
										@if ($rights == 1)
											<td style="vertical-align: middle; text-align: center;">
												<label class="radio-inline" style="padding-left: 0px;"><input type="radio" id="{{ $employee->id . '_rdo_superuser' }}" name="{{ "access_level[" . $employee->uid . "]" }}" value="5" {{ $employee->access_level == 5 ? ' checked' : '' }}></label>
											</td>
										@endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                                <br>
                                <div class="text-right">
                                    <button type="button" id="back_button" class="btn btn-dark waves-effect waves-light text-left"
                                            data-dismiss="modal"><i class="mdi mdi-skip-backward-outline"></i>Back
                                    </button>
                                    <button type="submit"  id="add-ribbon" class="btn btn-success waves-effect waves-light">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div><!-- end col -->
                </div>
                <!-- end row -->
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
    <script src="{{ asset('libs/iCheck/icheck.min.js') }}"></script>

    <script>

        document.getElementById("back_button").onclick = function () {
            location.href = "{{ route('users-access.index') }}";
        };

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

    </script>
@endsection




