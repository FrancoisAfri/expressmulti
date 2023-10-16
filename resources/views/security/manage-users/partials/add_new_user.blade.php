<!-- Signup modal content -->
<div id="add-new-user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <i class="mdi mdi-account-convert mr-2 text-muted font-18 vertical-middle"> New User</i>
                </div>

                <form class="form-horizontal" method="POST" name="add-user-form">
                    {{ csrf_field() }}

                    <div class="modal-header bg-light">
                        <h4 class="modal-title" id="myCenterModalLabel">Create New User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>

                    <div class="modal-body">
                        <div id="invalid-input-alert"></div>
                        <div id="success-alert"></div>
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">Name <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control"
                                               id="first_name" name="first_name" placeholder="John">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">Surname <span class="text-danger">*</span> </label>
                                        <input type="text" class="form-control"
                                               id="surname" name="surname" placeholder="Doe">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">Email address <span class="text-danger">*</span> </label>
                                        <input class="form-control" type="email" id="email"
                                               name="email" required="" placeholder="john@deo.com">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">Cell Number <span class="text-danger">*</span> </label>
                                        <input class="form-control" type="text"
                                               id="cell_number" name="cell_number"
                                               required="" placeholder="Enter Cellphone" maxlength="12">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name"> Roles</label>
                                <select id="roles" name="roles"
                                        class="form-control" required="">
                                    <option value="0">Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group text-center">
{{--                                <button class="btn btn-primary" type="submit">Create User</button>--}}
                                <button type="button" id="add-user" class="btn btn-success waves-effect waves-light">
                                    Create User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
