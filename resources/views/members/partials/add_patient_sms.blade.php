<div class="modal fade" id="add-sms-patient-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-patient_sms-form">
                {{ csrf_field() }}

                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Add Sms</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="modal-body p-4">

                        <div class="form-group">
                            <label for="name"> Number of sms </label>
                            <input type="number" class="form-control" id="sms_count"
                                   name="sms_count"  placeholder="Enter NUmber of Sms" required>
                        </div>


                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close
                        </button>
                        <button type="button" id="add-patient-sms" class="btn btn-success waves-effect waves-light">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



