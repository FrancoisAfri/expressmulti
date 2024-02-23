<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyIdentity;
use App\Services\RestaurantService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use App\Services\CompanyIdentityService;
use App\Models\MenuType;
use App\Models\Categories;
use App\Models\Menu;
use App\Models\Cart;
use App\Models\Tables;
use App\Models\Orders;
use App\Models\EventsServices;
use App\Models\OrdersProducts;
use App\Models\OrdersServices;
use App\Models\ServiceType;
use App\Models\HRPerson;
use App\Models\CloseRequests;
use App\Models\TableScans;
use App\Models\RestaurantSetup;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\DB;
use App\Events\NewRecordAdded;

class RestaurantGuestController extends Controller
{	
	use BreadCrumpTrait, CompanyIdentityTrait;
	
	/**
     * @var PatientService
     */
    private $restaurantService;
	private $companyIdentityService;
	
	public function __construct(RestaurantService $restaurantService, CompanyIdentityService $companyIdentityService)
    {
        $this->RestaurantService = $restaurantService;
		$this->companyIdentityService = $companyIdentityService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Tables $table)
    {
		if (empty($table->status))
		{
			return redirect()->route("qr_code.activate");
		}
		// get table last scanned
		$scanned = TableScans::where('table_id', $table->id)->where('status', 1)->orderBy('id', 'desc')->first();
		$type = !empty($request['type']) ? $request['type'] : 0;
		$categoty = !empty($request['categoty']) ? $request['categoty'] : 0;
		if (!empty($scanned->status)  &&  $scanned->status === 1)
		{
			//  Restaurant ordering page
			
			$localName = (!empty($scanned['nickname'])) ? $scanned['nickname'] : '';
			// data to display on views
			$data = $this->breadcrumb(
				"Welcome $localName",
				"",
				'restaurant_details',
				'Home Page',
				'Restaurant Menu & Services'
			);

			// get restaurant Manager
			$user =  DB::table('users')->select('users.*', 'model_has_roles.*')
				->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
				->where('model_has_roles.role_id', 3)
				->first();
			if (!empty($user->id))
				$manager = HRPerson::where('user_id',$user->id)->first();
			else $manager = '';
			// get orders and service history
			$orders = Orders::getOderByTable($table->id, $scanned->id);
			$menuTypes = MenuType::getMenuTypes();
			$Categories = Categories::getCategories();
			// get orders and service history
			$orders = Orders::getOderByTable($table->id,$scanned->id);
			$ordersServices = OrdersServices::getServicesByTable($table->id,$scanned->id);
			
			$data['orders'] = $orders;
			$data['ordersServices'] = $ordersServices;
			if (!empty($user->id))
				$data['avatar'] = $this->companyIdentityService->getAvatar($user->id);
			else $data['avatar'] = '';
			$data['menus'] = Menu::getMenus($type, $categoty);
			$data['manager'] = $manager;
			$data['menuTypes'] = $menuTypes;
			$data['Categories'] = $Categories;
			$data['categoty'] = $categoty;
			$data['menu_type'] = $type;
			$data['orders'] = Orders::getOderByTable($table->id, $scanned->id);
			$data['carts'] = Cart::getCart($table->id);
			$data['table'] = $table;
			$data['localName'] = $localName;
			$data['services'] = ServiceType::getServices();
			return view('guest.index')->with($data);
		}
		else
		{
			$ipAddress = $this->getUserIpAddress($request);
			
			$scanned = TableScans::create([
					'ip_address' => $ipAddress,
					'table_id' => $table->id,
					'scan_time' => time(),
					'status' => 1,
				]);
			$localName = '';
			Alert::toast('This table have been closed !!! Please scan a qr code', 'warning');
			return back();
			//return redirect('/patients/booking_calender');
			//eturn redirect()->route("seating.plan", $table->id);
		}
    }
	// inactive qr code
	public function inactiveQrcode()
    {
		$data['localName'] = '';
		return view('guest.inactive_qr_code')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serviceRequest(Tables $table, ServiceType $service)
    {
        //
		$newRequest = $this->RestaurantService->requestService($table, $service);
		
        alert()->success('SuccessAlert', "Your: $service->name request have been submitted, It been atttended to");
        activity()->log("New Service Request Added: $service->name ");

		return redirect()->back();
    }
	
	// order for meal
	public function orderRequest(Tables $table, ServiceType $service)
    {
        //
		$newRequest = $this->RestaurantService->requestService($table, $service);
		
        alert()->success('SuccessAlert', "Your: $service->name request have been submitted, It been atttended to");
        activity()->log("New Service Request Added: $service->name ");

		return redirect()->back();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rateService(Request $request, TableScans $scan)
    {
		$comment = !empty($request['comments']) ? $request['comments'] : '';

		$scan->comment = $comment;
		$scan->update();
		
		Alert::toast('Thank you for your comment. We looking for to your next visist', 'success');
		activity()->log('Client Added Comment');
		return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	// close request
	public function closeTableRequest(Tables $table)
    {
		
		$scanned = TableScans::where('table_id', $table->id)->where('status', 1)->orderBy('id', 'desc')->first();
		// save close request
		$closeRequests = CloseRequests::create([
                'table_id' => $table->id,
                'scan_id' => $scanned->id,
                'status' => 1,
            ]);
			
		// save services requestService
		$EventsServices = EventsServices::create([
				'scan_id' => $scanned->id,
				'table_id' => $table->id,
				'service_type' => 3,
				'requested_time' => time(),
				'service' => "Close Request",
				'item_id' => $closeRequests->id,
				'status' => 1,
			]);
		// call event
		// Dispatch the event
		event(new NewRecordAdded($EventsServices));
		//alert
        alert()->success('SuccessAlert', "Your request to close table have been submitted. Your waiter will come to you shortly.");
        activity()->log("New close table Request Added");

		return redirect()->back();
    }
	
	public function saveName(Request $request, TableScans $scan)
    {
		$nickname = !empty($request['nickname']) ? $request['nickname'] : '';
		
		$scan->nickname = $nickname;
		$scan->update();
		Alert::toast('Thank you!!! You may continue.', 'success');
        activity()->log('Table Closed Successfully');
		return back();
		
    }
	//save order
	public function storeOrder(Tables $table)
    {
		///  save order 
		$scanned = TableScans::where('table_id', $table->id)->where('status', 1)->orderBy('id', 'desc')->first();
		// checck if product have already been added
		$cart = Cart::where('table_id', $table->id)
				//->where('scan_id', $scanned->id)
				->get();

		$order = Orders::create([
						'table_id' => $table->id,
						'scan_id' => $scanned->id,
						'status' => 1,
					]);
		$orderNumber = 'ORD' . sprintf('%07d', $order->id);
        $order->order_no = $orderNumber;
        $order->update();
		// save items into order products table
		$menuOrder = "Menu Order: ";
		$comment = '';
		foreach($cart as $item) {
			
			$OrdersProduct = OrdersProducts::create([
						'product_id' => $item->product_id,
						'comment' => $item->comment,
						'table_id' => $item->table_id,
						'order_id' => $order->id,
						'price' => $item->price,
						'quantity' => $item->quantity,
						'amount' => ($item->quantity * $item->price) ,
						'status' => 1,
					]);
			// add service request variable
			$menu = Menu::where('id', $item->product_id)->first();
			$menuOrder .= "$menu->name, Quantity: $item->quantity | ";
			$comment .= $item->comment." | ";
			// delete item from cart
			$item->delete();
		}
		// save service request
		$EventsServices = EventsServices::create([
					'scan_id' => $scanned->id,
					'table_id' => $item->table_id,
					'service_type' => 2,
					'requested_time' => time(),
					'service' => $menuOrder,
					'comment' => $comment,
					'item_id' => $order->id,
					'status' => 1,
				]);
		// call event
		// Dispatch the event
		//event(new NewRecordAdded($EventsServices));	
		Alert::toast('Your Order have been submitted.', 'success');
		activity()->log('New Order Added');
		//return response()->json(['message' => 'success'], 200);
		return  back();
    }
	//save cart
	public function saveCart(Request $request, Tables $table, Menu $menu)
    {
		$quantity = !empty($request->input('quantity')) ? $request->input('quantity') : 0;
		$comment = !empty($request->input('comment')) ? $request->input('comment') : '';
		$scanned = TableScans::where('table_id', $table->id)->where('status', 1)->orderBy('id', 'desc')->first();
		// checck if product have already been added
		$cart = Cart::where('table_id', $table->id)
				->where('product_id', $menu->id)
				->where('scan_id', $scanned->id)
				->first();
		if (empty($cart))
		{
			/// save item into cart
			$cart = Cart::create([
						'product_id' => $menu->id,
						'comment' => $comment,
						'quantity' => $quantity,
						'table_id' => $table->id,
						'scan_id' => $scanned->id,
						'price' => $menu->price,
						'status' => 1,
					]);
		}
		else
		{
			$cart->comment = $comment;
			$cart->quantity = $quantity;
			$cart->update();
		}
		//Alert::toast('Your item have been added to cart.', 'success');
		activity()->log('New item added to cart');
        return response()->json(['message' => 'success'], 200);
		
    }
	//delete cart
	public function deleteItems(Cart $cart)
    {
		
		$cart->delete();
		Alert::toast('Your item have been deleted from cart.', 'success');
		activity()->log('Item deleted From Cart');
        return  back();
		
    }
	// ip address
	/*public function getUserIpAddress(Request $request)
    {
        $ipAddress = $request->ip();
        if (empty($ipAddress)) {
            $ipAddress = $request->server('REMOTE_ADDR');
        }
        
    }*/
	public function getUserIpAddress(Request $request)
	{
		$ipAddress = $request->ip();
		if (empty($ipAddress)) {
            $ipAddress = $request->server('REMOTE_ADDR');
        }
		return $ipAddress;
	}
}
