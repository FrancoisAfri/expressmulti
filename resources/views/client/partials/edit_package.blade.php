<!-- Signup modal content -->
<div id="edit-package-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="scrollableModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="needs-validation" novalidate method="Post" name="edit-package-form">
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
                        <h4 class="modal-title" id="myCenterModalLabel">Edit Package</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="invalid-input-alert"></div>
                        <div id="success-alert"></div>
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="package_name"> Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               id="package_name" name="package_name" placeholder="Enter Name" required>
                                        <div class="invalid-feedback">
                                            Please Enter Name.
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no_table"> No of Table <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control"
                                               id="no_table" name="no_table" placeholder="No of Table" required>

                                        <div class="invalid-feedback">
                                            Please Enter No of Table.
                                        </div>
                                    </div>
                                </div>
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price"> Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control"
                                               id="price" name="price" placeholder="Price" required>

                                        <div class="invalid-feedback">
                                            Please Enter No of Table.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" id="edit-package" class="btn btn-success waves-effect waves-light">
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