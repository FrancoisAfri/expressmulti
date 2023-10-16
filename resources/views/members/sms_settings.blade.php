@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/mohithg-switchery/switchery.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>

@endsection

@section('content')

    @section('content_data')

                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Sms Settings </h4>
                                <form  method="post" action="{{ route('sms_settings.store') }}">

                                {{ csrf_field() }}

{{--                                    @if(!empty($SmSConfiguration->id))--}}
{{--                                        {{ method_field('PATCH') }}--}}
{{--                                    @endif--}}

                                    <input type="hidden" name="user_id" value="{{ !empty($SmSConfiguration->id) ? $SmSConfiguration->id : 0}}">

                                    <div class="form-row">
                                        <label for="inputState" class="col-form-label">SMS Provider</label>
                                        <select id="inputState" class="form-control" name="sms_provider" id="sms_provider" required>
                                            <option value="">*** Select a Service Provider ***</option>
                                            <option value="1" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 1) ? ' selected="selected"' : '' }}>BulkSMS</option>
                                            <option value="2" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 2) ? ' selected="selected"' : '' }}>vodacomSMS</option>
                                            <option value="3" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 3) ? ' selected="selected"' : '' }}>LogicSMS</option>
                                            <option value="4" {{ (!empty($SmSConfiguration->sms_provider) && $SmSConfiguration->sms_provider == 4) ? ' selected="selected"' : '' }}>HugeTelecoms</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAddress" class="col-form-label">SMS Username</label>
                                        <input type="text" class="form-control" id="sms_username" name="sms_username"
                                               value="{{!empty($SmSConfiguration->sms_username) ? $SmSConfiguration->sms_username: ' ' }}"
                                                 placeholder="Enter Username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAddress2" class="col-form-label">SMS Password </label>
                                        <input type="password" class="form-control" id="sms_password" name="sms_password"
                                               value="{{!empty($SmSConfiguration->sms_password) ? $SmSConfiguration->sms_password: '' }}"
                                                 placeholder="Enter Password" required>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save Settings
                                    </button>
                                </form>
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                    <div class="col-6">
                        <div class="card-box">
                            <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
                                    data-toggle="modal" data-target="#add-sms-patient-modal">
                                <i class="mdi mdi-plus-circle"></i> Add Sms
                            </button>
                            <h4 class="header-title mb-4">Patient Sms</h4>
                            <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" id="tickets-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Number of sms </th>
                                    <th>Status</th>
                                    <th class="hidden-sm">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($SmsTracker as $key => $module)
                                    <tr>
                                        <td>
                                            <span>
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                 {{ $module->sms_count ?? 0}}
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                @if($module->is_active == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @elseif($module->is_active == 0)
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
                                                            data-target="#edit-sms-patient-modal"
                                                            data-id="{{ $module->id }}"
                                                            data-sms_count="{{ $module->sms_count }}">
                                                        <i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>
                                                        Edit
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
                        </div>
                    </div><!-- end col -->
                </div>
                @include('patients.partials.add_patient_sms')
                @include('patients.partials.edit_patient_sms')
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
    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('libs/mohithg-switchery/switchery.min.js')}}"></script>
    <script src="{{ asset('libs/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/pages/form-summernote.init.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js')}}"></script>
    <script src="{{ asset('libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{ asset('js/pages/form-advanced.init.js')}}"></script>

    <script src="{{ asset('js/custom_components/js/modal_ajax_submit.js') }}"></script>

    <script src="{{ asset('js/pages/tickets.js') }}"></script>
    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>

    <script>

        $(function () {

            $('#add-patient-sms').on('click', function () {
                let strUrl = '{{ route('add.PatientSms') }}';
                let modalID = 'add-sms-patient-modal';
                let formName = 'add-patient_sms-form';
                let submitBtnID = 'add-patient-sms';
                let redirectUrl = '{{ route('sms_settings.index') }}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            let moduleId;
            $('#edit-sms-patient-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                moduleId = btnEdit.data('id');
                let sms_count = btnEdit.data('sms_count');
                let modal = $(this);
                modal.find('#sms_count').val(sms_count);
                modal.find('#formId').val(moduleId);
            });



            $('#edit-sms-patient').on('click', function () {
                let strUrl = '{{ route('edit.PatientSms') }}';
                let modalID = 'edit-sms-patient-modal';
                let formName = 'edit-sms-patient-form';
                let submitBtnID = 'edit-sms-patient';
                let redirectUrl = '{{ route('sms_settings.index') }}';
                let successMsgTitle = 'Record Updated!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

        });

    </script>
@endsection

