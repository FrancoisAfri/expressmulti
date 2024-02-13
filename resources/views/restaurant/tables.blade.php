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
	<link href="https://unpkg.com/cloudinary-video-player@1.9.0/dist/cld-video-player.min.css" rel="stylesheet">
	 <link href="{{ asset('libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
@endsection

{{-- Page content --}}
@section('content')
    @section('content_data')
        <div class="row">
            <div class="col-12">
                <div class="card-box">
					<button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
							data-toggle="modal" data-target="#add-new-table-modal">
						<i class="mdi mdi-sort-numeric-ascending mr-2 text-muted font-18 vertical-middle"></i>
						Add Table
					</button>
                    <h4 class="header-title mb-4">Tables</h4>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                           id="tickets-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Number Customers</th>
                            <th>Qr Code</th>
                            <th>Status</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tables as $key => $table)
                            <tr>
                                <td>
									<span>
										 {{ $table->name ?? ''}}
									</span>
                                </td>
								<td>
									<span>
										 {{ $table->number_customer ?? ''}}
									</span>
                                </td>
								<td>
									<div class="card-body">
										{!! QrCode::size(200)->generate("{{$current_url/$table->id}}") !!}

									</div>
                                </td>
                                <td>
									<span>
										@if($table->status == 1)
											<span class="badge badge-success">Active</span>
										@elseif($table->status == 0)
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
											<button type="button" id="edit_table" class="dropdown-item"
													data-toggle="modal" title="Edit Table" data-target="#edit-table-modal"
													data-id="{{ $table->id }}"
													data-name="{{ $table->name }}"
													data-number_customer="{{ $table->number_customer }}">
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i> Edit
											</button>
											<button onclick="postData({{$table->id}}, 'actdeac');"
                                                    class="dropdown-item" data-toggle="tooltip"
                                                    title='change Active status'>
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                {{(!empty($table->status) && $table->status == 1) ? "De-Activate" : "Activate"}}
                                            </button>
											<button onclick="postData({{$table->id}}, 'printCode');"
                                                    class="dropdown-item" data-toggle="tooltip"
                                                    title='Print Qr Code'>
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                Print Qr Code
                                            </button>
                                            <form name="command"
                                                  onclick="if(confirm('Are you sure you want to delete this table ?'))"

                                                  action="{{ route('table.destroy', $table->id) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
        @include('restaurant.partials.add_table')
        @include('restaurant.partials.edit_table')
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
	 <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
	 <script src="{{ asset('js/pages/form-fileuploads.init.js') }}"></script>
    <script>
        function deleteRecord(id) {
            location.href = "{{route('client_details.destroy', '')}}" + "/" + id;
        }

        function postData(id, data) {

            if (data == 'actdeac')
                location.href = "{{route('table.activate', '')}}" + "/" + id;
			elseif (data == 'printCode')
				location.href = "{{route('print.qr_code', '')}}" + "/" + id;
        }

        $(function () {

            $('#add-table').on('click', function () {
                let strUrl = '{{ route('table.store') }}';
                let modalID = 'add-new-table-modal';
                let formName = 'add-table-form';
                let submitBtnID = 'add-table';
                let redirectUrl = '{{ route('tables.view') }}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been saved successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			<!-- edit component file -->
            let tableID;
            $('#edit-table-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                tableID = btnEdit.data('id');
                let name = btnEdit.data('name');
                let number_customer = btnEdit.data('number_customer');
                let modal = $(this);
                modal.find('#name').val(name);
                modal.find('#number_customer').val(number_customer);
            });

            // update modal			
            $('#edit-table').on('click', function () {
				let com = 'table';
                let strUrl = '/restaurant/update/table/' + tableID;
                let modalID = 'edit-table-modal';
                let objData = {
                    name: $('#' + modalID).find('#name').val(),
                    number_customer: $('#' + modalID).find('#number_customer').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };

                let submitBtnID = 'edit-table';
                let redirectUrl = '{{route('tables.view')}}';
                let successMsgTitle = 'Changes Saved!';
                let successMsg = 'The Record has been updated successfully.';
                let Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
        });
    </script>
@endsection