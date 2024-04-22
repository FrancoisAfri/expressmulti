<div class="modal fade" id="view-more-modal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content border-0">
	   <div class="modal-header border-0">
		  <h5 class="modal-title" id="menu_name"></h5>
		  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	   </div>
		<form class="needs-validation" novalidate method="Post" name="add-cart-form">
            {{ csrf_field() }}
			<div id="invalid-input-alert"></div>
            <div id="success-alert"></div>
		   <div class="modal-body p-0">
				<div>
					<img id="item_image" src="" alt="" class="img-fluid">
				</div>
			  <div class="p-3">
				 <p class="fw-bold mb-1" id="name"></p> </h6> <h6 class="small text-muted mb-6" id="ingredients"></h6> <h3 class="small text-muted mb-6" id="calories"></h3>
				 
				 <h6 class="fw-bold mb-1" id="price">
			  </div>
			</div>
			<div class="modal-footer d-flex justify-content-between">
				<div class="btn btn-white border border-danger col-3 px-1">
					<div class="d-flex align-items-start justify-content-between px-1">
						<div class="minus"><i class="fa-solid fa-minus text-danger"></i></div>
						<input class="shadow-none form-control text-center border-0 p-0 box" type="text" placeholder=""
							aria-label="default input example" value="" name="quantity" id="quantity">
						<div class="plus"><i class="fa-solid fa-plus text-danger"></i></div>
					</div>
				</div>
				<textarea id="comment" name="comment" rows="3" cols="40" placeholder="Enter your comment here"></textarea>
			</div>	
			<button type="button" id="add-item-cart" class="btn btn-success waves-effect waves-light col-12 ">
				Add item
			</button>
		</form>
	</div>
 </div>
</div>