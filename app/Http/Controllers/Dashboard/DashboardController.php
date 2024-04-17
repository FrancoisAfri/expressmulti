<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingNotification;
use App\Models\CompanyIdentity;
use App\Models\HRPerson;
use App\Models\modules;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\CloseRequests;
use App\Models\EventsServices;
use App\Models\Url;
use App\Models\User;
use App\Models\Tables;
use App\Models\OrdersProducts;
use App\Models\TableScans;
use App\Models\Orders;
use App\Models\OrdersServices;
use App\Models\RestaurantSetup;
use App\Traits\CompanyIdentityTrait;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Services\RestaurantService;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;
use DateTime;
use Yajra\DataTables\DataTables;
class DashboardController extends Controller
{

    use CompanyIdentityTrait;
	/**
     * @var RestaurantService
     */
    private $restaurantService;
	
    /**
     * @var Notification
     */
    private $notification;


    public function __construct(RestaurantService $restaurantService)
    {
		$this->RestaurantService = $restaurantService;
    }


    public function calculateMonthlyProfit()
    {
        $target = $this->CompanyIdentityDetails();
        $unique = array();
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        foreach ($months as $month) {
            $coll = OrdersProducts::getSummaryByMonth($month);
            if (!empty($target['monthly_revenue_target']))
                $unique[] = $coll->sum('amount') / $target['monthly_revenue_target'] * 100;
            else $unique[] = 0;
        }

        return response($unique, 200);
    }

    public function getDailyProfit()
    {
        $target = $this->CompanyIdentityDetails();
        if (!empty($target['daily_revenue_target']))
            return OrdersProducts::getDailySummary()->sum('amount') / $target['daily_revenue_target'] * 100;
        else return 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $CompanyIdentity = $this->CompanyIdentityDetails(); 
		$services = EventsServices::getRequests();
		$setup = RestaurantSetup::where('id',1)->first();
		// get this year and month
		$year = date('Y');
		$month = date('m');
		
        $data['normal'] = !empty($setup->colour_one) ? $setup->colour_one : '';
        $data['moderate'] = !empty($setup->colour_two) ? $setup->colour_two : '';
        $data['critical'] = !empty($setup->colour_three) ? $setup->colour_three : '';
        $data['normal_mins'] = !empty($setup->mins_one) ? $setup->mins_one : '';
        $data['moderate_mins'] = !empty($setup->mins_two) ? $setup->mins_two : '';
        $data['critical_mins'] = !empty($setup->mins_three) ? $setup->mins_three : '';
        $data['targetRevenue'] = $CompanyIdentity['monthly_revenue_target'];
        $data['totalPayment'] = OrdersProducts::getDailySummary()->sum('amount');
        //$data['activePatients'] = Patient::totalPatients();
		$data['dailyData'] = $this->getDailyProfit();
		$data['activeModules'] = modules::where('active', 1)->get();
		$data['ordersServices'] = OrdersServices::getAllRequest();
		$data['CloseRequests'] = CloseRequests::getAllCloseRequests();
		//$data['orders'] = Orders::getOrders();
		$data['services'] = $services;
		$data['resquest_type'] = EventsServices::SERVICES_SELECT;
		$data['users'] = HRPerson::getAllUsers();
		$data['tables'] = Tables::getTablesScans();
		$data['totalOrders'] = OrdersProducts::totalPaidThisYear($year);
        $data['monthlyOrders'] = OrdersProducts::totalPaidThisMonth($year,$month);
        $data['totalIncompleteOrders'] = OrdersProducts::totalUnpaidThisYear($year);
        $data['monthlyIncompleteOrders'] = OrdersProducts::totalUnpaidThisMonth($year,$month);
        return view('dashboard.index')->with($data);
    }


    public function markNotification(Request $request)
    {

        $booking = $this->notification::where('notifiable_id', $request->id)->latest();
        $booking->update([
            'read_at' => $current_date_time = Carbon::now()->toDateTimeString()
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function closeTable(Tables $table)
    {
        $this->RestaurantService->closeTable($table); 
        Alert::toast('Table closed successfully', 'success');
        activity()->log('Table Closed Successfully');
        return back();
		
    }
	// close service
	public function closeService(EventsServices $service)
    {
		
        $this->RestaurantService->closeService($service); 
        Alert::toast('Service request successfully', 'success');
        activity()->log('Service Request Closed Successfully');
        return back();
		
    }
	// close request
	public function closeRequest(EventsServices $close)
    {
		
        $this->RestaurantService->closeRequest($close); 
        Alert::toast('Table closed successfully', 'success');
        activity()->log('Closed Request Successfully');
        return back();
		
    }
	// close request
	public function closeDeniedRequest(EventsServices $close)
    {
        $this->RestaurantService->closeDeniedRequest($close); 
        Alert::toast('Request closed successfully', 'success');
        activity()->log('Closed Request Successfully');
        return back();
		
    }
	// close order
	public function closeOrder(EventsServices $order)
    {
		//return $order;
        $this->RestaurantService->closeOrders($order); 
        Alert::toast('Request closed successfully', 'success');
        activity()->log('Request closed successfully');
        return back();
		
    }
    // delete order
	// close order
	public function deleteOrder(EventsServices $order)
    {
		//return $order;
        $this->RestaurantService->deleteOrders($order); 
        Alert::toast('Order deleted successfully', 'success');
        activity()->log('Order deleted successfully');
        return back();
		
    }
	/**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //old code
		/*if (!empty($services))
		{
			foreach ($services as $service) {
				
				$cur_date = new DateTime();
				//$cur_date = $cur_date->modify("+1 hours");  //fix the time since its an hour behind
				$cur_date = $cur_date->format('m/d/Y g:i A');
				$to_time = $service->requested_time;
				$from_time = strtotime($cur_date);

				$mins = round(abs($from_time - $to_time) / 60,2); //check the time difference

				// allocated color accordint to time
				if ($mins < 4)
					$color = "90EE90";
				elseif ($mins > 4 && $mins < 6)
					$color = "FFFF9E";
				elseif ($mins > 7)
					$color = "FF7F7F"; 
				
				$service->color = $color;
				$color = '';
				$mins = 0;
			}
		}*/
    } 
	
	// get API services
	public function getLatestServices()
	{
		$services = EventsServices::getRequests(); // Retrieve the latest services from the database
		
		return DataTables::of($services)->make(true);
	}
	// get API services
	public function getOpenServicesPerWaiter($waiter)
	{
		$services = EventsServices::getWaiterRequests($waiter); // Retrieve the latest services from the database
		
		return response()->json([
            'services' => $services
        ]);
	}

	// get API tables
	public function getTablesWaiter($waiter)
	{
		$tables = Tables::getTablesWaiter($waiter);
		foreach ($tables as $table) 
		{
			$table->table_status = TableScans::getTableStatus($table->id);
		}
		return $tables;
		/*return response()->json([
            'tables' => $tables
        ]);*/
	}
	// get API tables status 
	public function getTableStatus($table)
	{
		$status = TableScans::getTableStatus($table);
		
		return response()->json([
            'status' => $status
        ]);
	}
	// get API tables nickname 
	public function getTableNickname($table)
	{
		$nickname = TableScans::getTableName($table);
		
		return response()->json([
            'nickname' => $nickname
        ]);
	}
	// get API tables nickname 
	public function changeWaiterStatus(User $user)
	{
		$user->online == 1 ? $status = 0 : $status = 1;
        $user->online = $status;
        $user->update();
		$person = $user->load('person');
		return response()->json([
            'user' => $person
        ]);
	}
	public function closeTableAPI(Tables $table)
    {
        $this->RestaurantService->closeTable($table); 
        return response()->json([
            'success' => 'true'
        ]);
		
    }
	// close service
	public function closeServiceAPI(EventsServices $service)
    {
		
        $this->RestaurantService->closeService($service); 
        return response()->json([
            'success' => 'true'
        ]);
		
    }
	// close request
	public function closeRequestAPI(EventsServices $close)
    {
		
        $this->RestaurantService->closeRequest($close); 
        return response()->json([
            'success' => 'true'
        ]);
		
    }
	// close request
	public function closeDeniedRequestAPI(EventsServices $close)
    {
        $this->RestaurantService->closeDeniedRequest($close); 
        return response()->json([
            'success' => 'true'
        ]);
		
    }
	// close order
	public function closeOrderAPI(EventsServices $order)
    {
		//return $order;
        $this->RestaurantService->closeOrders($order); 
	    return response()->json([
			'success' => 'true'
		]);
		
    }
    // delete order
	// close order
	public function deleteOrderAPI(EventsServices $order)
    {
		//return $order;
        $this->RestaurantService->deleteOrders($order); 
        return response()->json([
            'success' => 'true'
        ]);
		
    }
}
