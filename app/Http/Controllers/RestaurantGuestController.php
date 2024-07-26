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
use App\Models\OrdersHistory;
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
			return redirect()->route("qr_code.activate");
		
		// get table last scanned
		$scanned = TableScans::where('table_id', $table->id)->where('status', 1)->orderBy('id', 'desc')->first();
		$type = !empty($request['type']) ? $request['type'] : 0;
		$category = !empty($request['category']) ? $request['category'] : 0;
		if (!empty($scanned->status)  &&  $scanned->status === 1)
		{
			//  Restaurant ordering page
			//return $services;
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
			// get avatar
			if (!empty($table->employee_id))
			{
				$hrUser = HRPerson::where('id', $table->employee_id)->first();
				$defaultAvatar = ($hrUser->gender === 0) ? asset('images/m-silhouette.jpg') : asset('images/f-silhouette.jpg');
				$profilePic = (!empty( $hrUser->profile_pic)) ? asset('uploads/' . $hrUser->profile_pic) : $defaultAvatar;
			}
			else $profilePic = '';
			
			$data['scanned'] = $scanned;
			$data['profilePic'] = $profilePic;
			$data['menus'] = Menu::getMenus($type, $category);
			$data['manager'] = $manager;
			$data['menuTypes'] = $menuTypes;
			$data['Categories'] = $Categories;
			$data['category'] = $category;
			$data['menu_type'] = $type;
			$data['orders'] = Orders::getOderByTable($table->id, $scanned->id);
			$data['carts'] = Cart::getCart($table->id);
			$data['table'] = $table;
			$data['localName'] = $localName;
			$data['events'] =  EventsServices::getUserRequests($table->id, $scanned->id);
			$data['serviceRequests'] = ServiceType::getServices();
			$data['resquest_type'] = EventsServices::SERVICES_SELECT;
			return view('guest.index')->with($data);
		}
		else
		{
			$data['table'] = $table;
			return view('guest.index')->with($data);
		}
    }
	// open a new table
	public function openTable(Request $request, Tables $table)
    {
		
		// open new table
		$nickname = !empty($request['nickname']) ? $request['nickname'] : '';
		$ipAddress = $this->getUserIpAddress($request);
			
		$scanned = TableScans::create([
				'ip_address' => $ipAddress,
				'table_id' => $table->id,
				'waiter' => $table->employee_id,
				'scan_time' => time(),
				'nickname' => $nickname,
				'status' => 1,
			]);
		// send waiter notification
		$action = "New Table Scanned" ;	
		$history = OrdersHistory::create([
			'action' => $action,
			'table_id' => $table->id,
		]);
		$EventsServices = EventsServices::create([
						'scan_id' => $scanned->id,
						'table_id' => $table->id,
						'service_type' => 1,
						'waiter' => $table->employee_id,
						'requested_time' => time(),
						'service' => "New Table Scanned",
						'item_id' => 0, 
						'status' => 1,
					]);
		// get waiter user token
		$waiter = HRPerson::find($table->employee_id);
		$waiter = $waiter->load('user');
		$userFcmToken = !empty($waiter->user->user_fcm_token) ? $waiter->user->user_fcm_token : '' ;
		// send Push notification
		//$this->sendPush($userFcmToken, $EventsServices);
		Alert::toast('Thank you!!! You may continue.', 'success');
        activity()->log('Table Name Saved Successfully');
		return back();
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
		$service = $this->RestaurantService->requestService($table, $service);
		
		// get waiter user token
		$waiter = HRPerson::find($service->waiter);
		$waiter = $waiter->load('user');
		$userFcmToken = !empty($waiter->user->user_fcm_token) ? $waiter->user->user_fcm_token : '' ;
		// send Push notification
		//$this->sendPush($userFcmToken, $service);

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
		$q_one = !empty($request['q_one']) ? $request['q_one'] : '';
		$q_two = !empty($request['q_two']) ? $request['q_two'] : '';
		$q_three = !empty($request['q_three']) ? $request['q_three'] : '';
		$q_four = !empty($request['q_four']) ? $request['q_four'] : '';

		$scan->comment = $comment;
		$scan->q_one = $q_one;
		$scan->q_two = $q_two;
		$scan->q_three = $q_three;
		$scan->q_four = $q_four;
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
		if (!empty($scanned->id))
		{
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
					'waiter' => $table->employee_id,
					'status' => 1,
				]);
			// get waiter user token
			$waiter = HRPerson::find($table->employee_id);
			$waiter = $waiter->load('user');
			$userFcmToken = !empty($waiter->user->user_fcm_token) ? $waiter->user->user_fcm_token : '' ;
			// send Push notification
			//$this->sendPush($userFcmToken, $EventsServices);
			alert()->success('SuccessAlert', "Your request to close table have been submitted. Your waiter will come to you shortly.");
			activity()->log("New close table Request Added");
		}
		else alert()->success('SuccessAlert', "Your request to close table can not be processed. This is already closed.");
		
		return redirect()->back();
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
						'waiter' => $table->employee_id,
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
					'waiter' => $table->employee_id,
					'comment' => $comment,
					'item_id' => $order->id,
					'status' => 1,
				]);
		// get waiter user token
		$waiter = HRPerson::find($table->employee_id);
		$waiter = $waiter->load('user');
		$userFcmToken = !empty($waiter->user->user_fcm_token) ? $waiter->user->user_fcm_token : '' ;
		// send Push notification
		//$this->sendPush($userFcmToken, $EventsServices);
		
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
	
	//This function is needed, because php doesn't have support for base64UrlEncoded strings
    function base64UrlEncode($text) {
        return str_replace(
                ['+', '/', '='],
                ['-', '_', ''],
                base64_encode($text)
        );
    }
	
	public function getGoogleAccessToken() {
		
		$fcmFile = base_path('app/Fcm_keys/');
        $currentTokenString = file_get_contents($fcmFile . "fcm_token.json");
        $currentToken = json_decode($currentTokenString);

        if ($currentToken->access_token) {
            if (time() - $currentToken->time < 3590) {
                return $currentToken->access_token;
            }
        }
        // Read service account details
        $authConfigString = file_get_contents($fcmFile . "afrixcel-3098e-firebase-adminsdk-9fbd2-4789c8f582.json");

		// Parse service account details
        $authConfig = json_decode($authConfigString);

		// Read private key from service account details
        $secret = openssl_get_privatekey($authConfig->private_key);

		// Create the token header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'RS256'
        ]);

		// Get seconds since 1 January 1970
        $time = time();

		// Allow 1 minute time deviation between client en server
        $start = $time - 60;
        $end = $start + 3600;

		// Create payload
        $payload = json_encode([
            "iss" => $authConfig->client_email,
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => "https://oauth2.googleapis.com/token",
            "exp" => $end,
            "iat" => $start
        ]);

		// Encode Header
        $base64UrlHeader = $this->base64UrlEncode($header);

		// Encode Payload
        $base64UrlPayload = $this->base64UrlEncode($payload);

		// Create Signature Hash
        $result = openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $secret, OPENSSL_ALGO_SHA256);

		// Encode Signature to Base64Url String
        $base64UrlSignature = $this->base64UrlEncode($signature);

		// Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

		//-----Request token, with an http post request------
        $options = array('http' => array(
                'method' => 'POST',
                'content' => 'grant_type=urn:ietf:params:oauth:grant-type:jwt-bearer&assertion=' . $jwt,
                'header' => "Content-Type: application/x-www-form-urlencoded"
        ));
        $context = stream_context_create($options);
        $now = time();
        $responseText = file_get_contents("https://oauth2.googleapis.com/token", false, $context);

        $response = json_decode($responseText);

        $json = array("access_token" => $response->access_token, "time" => $now);
        file_put_contents($fcmFile . "fcm_token.json", json_encode($json));
        return $response->access_token;
    }
	
	public function sendPush($userFcmToken, $service) {

        $apiurl = 'https://fcm.googleapis.com/v1/projects/afrixcel-3098e/messages:send';

        $headers = [
            'Authorization: Bearer ' . $this->getGoogleAccessToken(),
            'Content-Type: application/json'
        ];

        $data = array(
            'id' => "$service->id",
            "uuid" => $service->uuid, 
            "item_id" => "$service->item_id",
            "table_id" => "$service->table_id",
            "scan_id" => "$service->scan_id",
            "service_type" => "$service->service_type",
            "requested_time" => "$service->requested_time",
            "completed_time" => "",
            "service" => $service->service,
            "status" => "1",
            "comment" => $service->comment,
            "created_at" => $service->created_at,
            "updated_at" => $service->updated_at,
            "waiter" => "$service->waiter",
            "table_name" => $service->tables->name,

        );
        //The $in_app_module array above can be empty - I use this to send variables in to my app when it is opened, so the user sees a popup module with the message additional to the generic task tray notification.

        $message = [
            'message' => array(
                "token" => $userFcmToken,
				"notification" => array("title" => "New service request", "body" => $data["service"]),
                "data" => $data,
                "android" => array("priority" => "high"),
                "apns" => array("headers" => array("apns-priority" => "5"))
            )
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiurl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);

        if ($result === FALSE) {
            //Failed
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);
    }
}
