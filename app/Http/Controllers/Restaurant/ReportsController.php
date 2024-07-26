<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\HRPerson;
use App\Models\EventsServices;
use App\Models\Orders;
use App\Models\OrdersProducts;
use App\Models\OrdersServices;
use App\Models\User;
use App\Models\Tables;
use App\Models\ServiceType;
use App\Models\TableScans;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use PhpParser\Node\Expr\Array_;
use Illuminate\Support\Facades\Validator;
class ReportsController extends Controller
{
    use BreadCrumpTrait, CompanyIdentityTrait;

//restaurant_avg_response_time_graph
    public function __construct()
    {


    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = $this->breadcrumb(
            'Restaurant',
            'Reports Page',
            '/restaurant/reports',
            'Restaurant Management',
            'Reports'
        );

		$employees = HRPerson::where('status',1)->get();
		$CompanyIdentity = $this->CompanyIdentityDetails(); 

		// get this year and month
		$year = date('Y');
		$month = date('m');
		
		$date = Carbon::now();
        $data['employees'] = $employees;
		$data['date'] = date("d/m/Y");
        return view('restaurant.reports.index')->with($data);
    }

	// waiter response reports
    public function waiterResponse(Request $request){
		
	$validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDate = !empty($dates[0]) ? $dates[0] : '';
        $endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDate = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';
		$users =  User::select('users.*', 'model_has_roles.*')
				->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
				->where('model_has_roles.role_id', 4)
				->get();
		$waiters = $users->load('person');
		//return $users;
		$resultData = [];
		$count = 0;
		$name = '';
		// Calculate response time
		foreach ($waiters as $waiter) {
			
			$avg = EventsServices::getRequestsGraphs($startDate, $endDate, $waiter->person->id);
			
			// Create an associative array representing a data point
			if (!empty($waiter->person->first_name))
			{
				$name = $waiter->person->first_name;
				if (!empty($name))
					//die('ddddddd');
					$formattedData = [
						'y' => $name, // Assuming 'initial' holds the label
						'a' => $avg // Assuming $avg holds the average response time
					];
				$name = $avg = '';
			}
			// Assign the formatted data to the $resultData array
			$resultData[] = $formattedData;
			//$waiter->person->initial = '';
			$count ++;
			//echo $waiter->person->first_name."</br>";
		}
		//echo $count;
		//die;

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = $startDate."-".$endDateDisplay;
        $data['user'] = Auth::user()->load('person');
        $data['dataArray'] = $resultData;
		
        return view('restaurant.graphs.waiter_response_time_graph')->with($data);
    }
	//

	public function getWaiterResponseTime($startDate, $endDate)
    {

        $users =  User::select('users.*', 'model_has_roles.*')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->where('model_has_roles.role_id', 4)
            ->get();
        $waiters = $users->load('person');

        //return $waiters;
        $resultData = array();

        // calculate response time
        foreach ($waiters as $waiter)
        {
            $formattedData = [];
            $avg = EventsServices::getRequestsGraphs($startDate, $endDate, $waiter->person->id);
            $formattedData[] = ['Waiter' => $waiter->person->initial, 'value' => $avg];
            $resultData[$waiter->person->id] = $formattedData;
        }

        return response($resultData, 200);
    }
	// waiter sales reports
	public function waiterSales(Request $request){

        $validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDate = !empty($dates[0]) ? $dates[0] : '';
        $endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDate = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';
		$users =  User::select('users.*', 'model_has_roles.*')
				->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
				->where('model_has_roles.role_id', 4)
				->get();
		$waiters = $users->load('person');
		$resultData = [];

		// Calculate response time
		foreach ($waiters as $waiter) {
			
			$amount = Orders::totalSalesPerWaiter($startDate, $endDate, $waiter->person->id);
			
			// Create an associative array representing a data point
			if (!empty($waiter->person->first_name))
				$formattedData = [
					'y' => $waiter->person->first_name, // Assuming 'initial' holds the label
					'a' => $amount // Assuming $avg holds the average response time
				];
			// Assign the formatted data to the $resultData array
			$resultData[] = $formattedData;
		}

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = $startDate."-".$endDateDisplay;
        $data['user'] = Auth::user()->load('person');
        $data['dataArray'] = $resultData;

        return view('restaurant.graphs.waiter_sale_value_graph')->with($data);
    }
	// Popular Quick services
	public function popularServices(Request $request){

        $validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDate = !empty($dates[0]) ? $dates[0] : '';
        $endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDate = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';
		
		$services = ServiceType::where('status',1)->get();

		$resultData = [];

		// Calculate response time
		foreach ($services as $service) {
			
			$numberRequest = OrdersServices::mostPopularServices($startDate, $endDate, $service->id);
			
			// Create an associative array representing a data point
			if (!empty($service->name))
				$formattedData = [
					'y' => $service->name, // Assuming 'initial' holds the label
					'a' => $numberRequest // Assuming $avg holds the average response time
				];
			// Assign the formatted data to the $resultData array
			$resultData[] = $formattedData;
		}

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = $startDate."-".$endDateDisplay;
        $data['user'] = Auth::user()->load('person');
        $data['dataArray'] = $resultData;

        return view('restaurant.graphs.popular_quick_services_graph')->with($data);
    }
	// turn around time table size
	public function turnaroundTableSize(Request $request){

        $validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDate = !empty($dates[0]) ? $dates[0] : '';
        $endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDate = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';
		
		$tables = Tables::where('status',1)->orderBy('number_customer')->get();

		$resultData = [];

		// Calculate response time
		foreach ($tables as $table) {
			
			$avg = EventsServices::getRequestsPerTableGraphs($startDate, $endDate, $table->id);
			
			// Create an associative array representing a data point
			if (!empty($table->number_customer))
				$formattedData = [
					'y' => $table->number_customer, // Assuming 'initial' holds the label
					'a' => $avg // Assuming $avg holds the average response time
				];
			// Assign the formatted data to the $resultData array
			$resultData[] = $formattedData;
		}

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = $startDate."-".$endDateDisplay;
        $data['user'] = Auth::user()->load('person');
        $data['dataArray'] = $resultData;

        return view('restaurant.graphs.turn_around_table_size_graph')->with($data);
    }
	// waiter sales reports
	public function reviewsReports(Request $request){

        $validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDate = !empty($dates[0]) ?$dates[0]  : '' ;
		$endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDate = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';
		$scans = TableScans::getReports($startDate, $endDate);

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = date("d/m/Y");
        $data['user'] = Auth::user()->load('person');
        $data['scans'] = $scans;

        return view('restaurant.graphs.reviews_graph')->with($data);
    }
	public function popularDishes(Request $request){

        $validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDate = !empty($dates[0]) ? $dates[0] : '';
        $endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDate = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';
		
		$dishes = OrdersProducts::popularDishes($startDate, $endDate);

        $data['dishes'] = $dishes;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = $startDate."-".$endDateDisplay;
        $data['user'] = Auth::user()->load('person');

        return view('restaurant.reports.popular_dishes')->with($data);
    }
	
	// waiter response reports
    public function restaurantTurnaroundTime(Request $request)
	{

		$validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDateS = !empty($dates[0]) ? $dates[0] : '';
		$endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDateS = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';
		//
		// Convert the start and end dates to Carbon objects
		$startDate = new Carbon($startDateS);
		$endDate = new Carbon($endDateS);
		$resultData = [];
		// Loop through the dates from $startDate to $endDate, incrementing one day at a time
		$currentDate = clone $startDate; // Clone the start date to avoid modifying it directly
		while ($currentDate->lte($endDate)) {
			// Process the current date (e.g., output it, perform some action)
			///echo $currentDate->toDateString() . "<br>";
			$todayDate = $currentDate->toDateString();
			$avg = EventsServices::getRestaurantGraphs($todayDate);
			
			// Create an associative array representing a data point
			$formattedData = [
				'y' => $todayDate, // Assuming 'initial' holds the label
				'a' => $avg // Assuming $avg holds the average response time
			];
			// Assign the formatted data to the $resultData array
			$resultData[] = $formattedData;
			// Move to the next day
			$currentDate->addDay();
		}
		
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = $startDateS."-".$endDateDisplay;
        $data['user'] = Auth::user()->load('person');
        $data['dataArray'] = $resultData;
		
        return view('restaurant.graphs.restaurant_avg_response_time_graph')->with($data);
    }	
	// restaurant sales reports
	public function restaurantSalesVolume(Request $request){

        $validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDateS = !empty($dates[0]) ? $dates[0] : '';
		$endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDateS = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';

		//
		// Convert the start and end dates to Carbon objects
		$startDate = new Carbon($startDateS);
		$endDate = new Carbon($endDateS);
		$resultData = [];
		// Loop through the dates from $startDate to $endDate, incrementing one day at a time
		$currentDate = clone $startDate; // Clone the start date to avoid modifying it directly
		while ($currentDate->lte($endDate)) {
			// Process the current date (e.g., output it, perform some action)
			//echo $currentDate->toDateString() . "<br>";
			$todayDate = $currentDate->toDateString();
			
			$amount = Orders::totalSalesRestaurant($todayDate);
			// Create an associative array representing a data point
			$formattedData = [
				'y' => $todayDate, // Assuming 'initial' holds the label
				'a' => $amount // Assuming $avg holds the average response time
			];
			// Assign the formatted data to the $resultData array
			$resultData[] = $formattedData;
			// Move to the next day
			$currentDate->addDay();
		}

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = $startDateS."-".$endDateDisplay;
        $data['user'] = Auth::user()->load('person');
        $data['dataArray'] = $resultData;

        return view('restaurant.graphs.restaurant_sales_volune_time_graph')->with($data);
    }
	// 
	// app usage reports
	public function appUsage(Request $request){

        $validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDateS = !empty($dates[0]) ? $dates[0] : '';
		$endDateDisplay = !empty($dates[1]) ? $dates[1] : '';
        $endDateS = !empty($dates[1]) ? $dates[1] . ' 23:59:00' : '';

		//
		// Convert the start and end dates to Carbon objects
		$startDate = new Carbon($startDateS);
		$endDate = new Carbon($endDateS);
		$resultData = [];
		// Loop through the dates from $startDate to $endDate, incrementing one day at a time
		$currentDate = clone $startDate; // Clone the start date to avoid modifying it directly
		while ($currentDate->lte($endDate)) {
			// Process the current date (e.g., output it, perform some action)
			//echo $currentDate->toDateString() . "<br>";
			$todayDate = $currentDate->toDateString();
			
			$number = TableScans::getUsageReports($todayDate);
			// Create an associative array representing a data point
			$formattedData = [
				'y' => $todayDate, // Assuming 'initial' holds the label
				'a' => $number // Assuming $avg holds the average response time
			];
			// Assign the formatted data to the $resultData array
			$resultData[] = $formattedData;
			// Move to the next day
			$currentDate->addDay();
		}

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDateDisplay;
		$data['date'] = $startDateS."-".$endDateDisplay;
        $data['user'] = Auth::user()->load('person');
        $data['dataArray'] = $resultData;

        return view('restaurant.graphs.restaurant_app_usage_graph')->with($data);
    }
	// reviews star reports
	public function appStarRating(Request $request){

        $validator = Validator::make($request->all(), [
            'date_range' => 'required',
        ]);
		
        $validator->after(function ($validator) use ($request) {
            // get date 
            $dates = explode("to", $request['date_range']);
			$startDate = !empty($dates[0]) ? $dates[0] : '';
			$endDate = !empty($dates[1]) ? $dates[1] : '';
			if (empty($startDate) || empty($endDate))
				$validator->errors()->add('date_range', "Please make sure both start and end are selected.");
        });
        if ($validator->fails()) {
            return redirect("/restaurant/reports")
                ->withErrors($validator)
                ->withInput();
        }

		$dates = explode("to", $request['date_range']);
        $startDate = !empty($dates[0]) ? $dates[0] : '';
        $endDate = !empty($dates[1]) ? $dates[1] : '';

		// Convert the start and end dates to Carbon objects
		$i = 1;

		while ($i <= 5) 
		{
			//echo $i."</br>";
			// Process the current date (e.g., output it, perform some action)
			//echo $currentDate->toDateString() . "<br>";
			
			$ambience = TableScans::getRatingsQoneReports($i,$startDate, $endDate);
			$food = TableScans::getRatingsQtwoReports($i,$startDate, $endDate);
			$service = TableScans::getRatingsQthreeReports($i,$startDate, $endDate);
			$app = TableScans::getRatingsQfourReports($i,$startDate, $endDate);
			// Create an associative array representing a data point
			$formattedData = [
				'y' => $i, // Assuming 'initial' holds the label
				'a' => $ambience,
				'b' => $food, // Assuming $avg holds the average response time
				'c' => $service, // Assuming $avg holds the average response time
				'd' => $app				// Assuming $avg holds the average response time
			];
			// Assign the formatted data to the $resultData array
			$resultData[] = $formattedData;
			$i ++;
		}
		//[{"y":1,"a":12,"b":23,"c":55,"d":43},{"y":2,"a":55,"b":44,"c":33,"d":65},{"y":3,"a":34,"b":54,"c":66,"d":66},{"y":4,"a":67,"b":76,"c":54,"d":56},{"y":5,"a":66,"b":55,"c":76,"d":66}]
		$resultData = "";
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
		$data['date'] = $startDate."-".$endDate;
        $data['user'] = Auth::user()->load('person');
        $data['dataArray'] = $resultData;

        return view('restaurant.graphs.restaurant_app_usage_graph')->with($data);
	}
	// calculate response time
    public function responseTime($startDate, $endDate){


		$start = Carbon::parse($startDate);
		$end = Carbon::parse($endDate);

		$diffInDays = $end->diffInDays($start);
		$diffInHours = $end->diffInHours($start);
		$diffInMinutes = $end->diffInMinutes($start);
		$diffInSeconds = $end->diffInSeconds($start);

		// You can also get the difference in a human-readable format
		$humanReadableDiff = $end->diffForHumans($start);

		// Output the differences
		//echo "Difference in days: $diffInDays\n";
		//echo "Difference in hours: $diffInHours\n";
		//echo "Difference in minutes: $diffInMinutes\n";
		//echo "Difference in seconds: $diffInSeconds\n";
		//echo "Human readable difference: $humanReadableDiff\n";

		return $humanReadableDiff;
    }

	//
	public function getDailyProfit()
    {
        $target = $this->CompanyIdentityDetails();
        if (!empty($target['daily_revenue_target']))
            return OrdersProducts::getDailySummary()->sum('amount') / $target['daily_revenue_target'] * 100;
        else return 0;
    }
	//financials
	public function financials()
    {
        $data = $this->breadcrumb(
            'Restaurant',
            'Reports Page',
            '/restaurant/reports',
            'Restaurant Management',
            'Reports'
        );
		$CompanyIdentity = $this->CompanyIdentityDetails(); 

		// get this year and month
		$year = date('Y');
		$month = date('m');

		$date = Carbon::now();
		$data['date'] = date("d/m/Y");
		$data['totalOrders'] = OrdersProducts::totalPaidThisYear($year);
        $data['monthlyOrders'] = OrdersProducts::totalPaidThisMonth($year,$month);
        $data['totalIncompleteOrders'] = OrdersProducts::totalUnpaidThisYear($year);
        $data['monthlyIncompleteOrders'] = OrdersProducts::totalUnpaidThisMonth($year,$month);
		$data['targetRevenue'] = $CompanyIdentity['monthly_revenue_target'];
        $data['totalPayment'] = OrdersProducts::getDailySummary()->sum('amount');
		$data['dailyData'] = $this->getDailyProfit();
        return view('restaurant.graphs.financials')->with($data);
    }
}
