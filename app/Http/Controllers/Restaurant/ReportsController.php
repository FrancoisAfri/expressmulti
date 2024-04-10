<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\HRPerson;
use App\Models\EventsServices;
use App\Models\Orders;
use App\Models\OrdersProducts;
use App\Models\User;
use App\Models\TableScans;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use PhpParser\Node\Expr\Array_;

class ReportsController extends Controller
{
    use BreadCrumpTrait, CompanyIdentityTrait;


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
		//return $employees;

/*
        // age analysis report
        $accountPayments = Accounts::getAccounts();
        foreach ($accountPayments as $acount) {
            //calculations
            $acount->total_balance = $this->ageAnalysisTotalAccount($acount->id);
            $acount->total_medical_aids_balance = $this->ageAnalysisTotalMedical($acount->id);
            $acount->total_patient_balance = $this->ageAnalysisTotalPatient($acount->id);
            $acount->current_balance = $this->ageAnalysisdate($acount->id, 1);
            $acount->thirty_balance = $this->ageAnalysisdate($acount->id, 2);
            $acount->sixthy_balance = $this->ageAnalysisdate($acount->id, 3);
            $acount->ninthy_balance = $this->ageAnalysisdate($acount->id, 4);
            $acount->one_twenty_balance = $this->ageAnalysisdate($acount->id, 5);
            $acount->one_fithy_balance = $this->ageAnalysisdate($acount->id, 6);
            $acount->last_payment_date = $this->accountLastPaymentDate($acount->id);
        }

        // patient age analysis report
        $accountPayments = Accounts::getAccounts();
        foreach ($accountPayments as $acount) {
            //calculations
            $acount->total_balance = $this->ageAnalysisTotalAccount($acount->id);
            $acount->total_medical_aids_balance = $this->ageAnalysisTotalMedical($acount->id);
            $acount->total_patient_balance = $this->ageAnalysisTotalPatient($acount->id);
            $acount->current_balance = $this->ageAnalysisdate($acount->id, 1);
            $acount->thirty_balance = $this->ageAnalysisdate($acount->id, 2);
            $acount->sixthy_balance = $this->ageAnalysisdate($acount->id, 3);
            $acount->ninthy_balance = $this->ageAnalysisdate($acount->id, 4);
            $acount->one_twenty_balance = $this->ageAnalysisdate($acount->id, 5);
            $acount->one_fithy_balance = $this->ageAnalysisdate($acount->id, 6);
            $acount->last_payment_date = $this->accountLastPaymentDate($acount->id);

        }

        $date = Carbon::now()->subDays(30);
        $paymentCodes = array(1 => 'cash', 2 => 'EFT', 3 => 'Debit Card', 4 => 'Medical Scheme');
        $data = $this->creditData();

		$medic =  BillingInvoice::dataMedic();


        $data['thirty_balance'] = $thirty_balance = BillingInvoice::medicAnalysis(1);
        $data['patientPayments'] = $this->accountPayments();
        $data['medic'] = $medic;
        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
        $data['name'] = $this->CompanyIdentityDetails();
        $data['accountPayments'] = $this->accountPayments();
        $data['detailedTransactions'] = BillingProcedures::getDetailedInfo();
        $data['dailySummary'] = InvoicePayments::getDailySummary(0)->sum('paid');
        $data['cash'] = InvoicePayments::getDailySummaryForType(1)->sum('paid');
        $data['eft'] = InvoicePayments::getDailySummaryForType(2)->sum('paid');
        $data['debitCard'] = InvoicePayments::getDailySummaryForType(3)->sum('paid');
        $data['medicalScheme'] = InvoicePayments::getDailySummaryForType(4)->sum('paid');
        $data['user'] = Auth::user()->load('person');
        $data['date'] = $date;
*/
		$dishes = OrdersProducts::popularDishes();
		//return $dishes;
        $data['dishes'] = $dishes;
		$date = Carbon::now();
        $data['employees'] = $employees;
		$data['date'] = date("d/m/Y");
        return view('restaurant.reports.index')->with($data);
    }

	// waiter response reports
    public function waiterResponse(Request $request){

        $this->validate($request, [
            //'employee_id' => 'required',
            'date_range' => 'required',
        ]);

		//$employee_id = !empty($request['employee_id']) ? $request['employee_id'] : 0;
        $dates = explode("to", $request['date_range']);
        $startDate = $dates[0];
        $endDate = $dates[1];
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
			$formattedData[] = ['year' => $waiter->person->initial, 'value' => $avg];
			$resultData[$waiter->person->id] = $formattedData;
        }
		//print_r($resultData);
		//die('lll');
		/*$resultData = array();

		// calculate response time
		foreach ($waiters as $waiter) {
			$services = EventsServices::getRequestsGraphs($startDate, $endDate, $waiter->person->id);

			// Construct an array with year and value for each entry in the response time data
			$formattedData = [];
			foreach ($services as $service) {
				$formattedData[] = ['year' => $service->year, 'value' => $service->value];
			}

			// Append the formatted data to the $resultData array using the waiter's ID as the key
			$resultData[$waiter->person->id] = $formattedData;
		}
		*/
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
		$data['date'] = $startDate."-".$endDate;
        $data['user'] = Auth::user()->load('person');
		$data['resquest_type'] = EventsServices::SERVICES_SELECT;
        //$data['totalPatients'] = EventsServices::getTotalPatients($startDate, $endDate)->count();
        //$data['services'] = $services;
        $data['resultData'] = $resultData;

        return view('restaurant.graphs.waiter_response_time_graph')->with($data);
    }
	//
	/*
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
    }*/
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

        $this->validate($request, [
            //'employee_id' => 'required',
            'date_range' => 'required',
        ]);

		$employee_id = !empty($request['employee_id']) ? $request['employee_id'] : 0;
        $dates = explode("to", $request['date_range']);
        $startDate = $dates[0];
        $endDate = $dates[1];
		$orders = Orders::getOrdersReports($startDate, $endDate, $employee_id);
		// calculate response time
		$totals = $amount = 0;
		foreach ($orders as $order)
		{
			$amount = OrdersProducts::totalAmountOrder($order->id);
			$order->total_amount = $amount;
			$totals = $totals + $amount;
			$amount = 0;
			$order->totals = $totals;
        }

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
		$data['date'] = date("d/m/Y");
        $data['user'] = Auth::user()->load('person');
        $data['orders'] = $orders;

        return view('restaurant.reports.waiter_sales')->with($data);
    }
	// waiter sales reports
	public function reviewsReports(Request $request){

        $this->validate($request, [
            'date_range' => 'required',
        ]);

		$dates = explode("to", $request['date_range']);
        $startDate = $dates[0];
        $endDate = $dates[1];
		$scans = TableScans::getReports($startDate, $endDate);

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
		$data['date'] = date("d/m/Y");
        $data['user'] = Auth::user()->load('person');
        $data['scans'] = $scans;

        return view('restaurant.graphs.reviews_graph')->with($data);
    }
	public function popularDishes(){

        $this->validate($request, [
            'date_range' => 'required',
        ]);

		$dates = explode("to", $request['date_range']);
        $startDate = $dates[0];
        $endDate = $dates[1];
		$scans = TableScans::getReports($startDate, $endDate);

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
		$data['date'] = date("d/m/Y");
        $data['user'] = Auth::user()->load('person');
        $data['scans'] = $scans;

        return view('restaurant.graphs.popular_dishes_graph')->with($data);
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

	public function medicalAnalysis(){
        $thirty_balance = BillingInvoice::medicAnalysis(1);
    }

    public function accountPayments()
    {
        $accountPayments = Accounts::getAccounts();


        foreach ($accountPayments as $acount) {
            //calculations
            $acount->total_balance = $this->ageAnalysisTotalAccount($acount->id);
            $acount->total_medical_aids_balance = $this->ageAnalysisTotalMedical($acount->id);
            $acount->total_patient_balance = $this->ageAnalysisTotalPatient($acount->id);
            $acount->total_isuarance = $this->ageAnalysisInsuarance($acount->id);
            $acount->current_balance = $this->ageAnalysisdate($acount->id, 1);
            $acount->thirty_balance = $this->ageAnalysisdate($acount->id, 2);
            $acount->sixthy_balance = $this->ageAnalysisdate($acount->id, 3);
            $acount->ninthy_balance = $this->ageAnalysisdate($acount->id, 4);
            $acount->one_twenty_balance = $this->ageAnalysisdate($acount->id, 5);
            $acount->one_fithy_balance = $this->ageAnalysisdate($acount->id, 6);
            $acount->last_payment_date = $this->accountLastPaymentDate($acount->id);

            //Patient Age analysis
            $acount->patient_current_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->id, 1);
            $acount->patient_thirty_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->id, 2);
            $acount->patient_sixthy_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->id, 3);
            $acount->patient_ninthy_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->id, 4);
            $acount->patient_one_twenty_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->id, 5);
            $acount->patient_one_fithy_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->id, 6);
            $acount->patient_one_eighty_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->id, 7);
            $acount->patient_two_hundred_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->id, 8);

            //summary figures
            $acount->total_balance_age = $this->ageAnalysisTotalBalanceAccount();
            $acount->total_balance_medical = $this->balanceAgeAnalysisTotalMedical();
            $acount->balanceAgeAnalysisTotalPatient = $this->balanceAgeAnalysisTotalPatient();
            $acount->totalAgeAnalysisInsuarance = $this->totalAgeAnalysisInsuarance();

            $acount->current_balanceTotal = $this->ageAnalysisDateTotals(1);
            $acount->thirty_balanceTotal = $this->ageAnalysisDateTotals(2);
            $acount->sixthy_balance = $this->ageAnalysisDateTotals(3);
            $acount->ninthy_balance = $this->ageAnalysisDateTotals(4);
            $acount->one_twenty_balance = $this->ageAnalysisDateTotals(5);
            $acount->one_fithy_balance = $this->ageAnalysisDateTotals(6);
            $acount->ageAnalysisTotalBalanceAccount = $this->ageAnalysisTotalBalanceAccount();

            $acount->credit_medical = BillingInvoice::SchemeageAnalysisdate(1);
            $acount->thirty_medical = BillingInvoice::SchemeageAnalysisdate(2);
            $acount->sixthy_medical = BillingInvoice::SchemeageAnalysisdate(3);
            $acount->ninthy_medical = BillingInvoice::SchemeageAnalysisdate(4);
            $acount->one_twenty_medical = BillingInvoice::SchemeageAnalysisdate(5);
            $acount->one_fithyy_medical = BillingInvoice::SchemeageAnalysisdate(6);

            $acount->credit_insurance = BillingInvoice::ageAnalysisInsuarance(1);
            $acount->thirty_insuarance = BillingInvoice::ageAnalysisInsuarance(2);
            $acount->sixthy_indurance = BillingInvoice::ageAnalysisInsuarance(3);
            $acount->ninthy_indurance = BillingInvoice::ageAnalysisInsuarance(4);
            $acount->one_twenty_indurance = BillingInvoice::ageAnalysisInsuarance(5);
            $acount->one_fithyy_indurance = BillingInvoice::ageAnalysisInsuarance(6);

            $acount->credit_patience = BillingInvoice::ageAnalysisTotalPatient(1);
            $acount->thirty_patience = BillingInvoice::ageAnalysisTotalPatient(2);
            $acount->sixthy_patience = BillingInvoice::ageAnalysisTotalPatient(3);
            $acount->ninthy_patience = BillingInvoice::ageAnalysisTotalPatient(4);
            $acount->one_twenty_patience = BillingInvoice::ageAnalysisTotalPatient(5);
            $acount->one_fithyy_patience = BillingInvoice::ageAnalysisTotalPatient(6);

            // current factor
            $acount->current_factor = (!empty ($acount->current_balanceTotal) && !empty($acount->total_balance_age)) ? $acount->current_balanceTotal / $acount->total_balance_age * 100 : 0 ;
            $acount->thirty_day_factor = (!empty ($acount->thirty_balanceTotal) && !empty($acount->total_balance_age)) ? $acount->thirty_balanceTotal / $acount->total_balance_age * 100 : 0 ;
            $acount->sixthy_day_factor = (!empty ($acount->sixthy_balance) && !empty($acount->total_balance_age)) ? $acount->sixthy_balance / $acount->total_balance_age * 100 : 0 ;
            $acount->ninthy_day_factor = (!empty ($acount->ninthy_balance) && !empty($acount->total_balance_age)) ? $acount->ninthy_balance / $acount->total_balance_age * 100 : 0 ;
            $acount->one_twenty_day_factor = (!empty ($acount->one_twenty_balance) && !empty($acount->total_balance_age)) ? $acount->one_twenty_balance / $acount->total_balance_age * 100 : 0 ;
            $acount->one_fithy_day_factor = (!empty ($acount->one_fithy_balance) && !empty($acount->total_balance_age)) ? $acount->one_fithy_balance / $acount->total_balance_age * 100 : 0 ;

            // medical  factor
            $acount->current_scheme_factor = (!empty ($acount->credit_medical) && !empty($acount->total_balance_age)) ? $acount->credit_medical / $acount->total_balance_age * 100 : 0 ;
            $acount->thirty_scheme__day_factor = (!empty ($acount->thirty_medical) && !empty($acount->total_balance_age)) ? $acount->thirty_medical / $acount->total_balance_age * 100 : 0 ;
            $acount->sixthy_scheme__day_factor = (!empty ($acount->sixthy_medical) && !empty($acount->total_balance_age)) ? $acount->sixthy_medical / $acount->total_balance_age * 100 : 0 ;
            $acount->ninthy_scheme__day_factor = (!empty ($acount->ninthy_medical) && !empty($acount->total_balance_age)) ? $acount->ninthy_medical / $acount->total_balance_age * 100 : 0 ;
            $acount->one_twenty_scheme__day_factor = (!empty ($acount->one_twenty_medical) && !empty($acount->total_balance_age)) ? $acount->one_twenty_medical / $acount->total_balance_age * 100 : 0 ;
            $acount->one_fithy_scheme__day_factor = (!empty ($acount->one_fithy_medical) && !empty($acount->total_balance_age)) ? $acount->one_fithy_medical / $acount->total_balance_age * 100 : 0 ;

            //Patient factor
            $acount->current_patient_factor = (!empty ($acount->credit_patience) && !empty($acount->total_balance_age)) ? $acount->credit_patience / $acount->total_balance_age * 100 : 0 ;
            $acount->thirty_patient__day_factor = (!empty ($acount->thirty_patience) && !empty($acount->total_balance_age)) ? $acount->thirty_patience / $acount->total_balance_age * 100 : 0 ;
            $acount->sixthy_patient__day_factor = (!empty ($acount->sixthy_patience) && !empty($acount->total_balance_age)) ? $acount->sixthy_patience / $acount->total_balance_age * 100 : 0 ;
            $acount->ninthy_patient__day_factor = (!empty ($acount->ninthy_patience) && !empty($acount->total_balance_age)) ? $acount->ninthy_patience / $acount->total_balance_age * 100 : 0 ;
            $acount->one_twenty_patient__day_factor = (!empty ($acount->one_twenty_patience) && !empty($acount->total_balance_age)) ? $acount->one_twenty_patience / $acount->total_balance_age * 100 : 0 ;
            $acount->one_fithy_patient__day_factor = (!empty ($acount->one_fithy_patience) && !empty($acount->total_balance_age)) ? $acount->one_fithy_patience / $acount->total_balance_age * 100 : 0 ;

            //insurance factor
            $acount->current_insurance_factor = (!empty ($acount->credit_insurance) && !empty($acount->total_balance_age)) ? $acount->credit_insurance / $acount->total_balance_age * 100 : 0 ;
            $acount->thirty_insurance__day_factor = (!empty ($acount->thirty_insurance) && !empty($acount->total_balance_age)) ? $acount->thirty_insurance / $acount->total_balance_age * 100 : 0 ;
            $acount->sixthy_insurance__day_factor = (!empty ($acount->sixthy_insurance) && !empty($acount->total_balance_age)) ? $acount->sixthy_insurance / $acount->total_balance_age * 100 : 0 ;
            $acount->ninthy_insurance__day_factor = (!empty ($acount->ninthy_insurance) && !empty($acount->total_balance_age)) ? $acount->ninthy_insurance / $acount->total_balance_age * 100 : 0 ;
            $acount->one_twenty_insurance__day_factor = (!empty ($acount->one_twenty_insurance) && !empty($acount->total_balance_age)) ? $acount->one_twenty_insurance / $acount->total_balance_age * 100 : 0 ;
            $acount->one_fithy_insurance__day_factor = (!empty ($acount->one_fithy_insurance) && !empty($acount->total_balance_age)) ? $acount->one_fithy_insurance / $acount->total_balance_age * 100 : 0 ;

            //
            $acount->patient_factor = (!empty ($acount->balanceAgeAnalysisTotalPatient) && !empty($acount->total_balance_age)) ? $acount->balanceAgeAnalysisTotalPatient / $acount->total_balance_age * 100 : 0 ;

            $acount->medical_factor = (!empty ($acount->totalAgeAnalysisInsuarance) && !empty($acount->total_balance_age)) ? $acount->totalAgeAnalysisInsuarance / $acount->total_balance_age * 100 : 0 ;

            $acount->insuarance_factor = (!empty ($acount->totalAgeAnalysisInsuarance) && !empty($acount->total_balance_age)) ? $acount->totalAgeAnalysisInsuarance / $acount->total_balance_age * 100 : 0 ;

        }

        return $accountPayments;
    }

    public function creditAnalysis()
    {
        $data = $this->creditData();
        return view('billing.Invoices.reports.print_credit_age_analysis')->with($data);
    }

    public function transactionDetailed(){
        $data = $this->creditData();
        return view('billing.Invoices.reports.print_transaction_detailed')->with($data);
    }

    private function creditData()
    {
        $data['dailySummary'] = InvoicePayments::getCreditSummary(0)->sum('paid');
        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
        $data['name'] = $this->CompanyIdentityDetails();
        $data['date'] = Carbon::now()->toDateString();
        $data['creditPayments'] = InvoicePayments::getCreditSummary();
        $data['detailedTransactions'] = BillingProcedures::getDetailedInfo();
        $data['user'] = Auth::user()->load('person');

        return $data;
    }

    public function generateAgeAnalysis()
    {
        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
        $data['name'] = $this->CompanyIdentityDetails();
        $data['date'] = Carbon::now()->subDays(30);
        $data['accountPayments'] = $this->accountPayments();
        $data['detailedTransactions'] = BillingProcedures::getDetailedInfo();
        $data['user'] = Auth::user()->load('person');


        return view('billing.Invoices.reports.print_age_analysis')->with($data);
    }

    public function generatePatientAgeAnalysis()
    {
//        $query = InvoicePayments::getAccouintsInfo();

        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
        $data['name'] = $this->CompanyIdentityDetails();
        $data['date'] = Carbon::now()->subDays(30);
        $data['accountPayments'] = $this->accountPayments()->filter();
        $data['detailedTransactions'] = BillingProcedures::getDetailedInfo();
        $data['user'] = Auth::user()->load('person');

        return view('billing.Invoices.reports.print_patient_age_analysis')->with($data);
    }


    public function patientDataAnalysis(){
        $query = InvoicePayments::getAccouintsInfo();
        foreach ($query as $acount){
            $acount->patient_current_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->accounts_id, 1);

            $acount->patient_thirty_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->accounts_id, 2);
            $acount->patient_sixthy_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->accounts_id, 3);
            $acount->patient_ninthy_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->accounts_id, 4);
            $acount->patient_one_twenty_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->accounts_id, 5);
            $acount->patient_one_fithy_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->accounts_id, 6);
            $acount->patient_one_eighty_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->accounts_id, 7);
            $acount->patient_two_hundred_analysis = BillingInvoice::PatientAccountAgeAnalysisTotal($acount->accounts_id, 8);
        }

      return $query;
    }

    public function transactionSummary(Request $request)
    {
        $this->validate($request, [
            'date_range' => 'required',
        ]);
        $dates = explode("to", $request['date_range']);
        $startDate = $dates[0];
        $endDate = $dates[1];


        $today = Carbon::now()->toDayDateTimeString();
        $data['date'] = $today;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
        $data['name'] = $this->CompanyIdentityDetails();
        $paymentCodes = array(1 => 'cash', 2 => 'EFT', 3 => 'Debit Card', 4 => 'Medical Scheme');
        $data['cash'] = InvoicePayments::getPaymentSummary($startDate, $endDate, 1)->sum('paid');
        $data['eft'] = InvoicePayments::getPaymentSummary($startDate, $endDate, 2)->sum('paid');
        $data['Debit_Card'] = InvoicePayments::getPaymentSummary($startDate, $endDate, 3)->sum('paid');
        $data['medical_scheme'] = InvoicePayments::getPaymentSummary($startDate, $endDate, 4)->sum('paid');
        $data['daily'] = InvoicePayments::getPaymentSummary($today, $today, 4)->sum('paid');

        return view('billing.Invoices.reports.account _transaction_summary')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ageAnalysisdate($accountID, $type)
    {
        // get date range
        $from_date = $to_date = '';
        if ($type == 1) {
            $from_date = Carbon::now()->subDays(30);
            $to_date = Carbon::now();
        } elseif ($type == 2) {
            $from_date = Carbon::now()->subDays(60);
            $to_date = Carbon::now()->subDays(30);
        } elseif ($type == 3) {
            $from_date = Carbon::now()->subDays(90);
            $to_date = Carbon::now()->subDays(60);
        } elseif ($type == 4) {
            $from_date = Carbon::now()->subDays(120);
            $to_date = Carbon::now()->subDays(90);
        } elseif ($type == 5) {
            $from_date = Carbon::now()->subDays(150);
            $to_date = Carbon::now()->subDays(120);
        } elseif ($type == 6) {
            $from_date = Carbon::now()->subDays(200);
            $to_date = Carbon::now()->subDays(150);
        }
        // query data
        return $result = BillingInvoice::where('accounts_id', $accountID)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('invoice_balance_amount');
    }

    public function ageAnalysisDateTotals($type)
    {
        // get date range
        $from_date = $to_date = '';
        if ($type == 1) {
            $from_date = Carbon::now()->subDays(30);
            $to_date = Carbon::now();
        } elseif ($type == 2) {
            $from_date = Carbon::now()->subDays(60);
            $to_date = Carbon::now()->subDays(30);
        } elseif ($type == 3) {
            $from_date = Carbon::now()->subDays(90);
            $to_date = Carbon::now()->subDays(60);
        } elseif ($type == 4) {
            $from_date = Carbon::now()->subDays(120);
            $to_date = Carbon::now()->subDays(90);
        } elseif ($type == 5) {
            $from_date = Carbon::now()->subDays(150);
            $to_date = Carbon::now()->subDays(120);
        } elseif ($type == 6) {
            $from_date = Carbon::now()->subDays(200);
            $to_date = Carbon::now()->subDays(150);
        }
        // query data
        return $result = BillingInvoice::whereBetween('created_at', [$from_date, $to_date])
            ->sum('invoice_balance_amount');
    }

    // age analysis total balance per account
    public function ageAnalysisTotalAccount($accountID)
    {
        return $result = BillingInvoice::where('accounts_id', $accountID)
            ->sum('invoice_balance_amount');
    }

    /*
     * age Analysis Total Balance Account
     */
    public function ageAnalysisTotalBalanceAccount()
    {
        return $result = BillingInvoice::sum('invoice_balance_amount');

    }

    /**
     * @param $accountID
     * @return mixed
     */
    public function ageAnalysisInsuarance($accountID)
    {
        return $result = BillingInvoice::where('accounts_id', $accountID)
            ->where('invoice_type', 3)
            ->sum('invoice_balance_amount');
    }

    public function totalAgeAnalysisInsuarance()
    {
        return $result = BillingInvoice::where('invoice_type', 3)
            ->sum('invoice_balance_amount');
    }

    // age analysis total balance per account
    public function ageAnalysisTotalMedical($accountID)
    {
        return $result = BillingInvoice::where('accounts_id', $accountID)
            ->where('invoice_type', 1)
            ->sum('invoice_balance_amount');
    }

    public function balanceAgeAnalysisTotalMedical()
    {
        return $result = BillingInvoice::where('invoice_type', 1)
            ->sum('invoice_balance_amount');
    }

    // age analysis total balance per account
    public function ageAnalysisTotalPatient($accountID)
    {
        return $result = BillingInvoice::where('accounts_id', $accountID)
            ->where('invoice_type', 2)
            ->sum('invoice_balance_amount');
    }

    public function balanceAgeAnalysisTotalPatient()
    {
        return $result = BillingInvoice::where('invoice_type', 2)
            ->sum('invoice_balance_amount');
    }

    // age analysis total balance per account
    public function accountLastPaymentDate($accountID)
    {
        $result = InvoicePayments::where('accounts_id', '=', $accountID)
            ->where('paid', '!=', 0)
            ->first();

        if (!empty($result['created_at']))
            return $result->date;
        else return '';
    }
	public function downloadreports($id): \Illuminate\Http\Response
    {
        $data = $this->InvoiceDetails($id);

        PDF::setOptions(['isRemoteEnabled' => TRUE, 'enable_javascript' => TRUE]);
        $pdf = PDF::loadView('billing.Invoices.InvoicePrint.cust_invoice', $data)->setPaper(array(0, 0, 609.4488, 935.433), 'landscape');
        return $pdf->download('Billing_invoice_' . $id . '.pdf');
    }
}
