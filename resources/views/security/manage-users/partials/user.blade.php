<div id="add-new-user-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-user-form">
                {{ csrf_field() }}
{{--                {{ method_field('PATCH') }}--}}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Licence Types</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="first_name" name="first_name" value=""
                                   placeholder="Enter name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label">Surname</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="surname" name="surname" value=""
                                   placeholder="Enter name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" value=""
                                   placeholder="Enter email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="path" class="col-sm-2 control-label"> cell_number</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cell_number" name="cell_number" value=""
                                   placeholder="Enter Description" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-user" class="btn btn-warning"><i
                            class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


