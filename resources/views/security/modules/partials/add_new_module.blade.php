<div class="modal fade" id="add-new-module-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-module-form">
                {{ csrf_field() }}

                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Add New Module</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <div class="modal-body p-4">
                        <div class="form-group">
                            <label for="name"> Name</label>
                            <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Module name" required>
                        </div>

                        <div class="form-group">
                            <label for="name"> Code Name</label>
                            <input type="text" class="form-control" id="code_name" name="code_name"  placeholder="Enter Module's Unique Code Name (all small letters, no spaces)" required>
                        </div>

                        <div class="form-group">
                            <label for="name"> Path</label>
                            <input type="text" class="form-control" id="path" name="path"  placeholder="Enter Module Path" required>
                        </div>

                        <div class="form-group">
                            <label for="name"> Icon</label>
                            <input type="text" class="form-control" id="font_awesome" name="font_awesome"  placeholder="Enter Icon" required>
                        </div>

                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close
                        </button>
                        <button type="button" id="add-module" class="btn btn-success waves-effect waves-light">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


