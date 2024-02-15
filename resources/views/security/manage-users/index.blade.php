@extends('layouts.main-layout')

@section('page_dependencies')
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>

    <link href="{{asset('libs/intl-tel-input/build/css/intlTelInput.css') }}" rel="stylesheet" type="text/css"/>
@endsection

{{-- Page content --}}
@section('content')

    @section('content_data')

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
                            data-toggle="modal" data-target="#add-new-user-modal">
                        <i class="mdi mdi-plus-circle"></i> Create New User
                    </button>

                    <h4 class="header-title mb-4">Modules</h4>

                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                           id="tickets-table">
                        <thead>
                        <tr>

                            <th>Name</th>
                            <th>Email</th>
                            <th>Cell Number</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>
                                    <a href="{{route('user_profile.show', $user->person->uuid)}}" class="text-body">

                                        @if(empty($user->person->profile_pic))
                                            <img src="{{ $defaultAvatar }}"
                                                 alt="contact-img" title="contact-img" class="rounded-circle avatar-xs">
                                        @else(!empty($module->person->profile_pic))
                                            <img src="{{(!empty(asset('uploads/'.$user->person->profile_pic)))
                                                        ? asset('uploads/'.$user->person->profile_pic) : $defaultAvatar }}"
                                                 alt="contact-img" title="contact-img" class="rounded-circle avatar-xs">
                                        @endif

                                        <span

                                            class="ml-2">{{ (!empty($user->person->first_name . ' ' . $user->person->surname)) ?
                                                         $user->person->first_name . ' ' . $user->person->surname : ''}}
                                        </span>
                                    </a>
                                </td>
                                <td>
									<span>
										 {{ $user->person->email  ?? ''}}
									</span>
                                </td>
                                <td>
									<span>
										 {{ $user->person->cell_number ?? '' }}
									</span>
                                </td>
                                <td>
									<span>
										@if($user->person->status == 1)
											<span class="badge badge-success">Active</span>
										@elseif($user->person->status == 0)
											<span class="badge bg-soft-danger text-danger">No-Active</span>
										@endif
									</span>
                                </td>
                                <td>
                                    @if(!empty($user->getRoleNames()))
                                        @foreach($user->getRoleNames() as $role)
                                            <label class="badge badge-success">{{ $role ?? '' }}</label>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group dropdown">
                                        <a href="#"
                                           class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                           data-toggle="dropdown" aria-expanded="false"><i
                                                class="mdi mdi-arrange-bring-to-front"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button onclick="postData({{$user->id}}, 'actdeac');"
                                                    class="dropdown-item"><i
                                                    class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                {{(!empty($user->activate) && $user->activate == 1) ? "De-Activate" : "Activate"}}
                                            </button>
											<button onclick="postData({{$user->id}}, 'edit');"
                                                    class="dropdown-item"><i
                                                    class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                               Edit Profile
                                            </button>
                                            <form action="{{ route('manage.destroy', $user->id ) }}" method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <button type="submit"
                                                        class="dropdown-item delete_confirm"
                                                        data-toggle="tooltip" title='Delete'>
                                                    <i class="mdi mdi-account-remove mr-2 text-muted font-18 vertical-middle"></i>
                                                    Delete
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
        @include('security.manage-users.partials.add_new_user')

        {{--        @include('security.modules.partials.edit_new_module')--}}

    @endsection

@stop

@section('page_script')
    <!-- third party js -->

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

    <script>


        function postData(id, data) {

            if (data == 'ribbons')
                location.href = "/users/ribbons/" + id;
            else if (data == 'edit')
                location.href = "/users/profile_edit/" + id;
            else if (data == 'actdeac')
                location.href = "{{route('manageUsers.activate', '')}}" + "/" + id;
            else if (data == 'access')
                location.href = "/users/module_access/" + id;
                

        }

        document.querySelectorAll('#phone ,#cell_number').forEach(item => {
            window.intlTelInput(item, {
                initialCountry: "auto",
                geoIpLookup: function (success, failure) {
                    $.get("https://ipinfo.io", function () {
                    }, "jsonp").always
                    (
                        function (resp) {
                            let countryCode = (resp && resp.country) ? resp.country : "us";
                            success(countryCode);
                        }
                    );
                },
                autoHideDialCode: true,
                nationalMode: false,
                autoPlaceholder: 'aggressive',
                hiddenInput: "fullContactNo",
                utilsScript: "/js/utils.js",
            });
        })

        $(function () {

            $('#add-user').on('click', function () {
                let strUrl = '{{ route('manage.store') }}';
                let modalID = 'add-new-user-modal';
                let formName = 'add-user-form';
                let submitBtnID = 'add-user';
                let redirectUrl = '{{ route('manage.index') }}';
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




