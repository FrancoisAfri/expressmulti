<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\BillingInvoice;
use App\Models\BillingProcedures;
use App\Models\InvoiceCompanyProfile;
use App\Models\InvoicePayments;
use App\Models\MedicalAid;
use App\Models\MedicalAids;
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

    private InvoiceController $invoiceController;

    public function __construct(InvoiceController $invoiceController)
    {
        $this->invoiceController = $invoiceController;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'bills',
            'Client Billing',
            'Reports'
        );

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
//        dd(MedicalAid::with('patient')->get());
//        dd($medic);

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
        $data['creditAnalysis'] = InvoicePayments::getDailySummary(0);

        return view('billing.Invoices.reports.index')->with($data);
    }


    public function printDailyAudit(Request $request){

        $this->validate($request, [
            'date_range' => 'required',
        ]);

        $dates = explode("to", $request['date_range']);
        $startDate = $dates[0];
        $endDate = $dates[1];

//        dd(InvoicePayments::getTotalPatients($startDate, $endDate)->count());


        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
        $data['name'] = $this->CompanyIdentityDetails();
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['user'] = Auth::user()->load('person');
        $data['totalPatients'] = InvoicePayments::getTotalPatients($startDate, $endDate)->count();
        $data['totalTransactions'] =InvoicePayments::getTotalTransactions( $startDate, $endDate);
        $data['cash'] = InvoicePayments::getPaymentSummaryForType(1, $startDate, $endDate)->sum('paid');
        $data['eft'] = InvoicePayments::getPaymentSummaryForType(2, $startDate, $endDate)->sum('paid');
        $data['debitCard'] = InvoicePayments::getPaymentSummaryForType(3, $startDate, $endDate)->sum('paid');
        $data['dailySummary'] = InvoicePayments::getPaymentSummaryForType(0, $startDate, $endDate)->sum('paid');


        return view('billing.Invoices.reports.print_daily_audit')->with($data);

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
}
