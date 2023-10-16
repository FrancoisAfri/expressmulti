<div class="modal fade" id="add-new-ribbon-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-ribbon-form">
                {{ csrf_field() }}

                <div class="modal-header bg-light">
                    <h4 class="modal-title" id="myCenterModalLabel">Add New Ribbon</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="modal-body p-4">
                        <div class="form-group">
                            <label for="name"> Name</label>
                            <input type="text" class="form-control" id="ribbon_name" name="ribbon_name"  placeholder="Enter ribbon name" required>
                        </div>

                        <div class="form-group">
                            <label for="name"> Description </label>
                            <input type="text" class="form-control" id="description" name="description"  placeholder="Enter ribbon Description" required>
                        </div>

                        <div class="form-group">
                            <label for="name"> Path</label>
                            <input type="text" class="form-control" id="ribbon_path" name="ribbon_path"  placeholder="Enter Ribbon Path" required>
                        </div>

                        <input type="hidden" value="{{ $modules['id'] }}" name="module_id" id="module_id">

                        <div class="form-group">
                            <label for="name"> Sort Number </label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"  placeholder="Enter Sort Number" required>
                        </div>

                        <div class="form-group">
                            <label for="name"> Icon</label>
                            <input type="text" class="form-control" id="font_awesome" name="font_awesome"  placeholder="Enter Icon" required>
                        </div>

                        <div class="form-group">
                            <label for="access_level">Access Right</label>
                            <select name="access_level" id="access_level" class="form-control" required="">
                                <option value="0">None</option>
                                <option value="1">Read</option>
                                <option value="2">Write</option>
                                <option value="3">Modify</option>
                                <option value="4">Admin</option>
                                <option value="5">Super User</option>
                            </select>
                        </div>

                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close
                        </button>
                        <button type="button" id="add-ribbon" class="btn btn-success waves-effect waves-light">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


