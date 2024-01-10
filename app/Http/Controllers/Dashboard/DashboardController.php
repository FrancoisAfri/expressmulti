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
use App\Models\Url;
use App\Models\User;
use App\Models\Tables;
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
        /*BookingService        $bookingService,
        Booking               $booking*/
    )
    {
		$this->RestaurantService = $restaurantService;
       /// $this->clientService = $clientService;
       // $this->notification = $notification;
        //$this->booking = $booking;
        //$this->bookingService = $bookingService;
    }


    public function calculateMonthlyProfit()
    {
        $target = $this->CompanyIdentityDetails();
        $unique = array();
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        foreach ($months as $month) {
            $coll = InvoicePayments::getSummaryByMonth($month);
            if (!empty($target['daily_revenue_target']))
                $unique[] = $coll->sum('paid') / $target['monthly_revenue_target'] * 100;
            else $unique[] = 0;
        }

        return response($unique, 200);
    }

    public function getDailyProfit()
    {
        $target = $this->CompanyIdentityDetails();
        if (!empty($target['daily_revenue_target']))
            return InvoicePayments::getDailySummary(0)->sum('paid') / $target['daily_revenue_target'] * 100;
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

        
        //$data['topMedicalAids'] = BillingInvoice::topMedicalAids();
        //$data['myList'] = $myList;
        //$data['patients'] = Patient::getPatientInfo();
        //$data['targetRevenue'] = $CompanyIdentity['monthly_revenue_target'];
        //$data['totalPayment'] = InvoicePayments::getDailySummary(0)->sum('paid');
        //$data['cashPayment'] = InvoicePayments::getDailySummary(1)->sum('paid');
        //$data['cashEft'] = InvoicePayments::getDailySummary(2)->sum('paid');
        //$data['cashDebitCard'] = InvoicePayments::getDailySummary(3)->sum('paid');
		// $data['notifications'] = $this->notification::getAllUnreadNotifications();
		// $data['activePatients'] = Patient::totalPatients();
        //$data['bookingForMonth'] = Booking::getBookingForMonth();
		// $data['bookingForShowedUp'] = Booking::getBookingForShowedUp();
        //$data['bookingForNoShow'] = Booking::getBookingForNoShow();
		// $data['activeModules'] = modules::where('active', 1)->get();
	  // $tables = Tables::getTablesScans();
	   //return $tables;
		$data['dailyData'] = 23000;//$this->getDailyProfit();
		$data['activeModules'] = modules::where('active', 1)->get();
		$data['ordersServices'] = OrdersServices::getAllRequest();
		$data['orders'] = Orders::getOrders();
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
