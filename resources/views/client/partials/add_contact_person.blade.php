<!-- Signup modal content -->
<div id="add-new-contact-person-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="scrollableModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="needs-validation" novalidate method="Post" name="add-contact-person-form">
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
                        <h4 class="modal-title" id="myCenterModalLabel">Add Contact Person</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="invalid-input-alert"></div>
                        <div id="success-alert"></div>
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="heard"> First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               id="first_name" name="first_name" placeholder="Enter First Name" required>

                                        <div class="invalid-feedback">
                                            Please provide First Name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="heard"> Surname <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               id="surname" name="surname" placeholder="Enter Surname" required>

                                        <div class="invalid-feedback">
                                            Please provide Surname.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Number<span class="text-danger">*</span></label>
                                        <input type="text" id="contact_number" name="contact_number"
                                               value=""
                                               class="form-control" placeholder="Enter Contact Number">
                                        <div class="invalid-feedback">
                                            Please provide Contact Number.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
										<label>Email<span class="text-danger">*</span></label>
                                        <input type="text" id="email" name="email"
                                               value=""
                                               class="form-control" placeholder="Enter Email">
										<div class="invalid-feedback">
                                            Please provide Email.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="company_id"  name="company_id"
                                   value=" {{ (!empty($client->id)) ?  $client->id : ''}}">
                            <div class="form-group text-center">
                                <button type="button" id="add-contact-person" class="btn btn-success waves-effect waves-light">
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