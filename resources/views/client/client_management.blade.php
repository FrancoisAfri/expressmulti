@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('libs/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('libs/intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css">
@endsection

{{-- Page content --}}
@section('content')
    @section('content_data')
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <a class="btn btn-sm btn-blue waves-effect waves-light float-right"
                       href="{{ URL::route('client_details.index') }}">
                        <i class="mdi mdi-plus-circle"></i>
                        Add Client
                    </a>
                    <h4 class="header-title mb-4">Client Management</h4>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                           id="tickets-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th> Name</th>
                            <th> Email</th>
                            <th> Phone</th>
                            <th> Address</th>
                            <th> Package</th>
                            <th> Status</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($clients as $key => $client)
                            <tr>
                                <td>
                                    <b>
                                        <a href="{{route('client_details.show', $client->uuid)}}"
                                           class="btn btn-soft-primary btn-rounded btn-sm waves-effect waves-light">
                                            <i class="mdi mdi-hospital-box-outline mr-2 text-muted font-18 vertical-middle"></i>
                                            View Client Record</a>
                                    </b>
                                </td>
                                <td>
                                    <span
									class="ml-2">{{ (!empty($client->name)) ? $client->name : ''}}
									</span>
                                </td>
                                <td>
									<span>
										 {{ $client->email ?? ''}}
									</span>
                                </td>
                                <td>
									<span>
										 {{ $client->phone_number ?? '' }}
									</span>
                                </td>
								<td>
									<span>
										 {{ $client->res_address ?? '' }}
									</span>
                                </td>
								<td>
									<span>
										 {{ $client->packages->package_name ?? '' }}
									</span>
                                </td>
                                <td>
									<span>
										@if($client->is_active == 1)
											<span class="badge badge-success">Active</span>
										@elseif($client->is_active == 0)
											<span class="badge bg-soft-danger text-danger">No-Active</span>
										@endif
									</span>
                                </td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <a href="#"
                                           class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                           data-toggle="dropdown" aria-expanded="false"><i
                                                class="mdi mdi-arrange-bring-to-front"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button onclick="postData({{$client->id}}, 'actdeac');"
                                                    class="dropdown-item" data-toggle="tooltip"
                                                    title='change Active status'><i
                                                    class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                {{(!empty($client->is_active) && $client->is_active == 1) ? "De-Activate" : "Activate"}}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!-- end col -->
        </div>
        @include('client.partials.add_contact_person')
    @endsection
@stop
@section('page_script')
    <!-- third party js -->
    <script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
    <!-- third party js ends -->
    <script src="{{ asset('libs/tippy.js/tippy.all.min.js') }}"></script>
    <script src="{{ asset('libs/parsleyjs/parsley.min.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('libs/iCheck/icheck.min.js') }}"></script>
    <!-- Tickets js -->
    <script src="{{ asset('js/pages/tickets.js') }}"></script>
    <script>
        function deleteRecord(id) {
            location.href = "{{route('client_details.destroy', '')}}" + "/" + id;
        }

        $(document).ready(function () {
            $('input[type="radio"]').click(function () {
                const inputValue = $(this).attr("value");
                if (inputValue == 'd_id_number') {
                    id_number.show();
                    passport.hide();
                } else if (inputValue == 'd_passport') {
                    passport.show();
                    id_number.hide();
                }
            });
        });

        function postData(id, data) {

            if (data == 'actdeac')
                location.href = "{{route('clientManagement.activate', '')}}" + "/" + id;
        }

        $(function () {

			$('#add-contact-person').on('click', function () {
                let strUrl = '{{ route('contact_person.store') }}';
                let modalID = 'add-new-contact-person-modal';
                let formName = 'add-contact-person-form';
                let submitBtnID = 'add-contact-person';
                let redirectUrl = '{{route('clientManagement.index')}}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been saved successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
        });
    </script>
@endsection




