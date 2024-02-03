<!-- Signup modal content -->
<div id="view-cart-modal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="scrollableModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="needs-validation" novalidate method="Post" name="cart-form"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-header bg-light">
                        <h4 class="modal-title" id="myCenterModalLabel">Cart</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="invalid-input-alert"></div>
                        <div id="success-alert"></div>
                        <div class="modal-body p-4">
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
									<a href="/restaurant/place-order/{{$table->id}}"><i class="btn btn-success waves-effect waves-light col-12 ">Place Order</i></a>
								</div>
							@else
								<div class="alert alert-danger alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									Cart empty
								</div>
							@endif
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
