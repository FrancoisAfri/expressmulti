@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>

    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

{{-- Page content --}}
@section('content')

    @section('content_data')

                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
                                    data-toggle="modal" data-target="#add-new-ribbon-modal">
                                <i class="mdi mdi-plus-circle"></i> Add new Ribbon
                            </button>

                            <h4 class="header-title mb-4">Module Ribbon ({{ $modules['name'] ?? '' }}) </h4>

                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                                   id="tickets-table">
                                <thead>
                                <tr>
                                    <th>Ribbon Name</th>
                                    <th>Description</th>
                                    <th>Path</th>
                                    <th>Access Right</th>
                                    <th>Status</th>
                                    <th class="hidden-sm">Action</th>

                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($moduleRibbon as $key => $module)
                                    <tr>

                                        <td>
                                            {{ $module->ribbon_name ?? ''}}
                                        </td>


                                        <td>
                                            <span>
                                                 {{ $module->description ?? ''}}
                                            </span>
                                        </td>

                                        <td>
                                            <span>
                                                 {{ $module->ribbon_path ?? ''}}
                                            </span>
                                        </td>

                                        <td>
                                            <span>
                                                 {{ !empty($module->access_level) ? $userRights[$module->access_level] : 'None' }}
                                            </span>
                                        </td>


                                        <td>
                                            <span>
                                                @if($module->active == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @elseif($module->active == 0)
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
                                                    <button class="dropdown-item"
                                                            data-toggle="modal"
                                                            data-target="#edit-new-ribbon-modal"
                                                            data-id="{{ $module->id }}"
                                                            data-ribbon_name="{{ $module->ribbon_name }}"
                                                            data-description="{{ $module->description }}"
                                                            data-ribbon_path="{{ $module->ribbon_path }}"
                                                            data-sort_order="{{ $module->sort_order }}"
                                                            data-font_awesome="{{ $module->font_awesome }}"
                                                            data-access_level="{{ $module->access_level }}">
                                                        <i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>
                                                        Edit Module
                                                    </button>


                                                    <button onclick="postData({{$module->id}}, 'actdeac');"
                                                            class="dropdown-item"><i
                                                            class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                        {{(!empty($module->activate) && $module->activate == 1) ? "De-Activate" : "Activate"}}
                                                    </button>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                            <div class="text-left">
                                <button type="button" id="back_button" class="btn btn-dark waves-effect waves-light"
                                        data-dismiss="modal">
                                    <i class="mdi mdi-skip-backward-outline"></i>
                                      Back
                                </button>

                            </div>
                        </div>

                    </div><!-- end col -->
                </div>
                <!-- end row -->

                @include('security.ribbons.partials.add_new_ribbon')
                @include('security.ribbons.partials.edit_new_ribbon')

    @endsection

@endsection

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

        document.getElementById("back_button").onclick = function () {
            location.href = "{{ route('module.index') }}";
        };

        function postData(id, data) {

            if (data == 'ribbons')
                location.href = "/users/ribbons/" + id;
            else if (data == 'edit')
                location.href = "/users/module_edit/" + id;
            else if (data == 'actdeac')
                location.href = "{{route('ribbon.activate', '')}}" + "/" + id;
            else if (data == 'access')
                location.href = "/users/module_access/" + id;

        }

        $(function () {

            $('#add-ribbon').on('click', function () {
                let strUrl = '{{ route('ribbons.store') }}';
                let modalID = 'add-new-ribbon-modal';
                let formName = 'add-ribbon-form';
                let submitBtnID = 'add-ribbon';
                let redirectUrl = '{{ route('ribbons.show' , $modules['uuid'] ) }}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
				console.log('testing more');
            });

            let ribbonId;
            $('#edit-new-ribbon-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                ribbonId = btnEdit.data('id');
                let ribbon_name = btnEdit.data('ribbon_name');
                let description = btnEdit.data('description');
                let ribbon_path = btnEdit.data('ribbon_path');
                let access_level = btnEdit.data('access_level');
                let font_awesome = btnEdit.data('font_awesome');
                let sort_order = btnEdit.data('sort_order');

                let modal = $(this);
                modal.find('#ribbon_name').val(ribbon_name);
                modal.find('#description').val(description);
                modal.find('#ribbon_path').val(ribbon_path);
                modal.find('#access_level').val(access_level);
                modal.find('#font_awesome').val(font_awesome);
                modal.find('#sort_order').val(sort_order);
            });


            $('#edit-ribbon').on('click', function () {
                let strUrl = '/users/ribbons/' + ribbonId;

                let modalID = 'edit-new-ribbon-modal';
                let formName = 'edit-ribbon-form';
                let submitBtnID = 'edit-ribbon';
                let redirectUrl = '{{ route('ribbons.show' , $modules['uuid'] ) }}';
                let successMsgTitle = 'Record Updated!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

        });
    </script>
@endsection




