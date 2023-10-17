<div class="modal fade" id="edit-sms-patient-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-sms-patient-form">
                {{ csrf_field() }}
{{--                {{ method_field('PATCH') }}--}}

                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Edit Sms</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
{{--                    <h5 class="mt-0 mb-0 font-15">--}}
{{--                        <a href="contacts-profile.html" class="text-reset center-block">James Zavel</a>--}}
{{--                    </h5>--}}
                    <div class="modal-body p-4">

                        <div class="form-group">
                            <label for="name"> Number of sms </label>
                            <input type="number" class="form-control" id="sms_count"
                                   name="sms_count"  placeholder="Enter NUmber of Sms" required>
                        </div>

                    </div>

                    <input type="hidden" name="formId" id="formId" value="">

                    <div class="text-right">
                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close
                        </button>
                        <button type="button" id="edit-sms-patient" class="btn btn-success waves-effect waves-light">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


