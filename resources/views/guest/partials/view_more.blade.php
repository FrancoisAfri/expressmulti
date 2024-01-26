<!-- Signup modal content -->
<div id="view-more-moda" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="scrollableModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
				<div class="modal-header bg-light">
					<h4 class="modal-title" id="myCenterModalLabel">Menu Information</h4>
				</div>
				<div class="modal-body">
					<div id="invalid-input-alert"></div>
					<div id="success-alert"></div>
					<div class="modal-body p-4">
						<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger"></span></label>
                                        <input type="text" class="form-control"
                                               id="name" name="name" readonly style="border: 0px none;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description"> Description <span class="text-danger"></span></label>
                                        <input type="text" class="form-control"
                                               id="description" name="description" readonly style="border: 0px none;">
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" type="number"
                                               id="price" name="price" readonly style="border: 0px none;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="number" class="form-control"
                                               id="calories" name="calories" readonly style="border: 0px none;">
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ingredients"> Ingredients <span class="text-danger"></span></label>
										<textarea name="ingredients" id="ingredients" readonly style="border: 0px none;" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Categories </label>
										<input type="text" class="form-control"
                                               id="category" name="category" readonly style="border: 0px none;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type </label>
										<input type="text" class="form-control"
                                               id="type" name="type" readonly style="border: 0px none;">
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Image <span class="text-danger"></span></label>
										<div class="popup-thumbnail img-responsive">
											<img src="{{ asset('storage/assets/Images/'.$menu->image) }} "
												 height="35px" width="40px" alt="Image">
										</div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description"> Video <span class="text-danger"></span></label>
                                        <div class="popup-thumbnail img-responsive">
											<video  height="60" width="150" controls>
												<source src="{{URL::asset("storage/public/Videos/$menu->video")}}" type="video/mp4">
												Your browser does not support the video tag.
											</video>
										</div>
                                    </div>
                                </div>
                            </div>
						<div class="form-group text-center"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
					</div>
				</div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>