@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
@endsection

{{-- Page content --}}
{{-- Page content --}}
@section('content')

    @section('content_data')

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
                            data-toggle="modal" data-target="#add-new-module-modal">
                        <i class="mdi mdi-plus-circle"></i> Add new Module
                    </button>

                    <h4 class="header-title mb-4">Modules</h4>

                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                           id="tickets-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Role Name</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($roles as $key => $role)
                            <tr>

                                <td>
                                    {{ $role->id ?? ''}}
                                </td>

                                <td>
                                            <span>
                                                  {{ $role->name ?? ''}}
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
                                                    data-target="#edit-new-module-modal"
                                                    data-id="{{ $role->id }}"
                                                    data-name="{{ $role->name }}">
                                                <i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>
                                                Edit Module
                                            </button>


                                            <button onclick="postData({{$role->id}}, 'actdeac');"
                                                    class="dropdown-item"><i
                                                    class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                {{(!empty($role->activate) && $role->activate == 1) ? "De-Activate" : "Activate"}}
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
        <!-- end row -->

                        @include('security.modules.partials.add_new_module')
        {{--                @include('security.modules.partials.edit_new_module')--}}

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

    <script>

        function postData(id, data) {

            if (data == 'ribbons')
                location.href = "/users/ribbons/" + id;
            else if (data == 'edit')
                location.href = "/users/module_edit/" + id;
            else if (data == 'actdeac')
                location.href = "{{route('module.activate', '')}}" + "/" + id;
            else if (data == 'access')
                location.href = "/users/module_access/" + id;

        }

        $(function () {

            $('#add-module').on('click', function () {
                let strUrl = '{{ route('module.store') }}';
                let modalID = 'add-new-module-modal';
                let formName = 'add-module-form';
                let submitBtnID = 'add-module';
                let redirectUrl = '{{ route('module.index') }}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            let moduleId;
            $('#edit-new-module-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                moduleId = btnEdit.data('id');
                let name = btnEdit.data('name');
                let codeName = btnEdit.data('code_name');
                let path = btnEdit.data('path');
                let font_awesome = btnEdit.data('font_awesome');
                let modal = $(this);
                modal.find('#name').val(name);
                modal.find('#code_name').val(codeName);
                modal.find('#path').val(path);
                modal.find('#font_awesome').val(font_awesome);
            });


            $('#edit-module').on('click', function () {
                let strUrl = '/users/module/' + moduleId;

                let modalID = 'edit-new-module-modal';
                let formName = 'edit-module-form';
                let submitBtnID = 'edit-module';
                let redirectUrl = '{{ route('module.index') }}';
                let successMsgTitle = 'Record Updated!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

        });
    </script>
@endsection




