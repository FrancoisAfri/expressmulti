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
    private Booking $booking;
    //private BookingService $bookingService;


    public function __construct(

        BookingNotification   $notification, RestaurantService $restaurantService
    )
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

        $data['targetRevenue'] = $CompanyIdentity['monthly_revenue_target'];
        $data['totalPayment'] = OrdersProducts::getDailySummary()->sum('amount');
        // $data['activePatients'] = Patient::totalPatients();
		$services = EventsServices::getRequests();
		$data['dailyData'] = $this->getDailyProfit();
		$data['activeModules'] = modules::where('active', 1)->get();
		$data['ordersServices'] = OrdersServices::getAllRequest();
		$data['CloseRequests'] = CloseRequests::getAllCloseRequests();
		//$data['orders'] = Orders::getOrders();
		$data['services'] = $services;
		$data['resquest_type'] = EventsServices::SERVICES_SELECT;
		$data['users'] = HRPerson::getAllUsers();
		$data['tables'] = Tables::getTablesScans();
        return view('dashboard.index')->with($data);
    }

    public function getBookingsDash()
    {
        $bookings = $this->bookingService->getAllBookings();
        return response($bookings, 200);
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
        Alert::toast('Table Closed Successfully', 'success');
        activity()->log('Table Closed Successfully');
        return back();
		
    }
	// close service
	public function closeService(EventsServices $service)
    {
		
        $this->RestaurantService->closeService($service); 
        Alert::toast('Service Request Successfully', 'success');
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
        //
    }

}
