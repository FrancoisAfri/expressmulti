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
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<h4 class="header-title">Settings </h4>
						<form  method="post" action="{{ route('restaurant_settings.store') }}">
						{{ csrf_field() }}

							<input type="hidden" name="setup_id" value="{{ !empty($setup->id) ? $setup->id : 0}}">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="colour_one" class="col-form-label">Normal</label>
										<input type="color" class="form-control" id="colour_one" name="colour_one"
											   value="{{!empty($setup->colour_one) ? $setup->colour_one: '' }}"
												 placeholder="Enter Color">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="mins_one" class="col-form-label">Normal Minutes</label>
												 <input type="mins_one" class="form-control" id="mins_one" name="mins_one"
											   value="{{!empty($setup->mins_one) ? $setup->mins_one: '' }}"
												 placeholder="Enter Minutes">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="colour_two" class="col-form-label">Moderate </label>
										<input type="color" class="form-control" id="colour_two" name="colour_two"
											   value="{{!empty($setup->colour_two) ? $setup->colour_two: '' }}"
												 placeholder="Enter Color">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="colour_two" class="col-form-label">Moderate Minutes</label>
										<input type="mins_two" class="form-control" id="mins_two" name="mins_two"
											   value="{{!empty($setup->mins_two) ? $setup->mins_two: '' }}"
												 placeholder="Enter Minutes">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="colour_three" class="col-form-label">Critical </label>
										<input type="color" class="form-control" id="colour_three" name="colour_three"
											   value="{{!empty($setup->colour_three) ? $setup->colour_three: '' }}"
												 placeholder="Enter Color">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="mins_three" class="col-form-label">Critical Minutes</label>
										<input type="mins_three" class="form-control" id="mins_three" name="mins_three"
											   value="{{!empty($setup->mins_three) ? $setup->mins_three: '' }}"
												 placeholder="Enter Minutes">
									</div>
								</div>
							</div>
							<br>
							<button type="submit" class="btn btn-primary waves-effect waves-light">Save Settings
							</button>
						</form>
					</div> <!-- end card-body -->
				</div> <!-- end card-->
			</div> <!-- end col -->
		</div>
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

            

        });

    </script>
@endsection

