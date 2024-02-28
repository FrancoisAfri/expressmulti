<!-- Signup modal content -->
<div id="add-new-menu-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="scrollableModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="needs-validation" novalidate method="Post" name="add-menu-form" enctype="multipart/form-data">
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
                        <h4 class="modal-title" id="myCenterModalLabel">Add Menu</h4>
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
                                        <label for="description"> Description <span class="text-danger"></span></label>
										<textarea id="description" class="form-control" name="description" rows="3" cols="50"></textarea>
                                        <div class="invalid-feedback">
                                            Please provide Description.
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" type="number" step="0.01"
                                               id="price" name="price" placeholder="Enter Price" required>
                                        <div class="invalid-feedback">
                                            Please provide Price.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kJ"> kJ <span class="text-danger"></span></label>
                                        <input type="number" class="form-control"
                                               id="calories" name="calories" placeholder="Enter kJ">
                                        <div class="invalid-feedback">
                                            Please provide kJ.
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ingredients"> Ingredients <span class="text-danger"></span></label>
										<textarea id="ingredients" class="form-control" name="ingredients" rows="3" cols="50"></textarea>
                                        <div class="invalid-feedback">
                                            Please provide Ingredients.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Categories <span class="text-danger">*</span></label>
										<select class="form-control" name="category_id"
											  id="category_id"  data-toggle="select2"
												data-placeholder="Choose ...">
											<option value="">Select a category ...</option>
											@foreach($categories as $category)
												<option
													value="{{ $category->id }}">{{ $category->name }}
												</option>
											@endforeach
										</select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Menu Type <span class="text-danger">*</span></label>
										<select class="form-control" name="menu_type"
											  id="menu_type"  data-toggle="select2"
												data-placeholder="Choose ...">
											<option value="">Select a Type ...</option>
											@foreach($menusTypes as $type)
												<option
													value="{{ $type->id }}">{{ $type->name }}
												</option>
											@endforeach
										</select>
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Image <span class="text-danger"></span></label>
                                        <input type="file" id="image" name="image" data-plugins="dropify"
                                       data-allowed-file-extensions='["jpg", "jpeg", "png"]'
                                       data-show-upload="false">
										<strong> Allowed filetypes are jpg, jpeg, png. Max upload size allowed is 500x500."</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description"> Video <span class="text-danger"></span></label>
                                        <input type="file" id="video" name="video" data-plugins="dropify"
                                   data-allowed-file-extensions='["mp4"]' data-show-upload="false">
									<strong> Allowed filetypes are mp4. Max upload size allowed is 20M."</strong>
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sequence"> Sequence <span class="text-danger"></span></label>
                                        <input type="number" class="form-control"
                                               id="sequence" name="sequence" placeholder="Enter Sequence">
                                        <div class="invalid-feedback">
                                            Please provide sequence.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" id="add-menu" class="btn btn-success waves-effect waves-light">
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