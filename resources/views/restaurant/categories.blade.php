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
	<link href="{{ asset('libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

{{-- Page content --}}
@section('content')
    @section('content_data')
        <div class="row">
            <div class="col-12">
                <div class="card-box">
					<button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
							data-toggle="modal" data-target="#add-new-category-modal">
						<i class="mdi mdi-sort-numeric-ascending mr-2 text-muted font-18 vertical-middle"></i>
						Add Category
					</button>
                    <h4 class="header-title mb-4">Categories</h4>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                           id="tickets-table">
                        <thead>
                        <tr>
                            <th> Name</th>
							<th>Image</th>
                            <th> Status</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($categories as $key => $category)
                            <tr>
                                <td>
									<span>
										 {{ $category->name ?? ''}}
									</span>
                                </td>
								<td>
									@if(!empty($category->image))
										<div class="popup-thumbnail img-responsive">
											<img src="{{ asset('Images/categories/'.$category->image) }} "
												 height="35px" width="40px" alt="Image">
										</div>
									@else
										<!-- Placeholder image or any alternative content -->
										<span>No image Available</span>
									@endif
                                </td>
                                <td>
									<span>
										@if($category->status == 1)
											<span class="badge badge-success">Active</span>
										@elseif($category->status == 0)
											<span class="badge bg-soft-danger text-danger">Not active</span>
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
													data-toggle="modal" title="Edit Category" data-target="#edit-category-modal"
													data-id="{{ $category->id }}"
													data-name="{{ $category->name }}">
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i> Edit
											</button>
											<button onclick="postData({{$category->id}}, 'actdeac');"
                                                    class="dropdown-item" data-toggle="tooltip"
                                                    title='change Active status'>
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                {{(!empty($category->status) && $category->status == 1) ? "De-Activate" : "Activate"}}
                                            </button>
                                            <form name="command"
                                                  onclick="if(confirm('Are you sure you want to delete this category ?'))"

                                                  action="{{ route('category.destroy', $category->id) }}"
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
        @include('restaurant.partials.add_category')
        @include('restaurant.partials.edit_category')
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
                location.href = "{{route('category.activate', '')}}" + "/" + id;
        }

        $(function () {

            $('#add-category').on('click', function () {
                let strUrl = '{{ route('category.store') }}';
                let modalID = 'add-new-category-modal';
                let formName = 'add-category-form';
                let submitBtnID = 'add-category';
                let redirectUrl = '{{ route('categories.view') }}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been saved successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			<!-- edit component file -->
            let categoryID;
            $('#edit-category-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                categoryID = btnEdit.data('id');
                let name = btnEdit.data('name');
                let modal = $(this);
                modal.find('#name').val(name);
            });

            // update modal			
            $('#edit-category').on('click', function () {
				let com = 'category';
                let strUrl = '/restaurant/update/category/' + categoryID;
                let modalID = 'edit-category-modal';
                let objData = {
                    name: $('#' + modalID).find('#name').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };

                let submitBtnID = 'edit-category';
                let redirectUrl = '{{route('categories.view')}}';
                let successMsgTitle = 'Changes Saved!';
                let successMsg = 'The Record has been updated successfully.';
                let Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });
        });
    </script>
@endsection




