<!-- Signup modal content -->
<div id="add-new-contact-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="scrollableModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="needs-validation" novalidate method="Post" name="add-contact-form">
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
                                        <label for="heard"> Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               id="first_name" name="dependency_first_name" placeholder="John" required>

                                        <div class="invalid-feedback">
                                            Please provide First Name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="heard"> Surname <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                               id="surname" name="dependency_surname" placeholder="Doe" required>

                                        <div class="invalid-feedback">
                                            Please provide Surname.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Birth <span class="text-danger">*</span></label>
                                        <input type="text" id="my_datepicker" name="dependency_date_of_birth"
                                               value=""
                                               class="form-control" placeholder="Pick a date">
                                        <div class="invalid-feedback">
                                            Please provide Date of Birth.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passport_number"> Age </label>
                                        <input type="text" class="form-control" id="age"
                                               name="dependency_age" value=""
                                               placeholder="" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="heard"> Depandancy code <span class="text-danger">*</span></label>
                                        <input type="text"   class="form-control"
                                               id="dependency_code" name="dependency_code" placeholder="Enter Depandancy code" required maxlength="6">
                                        <div class="invalid-feedback">
                                            Please provide Surname.
                                        </div>
                                        <span class="form-text text-muted"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="d_id_number" name="d_radioInline" checked>
                                    <label for="inlineRadio1"> ID NUMBER <span class="text-danger">*</span></label>
                                </div>
                                <br>
                                <div class="radio radio-info form-check-inline">
                                    <input type="radio" id="radio" value="d_passport" name="d_radioInline">
                                    <label for="inlineRadio2"> PASSPORT <span class="text-danger">*</span> </label>
                                </div>
                            </div>
                            <br>
                            <div id="d-id_number">
                                <div class="row id_number box">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_number" id="lbl_id_number">ID Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="id_number"
                                                   maxlength="13" value="" name="dependency_id_number"
                                                   placeholder="Enter ID Number">
                                        </div>
                                    </div> <!-- end col -->
                                </div>
                            </div>
                            <input type="hidden" id="patient_id"  name="patient_id"
                                   value=" {{ (!empty($patient->id)) ?  $patient->id : ''}}">
                            <div id="d_passport">
                                <div class="row passport box">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="passport_number" id="lbl_pass"> Passport Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="passport_number"
                                                   name="dependency_passport_number" value=""
                                                   placeholder="Enter Passport Number (optional)" maxlength="10">
                                        </div>
                                    </div><!-- end col -->
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" id="add-contact" class="btn btn-success waves-effect waves-light">
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