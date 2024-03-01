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
@endsection

{{-- Page content --}}
@section('content')
    @section('content_data')
        <div class="row">
            <div class="col-12">
                <div class="card-box">
					<button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right"
							data-toggle="modal" data-target="#add-new-menu-modal">
						<i class="mdi mdi-sort-numeric-ascending mr-2 text-muted font-18 vertical-middle"></i>
						Add Menu Item
					</button>
                    <h4 class="header-title mb-4">Menus</h4>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                           id="tickets-table">
                        <thead>
                        <tr>
							<th>Order</th>
                            <th>Name</th>
                            <th width="200">Description</th>
                            <th width="200">Ingredients</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>kJ</th>
                            <th>Image</th>
                            <th>Video</th>
                            <th>Status</th>
                            <th class="hidden-sm">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($menus as $key => $menu)
                            <tr>
								<td style="text-align: center;">
									<span>
										 {{ $menu->sequence ?? ''}}
									</span>
								</td>
                                <td>
									 {{ $menu->name ?? ''}}
                                </td>
								<td width="200">
									<span width="200">
									 {{ $menu->description ?? ''}}
									 </span>
                                </td>
								<td width="200">
									{{ $menu->ingredients ?? ''}}
                                </td>
								<td>
									<span>
										 {{ $menu->categories->name ?? ''}}
									</span>
                                </td>
								<td>
									<span>
										 {{(!empty($menu->menuType->name)) ? $menu->menuType->name : 'N/A'}}
									</span>
                                </td>
								<td>
									<span>
										 {{ (!empty($menu->price)) ? 'R ' .number_format($menu->price, 2) : ''}}
									</span>
                                </td>
								<td>
									<span>
										 {{ $menu->calories ?? ''}}
									</span>
                                </td>
								<td>
									@if(!empty($menu->image))
										<div class="popup-thumbnail img-responsive">
											<img src="{{ asset('images/menus/'.$menu->image) }}"
												 height="35px" width="40px" alt="Image">
										</div>
									@else
										<!-- Placeholder image or any alternative content -->
										<span>No image Available</span>
									@endif
									
                                </td>
								<td>
									@if(!empty($menu->video))
										<video  height="60" width="150" controls>
											<source src="{{URL::asset("videos/menus/$menu->video")}}" type="video/mp4">
											Your browser does not support the video tag.
										</video>
									@else
										<!-- Placeholder video or any alternative content -->
										<span>No video Available</span>
									@endif
									
                                </td>
                                <td>
									<span>
										@if($menu->status == 1)
											<span class="badge badge-success">Active</span>
										@elseif($menu->status == 0)
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
											<button type="button" id="edit_menu" class="dropdown-item"
													data-toggle="modal" title="Edit Menu" data-target="#edit-menu-modal"
													data-id="{{ $menu->id }}"
													data-name="{{ $menu->name }}"
													data-price="{{ $menu->price }}"
													data-calories="{{ $menu->calories }}"
													data-description="{{ $menu->description }}"
													data-ingredients="{{ $menu->ingredients }}"
													data-category_id="{{ $menu->category_id }}"
													data-sequence="{{ $menu->sequence }}"
													data-menu_type="{{ $menu->menu_type }}">
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i> Edit
											</button>
											<button onclick="postData({{$menu->id}}, 'actdeac');"
                                                    class="dropdown-item" data-toggle="tooltip"
                                                    title='change Active status'>
													<i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>
                                                {{(!empty($menu->status) && $menu->status == 1) ? "De-Activate" : "Activate"}}
                                            </button>
											<button onclick="postData({{$menu->id}}, 'deleterecord');"
                                                    class="dropdown-item" data-toggle="tooltip"
                                                    title='Delete'>
													<i class="mdi mdi-delete-empty mr-2 text-muted font-18 vertical-middle delete_confirm"
                                                       data-toggle="tooltip" title='Delete'></i>Delete
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
        @include('restaurant.partials.add_menu')
        @include('restaurant.partials.edit_menu')
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
	<script src="https://unpkg.com/cloudinary-video-player@1.9.0/dist/cld-video-player.min.js"
            type="text/javascript"></script> 
    <script>
        function deleteRecord(id) {
            location.href = "{{route('client_details.destroy', '')}}" + "/" + id;
        }

        function postData(id, data) {

            if (data == 'actdeac')
                location.href = "{{route('menu.activate', '')}}" + "/" + id;
			else if (data == 'deleterecord')
				location.href = "{{route('menu.destroy', '')}}" + "/" + id;
        }

        $(function () {

            $('#add-menu').on('click', function () {
                let strUrl = '{{ route('menu.store') }}';
                let modalID = 'add-new-menu-modal';
                let formName = 'add-menu-form';
                let submitBtnID = 'add-menu';
                let redirectUrl = '{{ route('menus.view') }}';
                let successMsgTitle = 'Record Added!';
                let successMsg = 'Record has been saved successfully.';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });
			<!-- edit component file -->
            let menuID;
            $('#edit-menu-modal').on('show.bs.modal', function (e) {
                let btnEdit = $(e.relatedTarget);
                menuID = btnEdit.data('id');
                let name = btnEdit.data('name');
                let description = btnEdit.data('description');
                let ingredients = btnEdit.data('ingredients');
                let category_id = btnEdit.data('category_id');
                let menu_type = btnEdit.data('menu_type');
                let calories = btnEdit.data('calories');
                let price = btnEdit.data('price');
                let sequence = btnEdit.data('sequence');
                let modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);
                modal.find('#ingredients').val(ingredients);
                modal.find('#category_id').val(category_id);
                modal.find('#menu_type').val(menu_type);
                modal.find('#calories').val(calories);
                modal.find('#price').val(price);
                modal.find('#sequence').val(sequence);
            });

            // update modal			
            $('#edit-menu').on('click', function () {
				let com = 'menu';
                let strUrl = '/restaurant/update/menu/' + menuID;
                let modalID = 'edit-menu-modal';
                let objData = {
                    name: $('#' + modalID).find('#name').val(),
                    description: $('#' + modalID).find('#description').val(),
                    ingredients: $('#' + modalID).find('#ingredients').val(),
                    category_id: $('#' + modalID).find('#category_id').val(),
                    menu_type: $('#' + modalID).find('#menu_type').val(),
                    price: $('#' + modalID).find('#price').val(),
                    calories: $('#' + modalID).find('#calories').val(),
                    sequence: $('#' + modalID).find('#sequence').val(),
                    _token: $('#' + modalID).find('input[name=_token]').val()
                };

                let submitBtnID = 'edit-menu';
                let redirectUrl = '{{route('menus.view')}}';
                let successMsgTitle = 'Changes Saved!';
                let successMsg = 'The Record has been updated successfully.';
                let Method = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection