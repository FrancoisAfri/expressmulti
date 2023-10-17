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
					<button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
							data-toggle="modal" data-target="#add-new-package-modal">
						<i class="mdi mdi-sort-numeric-ascending mr-2 text-muted font-18 vertical-middle"></i>
						Add Package
					</button>
                    <h4 class="header-title mb-4">Packages</h4>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                           id="tickets-table">
                        <thead>
                        <tr>
                            <th> Name</th>
                            <th> No of Tables</th>
                            <th> Status</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($packages as $key => $package)
                            <tr>
                                <td>
									<span>
										 {{ $package->package_name ?? ''}}
									</span>
                                </td>
                                <td>
									<span>
										 {{ $package->no_table ?? '' }}
									</span>
                                </td>
                                <td>
									<span>
										@if($package->status == 1)
											<span class="badge badge-success">Active</span>
										@elseif($package->status == 0)
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
											<button type="button" id="edit_package" class="dropdown-item"
													data-toggle="modal" title="Edit Package" data-target="#edit-package-modal"
													data-id="{{ $package->id }}"
													data-package_name="{{ $package->package_name }}"
													data-no_table="{{$package->no_table}}">
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i> Edit
											</button>
											<button onclick="postData({{$package->id}}, 'actdeac');"
                                                    class="dropdown-item" data-toggle="tooltip"
                                                    title='change Active status'>
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                {{(!empty($package->status) && $package->status == 1) ? "De-Activate" : "Activate"}}
                                            </button>
                                            <form name="command"
                                                  onclick="if(confirm('Are you sure you want to delete this package ?'))"

                                                  action="{{ route('package.destroy', $package->id) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token"
                                                       value="{{ csrf_token() }}">
                                                <button type="submit"
                                                        class="dropdown-item delete_confirm namespac"
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
        @include('client.partials.add_package')
        @include('client.partials.edit_package')
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

        function postData(id, data) {

            if (data == 'actdeac')
                location.href = "{{route('package.activate', '')}}" + "/" + id;
        }

        $(function () {

            $('#add-package').on('click', function () {
                let strUrl = '{{ route('package.store') }}';
                let modalID = 'add-new-package-modal';
                let formName = 'add-package-form';
                let submitBtnID = 'add-package';
                let redirectUrl = '{{ route('packages.view') }}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been updated successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			<!-- edit component file -->
            let packageId;
            $('#edit-package-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                packageId = btnEdit.data('id');
                let package_name = btnEdit.data('package_name');
                let no_table = btnEdit.data('no_table');
                let modal = $(this);
                modal.find('#package_name').val(package_name);
                modal.find('#no_table').val(no_table);
            });

            // update modal			
            $('#edit-package').on('click', function () {
				let com = 'package';
                let strUrl = '/clients/update/package/' + packageId;
                let modalID = 'edit-package-modal';
                let objData = {
                    package_name: $('#' + modalID).find('#package_name').val(),
                    no_table: $('#' + modalID).find('#no_table').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };

                let submitBtnID = 'edit-package';
                let redirectUrl = '{{route('packages.view')}}';
                let successMsgTitle = 'Changes Saved!';
                let successMsg = 'The Record has been updated successfully.';
                let Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
        });
    </script>
@endsection




