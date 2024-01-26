<div class="modal fade" id="view-cart-modal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content border-0">
	   <div class="modal-header border-0">
		  <h5 class="modal-title" id="menu_name"></h5>
		  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	   </div>
		<form class="needs-validation" novalidate method="Post" name="cart-form">
            {{ csrf_field() }}
		   @if (count($carts) > 0)
				@foreach($carts as $cart)
					<div class="mb-3 d-flex gap-2">
						<img src="{{ asset('libs/eatsome/img/veg.jpeg') }}"
							alt="" class="img-fluid">
						<div>
							<h6 class="fw-bold mb-1">{{ $cart->product->name ?? ''}} </h6>
							<p class="mb-0">{{ (!empty($cart->price)) ? 'R ' .number_format($cart->price, 2) : ''}}</p>
							<p class="small text-muted m-0">{{ $cart->product->categories->name ?? '' }}</p>
							<p class="small text-muted m-0">{{ $cart->product->menuType->name ?? '' }}</p>
							<div class="btn btn-white btn-sm border border-danger px-2 rounded">
								<div class="d-flex align-items-center justify-content-between gap-2">
									<div class="minus"><i class="fa-solid fa-minus text-danger"></i></div>
									<input size="3" class="shadow-none form-control text-center border-0 p-0 box" type="text" placeholder=""
									aria-label="default input example" value="{{ $cart->quantity?? ''}}" name="quantity">
									<div class="plus"><i class="fa-solid fa-plus text-danger"></i></div>
								</div>
							</div>
							<div class="trash">
								<a href="/restaurant/cart-trash/{{$cart->id}}"><i class="fa-solid fa-trash text-danger"></i></a>
							</div>
							
						</div>
					</div>
				@endforeach
				<div class="form-group text-center">
					<button type="button" id="place-order-cart" class="btn btn-success waves-effect waves-light col-12 ">
						Place Order
					</button>
				</div>
			@else
				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Cart empty
				</div>
			@endif
		</form>
	</div>
 </div>
</div>