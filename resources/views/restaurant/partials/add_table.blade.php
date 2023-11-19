<!-- Signup modal content -->
<div id="add-new-table-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="scrollableModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="needs-validation" novalidate method="Post" name="add-table-form"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="modal-header bg-light">
                        <h4 class="modal-title" id="myCenterModalLabel">Add Table</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="invalid-input-alert"></div>
                        <div id="success-alert"></div>
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               id="name" name="name" placeholder="Enter Name" required>

                                        <div class="invalid-feedback">
                                            Please provide Name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="number_customer"> Number Customers <span class="text-danger"></span></label>
                                        <input type="number" class="form-control"
                                               id="number_customer" name="number_customer" placeholder="Enter Number Customers">

                                        <div class="invalid-feedback">
                                            Please provide Number Customers.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Employees </label>
										<select class="form-control" name="employee_id"
											  id="employee_id"  data-toggle="select2"
												data-placeholder="Choose ...">
											<option value="">Select an Employee ...</option>
											@foreach($users as $user)
												<option
													value="{{ $user->id }}">{{ $user->first_name." ".$user->surname }}
												</option>
											@endforeach
										</select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" id="add-table" class="btn btn-success waves-effect waves-light">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>