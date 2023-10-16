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
                                    <button type="button"
                                            class="btn btn-sm btn-blue waves-effect waves-light float-right"
                                            data-toggle="modal" data-target="#add-new-holiday-modal">
                                        <i class="mdi mdi-plus-circle"></i> Add new Public Holiday
                                    </button>

                                    <h4 class="header-title mb-4">Modules</h4>

                                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                                           id="tickets-table">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Holiday Name</th>
                                            <th>Date</th>
                                            <th>year</th>
                                            <th class="hidden-sm">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($holidays as $key => $holiday)
                                            <tr>
                                                <td>

                                                </td>


                                                <td>
                                                    {{ $holiday->name ?? ''}}
                                                </td>


                                                <td>
                                            <span>
                                            {{ !empty($holiday->day) ? date('d M Y', $holiday->day) : '' }}
                                            </span>
                                                </td>

                                                <td>
                                            <span>
                                                 {{ $holiday->year ?? ''}}
                                            </span>
                                                </td>


                                                <td>
                                                    <div class="btn-group dropdown">
                                                        <a href="#"
                                                           class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                                           data-toggle="dropdown" aria-expanded="false"><i
                                                                class="mdi mdi-arrange-bring-to-front"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <button class="dropdown-item"
                                                                    data-toggle="modal"
                                                                    data-target="#edit-holiday-modal"
                                                                    data-id="{{ $holiday->id }}"
                                                                    data-name="{{ $holiday->name }}"
                                                                    data-day="{{ $holiday->day }}"
                                                                    data-country_id="{{ $holiday->country_id }}">
                                                                <i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>
                                                                Edit Holiday
                                                            </button>


                                                            <button onclick="postData({{$holiday->id}}, 'actdeac');"
                                                                    class="dropdown-item"><i
                                                                    class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                                {{(!empty($holiday->activate) && $holiday->activate == 1) ? "De-Activate" : "Activate"}}
                                                            </button>


                                                            <form
                                                                action="{{ route('public-holidays.destroy', $holiday->id) }}"
                                                                method="POST"
                                                                style="display: inline-block;">
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token"
                                                                       value="{{ csrf_token() }}">

                                                                <button type="submit"
                                                                        class="dropdown-item delete_confirm"
                                                                        data-toggle="tooltip" title='Delete'>
                                                                    <i class="mdi mdi-delete-empty mr-2 text-muted font-18 vertical-middle delete_confirm"
                                                                       data-toggle="tooltip" title='Delete'></i>Delete
                                                                </button>
                                                            </form>


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
                        <!-- end row -->
                        @include('security.holidays.partials.add_new_holiday')
                        @include('security.holidays.partials.edit_holiday')

                    @endsection

                @stop
                @section('page_script')
                    <!-- third party js -->

                    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
                    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
                    <script
                        src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
                    <script
                        src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
                    <script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>
                    <!-- third party js ends -->
                    <!-- Tickets js -->

                    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
                    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
                    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
                    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

                    <script src="{{ asset('js/pages/tickets.js') }}"></script>
                    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>
                    <script src="{{ asset('js/custom_components/js/deleteAlert.js') }}"></script>

                    <script src="{{ asset('js/custom_components/js/deleteAlert.js') }}"></script>
                    <script src="{{ asset('js/custom_components/js/deleteModal.js') }}"></script>

                    <script src="{{ asset('js/custom_components/js/sweetalert.min.js') }}"></script>
                    <script>


                        $(function () {


                            $('#add-holiday').on('click', function () {
                                let strUrl = '{{ route('public-holidays.store') }}';
                                let modalID = 'add-new-holiday-modal';
                                let formName = 'add-holiday-form';
                                let submitBtnID = 'add-holiday';
                                let redirectUrl = '{{ route('public-holidays.index') }}';
                                let successMsgTitle = 'Record Added!';
                                let successMsg = 'Record has been updated successfully.';
                                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                            });

                            let holidayId;
                            $('#edit-holiday-modal').on('show.bs.modal', function (e) {
                                let btnEdit = $(e.relatedTarget);
                                holidayId = btnEdit.data('id');
                                let name = btnEdit.data('name');
                                let day = btnEdit.data('day');
                                let country_id = btnEdit.data('country_id');
                                let modal = $(this);
                                modal.find('#name').val(name);
                                modal.find('#day').val(day);
                                modal.find('#country_id').val(country_id);
                            });


                            $('#edit-holiday').on('click', function () {
                                let strUrl = '/users/holiday/' + holidayId;

                                let modalID = 'edit-holiday-modal';
                                let formName = 'edit-holiday-form';
                                let submitBtnID = 'edit-holiday';
                                let redirectUrl = '{{ route('public-holidays.index') }}';
                                let successMsgTitle = 'Record Updated!';
                                let successMsg = 'Record has been updated successfully.';
                                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
                            });

                        });
                    </script>
@endsection
