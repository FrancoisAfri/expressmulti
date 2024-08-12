<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\BillingInvoice;
use App\Models\BillingProcedures;
use App\Models\CompanyIdentity;
use App\Models\CreditNote;
use App\Models\InvoiceCompanyProfile;
use App\Models\InvoicePayments;
use App\Models\Packages;
use App\Models\Patient;
use App\Models\PaymentArrangement;
use App\Services\CommunicationService;
use App\Services\crons\EmailInvoiceToAllClients;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use App\Traits\SubscriptionConstants;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class InvoiceController extends Controller
{
    use BreadCrumpTrait, CompanyIdentityTrait , SubscriptionConstants;

    /**
     * @var CommunicationService
     */
    private $CommunicationService;
    private EmailInvoiceToAllClients $emailInvoiceToAllClients;

    public function __construct(EmailInvoiceToAllClients $emailInvoiceToAllClients)
    {
        $this->emailInvoiceToAllClients = $emailInvoiceToAllClients;
    }


    public function index()
    {

       $PackageType = $this->subcriptions();

//        $patientsWithPackageType = Packages::getPatientsByPackageType(1);
        $this->emailInvoiceToAllClients->EmailInvoiceToAllClientsDependingOnTheirSubscription();

//        dd(PackageConstants::YEARLY);
        $data['name'] = $this->CompanyIdentityDetails();
        $data['companies'] = Patient::with('packages')->get();
        $data['date'] = $currentDate = date('j M Y');
        $data['invoice_number'] = $this->generateInvoiceNumber();
        $data['company_details'] = $this->CompanyIdentityDetails();
        return view('invoice.test')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = 0;

        if ($request['amount'] < $request['owed']) {
            $status = 7;
        } elseif ($request['amount'] >= $request['owed']) {
            $status = 8;
        }
        // detemine payment method
        switch ($request['payment_type']) {
            case "1":
                $paymentType = 'Cash';
                break;
            case "2":
                $paymentType = 'Eft';
                break;
            case "3":
                $paymentType = 'Debit Card';
                break;
            default:
                $paymentType = 'Cash';
        };
        // save invoice installment
        $billProcedure = InvoicePayments::create([
            'billing_invoice_id' => $request['billing_invoice_id'],
            'client_id' => $request['patient_no'],
            'date' => $request['date'],
            'amount' => $request['total_amount'],
            'paid' => $request['amount'],
            'owed' => $request['owed'] - $request['amount'],
            'note' => !empty($request->note) ? $request->note : '',
            'invoice_number' => $request['invoice_number'],
            'status' => $status,
            'accounts_id' => $request['accounts_id'],
            'description' => 'Payment received:' . ':' . $paymentType . ' ' . '- Patient',
            'payment_type' => $request['payment_type']
        ]);
        // if credit note save in the database
        if ($request->has('note')) {
            CreditNote::create([
                'account_no' => $request['account_no'],
                'invoice_id' => $request['billing_invoice_id'],
                'client_id' => $request['patient_no'],
                'amount' => $request['amount'],
                'date' => $request['date'],
                'note' => $request['note'],
            ]);
        }
        // update invoice table
        $invoice = BillingInvoice::find($request['billing_invoice_id']);
        $invoice->invoice_balance_amount = $request['owed'] - $request['amount'];
        $invoice->status = $status;
        $invoice->update();
        // send success message
        Alert::toast('Payment Captured Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $billProcedure
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'bills',
            'Client Billing',
            'Billing'
        );

        $account = Accounts::getAccountByUuid($id);


        $data['purchaseStatus'] = ['' => '', 5 => 'Client Waiting Invoice', 6 => 'Invoice Sent', 7 => 'Partially Paid', 8 => 'Paid'];
        $data['labelColors'] = ['' => 'danger', 5 => 'warning', 6 => 'primary', 7 => 'primary', 8 => 'success'];
        $data['avatar'] = asset('images/m-silhouette.jpg');
        $data['runningCost'] = BillingInvoice::where('accounts_id', $account->id)->sum('invoice_amount');
        $data['totalOwed'] = BillingInvoice::where('accounts_id', $account->id)->sum('invoice_balance_amount');
        $data['totalPaid'] = InvoicePayments::where('accounts_id', $account->id)->sum('paid');
        $data['accounts'] = Accounts::getAccountDetails($id);
        $data['myInvoces'] = BillingInvoice::getInvoices($account->id);
        return view('billing.Invoices.index')->with($data);
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

    public function printInvoice($id)
    {
        $data = $this->InvoiceDetails($id);
        return view('billing.Invoices.InvoicePrint.Invoice_pdf')->with($data);
    }

    public function downloadInvoice($id): \Illuminate\Http\Response
    {
        $data = $this->InvoiceDetails($id);

        PDF::setOptions(['isRemoteEnabled' => TRUE, 'enable_javascript' => TRUE]);
        $pdf = PDF::loadView('billing.Invoices.InvoicePrint.cust_invoice', $data)->setPaper(array(0, 0, 609.4488, 935.433), 'landscape');
        return $pdf->download('Billing_invoice_' . $id . '.pdf');
    }

    public function sendInvoice($id)
    {
        $details = BillingInvoice::where('id', $id)->with('patient', 'account')->first();
        $componyDetails = $this->CompanyIdentityDetails();
        $info = $this->InvoiceDetails($details->invoice_number);
        $invoiceNUmber = $details->invoice_number;
        $pdf = PDF::loadView('billing.Invoices.InvoicePrint.cust_invoice', $info)->setPaper(array(0, 0, 609.4488, 935.433), 'landscape');

        $data['email'] = $details->patient->email;
        $data['firstname'] = $details->patient->first_name;
        $data['surname'] = $details->patient->surname;
        $data['logo'] = $componyDetails['logo'];

        Mail::send('Email.invoice', $data, function ($message) use ($details, $invoiceNUmber, $data, $pdf) {
            $message->to($data["email"], $data["email"])
                ->subject('Invoice for ' . $details->patient->first_name)
                ->attachData($pdf->output(), 'Billing_invoice_' . $invoiceNUmber . '.pdf');
        });

        return back();
    }

    public function StatementIndex(Request $request, $id)
    {
        $data['six_months_go'] = $this->getFormatedMonth($id, 0);
        $data['five_months_go'] = $this->getFormatedMonth($id, 1);
        $data['four_months_go'] = $this->getFormatedMonth($id, 2);
        $data['three_months_go'] = $this->getFormatedMonth($id, 3);
        $data['two_months_go'] = $this->getFormatedMonth($id, 4);
        $data['one_months_go'] = $this->getFormatedMonth($id, 5);
        $data['current'] = $this->getFormatedMonth($id, 5);

        $clientId = Accounts::getAccount($id)->client_id;
        $data['uuid'] = $id;
        $data['months'] = $this->getLastSixMonths();

        return view('billing.Invoices.statement_index')->with($data);
    }

    public function getFormatedMonth($id, $index)
    {
        $lastSixMonth = $this->subMonths()[$index];
        $month = $lastSixMonth['month'];
        $year = $lastSixMonth['year'];
        return InvoicePayments::getDetailsForLastSixMonths($id, $year, $month);
    }

    private function calculateAmountDue($id, $index)
    {
        $lastSixMonth = $this->subMonths()[$index];
        $month = $lastSixMonth['month'];
        $year = $lastSixMonth['year'];
        return BillingInvoice::getAmountDue($id, $year, $month);
    }


    private function openingBalance($id, $index)
    {
        $lastSixMonth = $this->subMonths()[$index];
        $month = $lastSixMonth['month'];
        $year = $lastSixMonth['year'];
        return BillingInvoice::getAmountDue($id, $year, $month);
    }


    private function amountPaid($id, $index)
    {
        $lastSixMonth = $this->subMonths()[$index];
        $month = $lastSixMonth['month'];
        $year = $lastSixMonth['year'];
        return InvoicePayments::totalPaidThisMonth($id, $year, $month);
    }


    public function getLastSixMonths()
    {
        for ($i = 0; $i < 6; $i++) {
            $months[] = date("F Y", strtotime(date('Y-m-01') . " -$i months") - 1);
        }
        return $months;
    }

    public function subMonths()
    {

        $period = now()->subMonths(6)->monthsUntil(now());
        $data = [];
        foreach ($period as $date) {
            $data[] = [
                'month' => $date->month,
                'year' => $date->year,
            ];
        }
        return $data;
    }

    public function printDetailedStatement(Request $request)
    {
        $dates = explode("to", $request['date_range']);

        $startDate = $dates[0];
        $endDate = $dates[1];

        $data['name'] = $this->CompanyIdentityDetails();
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['patientDetails'] = Patient::getPatientDataById($request['client_id']);
        $data['date'] = Carbon::now()->toFormattedDateString();
        $data['accounts'] = InvoicePayments::getInvoiceDetails($request['client_id'], $startDate, $endDate);
        return view('billing.Invoices.InvoicePrint.detailedStatement')->with($data);
    }

    /**
     * @throws ValidationException
     */
    public function printStatement(Request $request, $id)
    {

        $this->validate($request, [
            'date_range' => 'required',
        ]);

        $dates = explode("to", $request['date_range']);
        $startDate = $dates[0];
        $endDate = $dates[1];

        $ID = Accounts::getAccountByUuid($id)->id;

        $data['patientDetails'] = Patient::getPatientDataById($ID);
        $data['date'] = Carbon::now()->toFormattedDateString();
        $data['name'] = $this->CompanyIdentityDetails();
        $data['accountDetails'] = Accounts::getAccount($id);
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['amountDue'] = InvoicePayments::AmountDue($ID, $startDate, $endDate);
        $data['openingBalance'] = InvoicePayments::AmountDue($request['client_id'], $startDate, $endDate);
        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
        $data['totalPaid'] = InvoicePayments::totalPaid($id, $startDate, $endDate);
        $data['accounts'] = InvoicePayments::getInvoiceDetails($ID, $startDate, $endDate);
        return view('billing.Invoices.InvoicePrint.statement')->with($data);
    }

    public function statementMonth1($id)
    {
        $client_id = Accounts::getAccountByUuid($id)->client_id;
//            dd($this->calculateAmountDue($client_id,6));
        $data = $this->detailedInfo($id);
        $data['amountDue'] = $this->calculateAmountDue($client_id, 5);
        $data['amountPaid'] = $this->amountPaid($client_id, 6);
        $data['openingBalance'] = $this->calculateAmountDue($client_id, 6);

        return view('billing.Invoices.InvoicePrint.statement_month_1')->with($data);
    }

    public function statementMonth2($id)
    {
        $client_id = Accounts::getAccountByUuid($id)->client_id;
        $data = $this->detailedInfo($id);
        $data['amountDue'] = $this->calculateAmountDue($client_id, 4);
        return view('billing.Invoices.InvoicePrint.statement_month_2')->with($data);
    }

    public function statementMonth3($id)
    {
        $client_id = Accounts::getAccountByUuid($id)->client_id;
        $data = $this->detailedInfo($id);
        $data['amountDue'] = $this->calculateAmountDue($client_id, 3);
        return view('billing.Invoices.InvoicePrint.statement_month_3')->with($data);
    }

    public function statementMonth4($id)
    {
        $client_id = Accounts::getAccountByUuid($id)->client_id;
        $data = $this->detailedInfo($id);
        $data['amountDue'] = $this->calculateAmountDue($client_id, 2);
        return view('billing.Invoices.InvoicePrint.statement_month_4')->with($data);
    }

    public function statementMonth5($id)
    {
        $client_id = Accounts::getAccountByUuid($id)->client_id;
        $data = $this->detailedInfo($id);
        $data['amountDue'] = $this->calculateAmountDue($client_id, 1);
        return view('billing.Invoices.InvoicePrint.statement_month_5')->with($data);
    }

    public function statementMonth6($id)
    {
        $client_id = Accounts::getAccountByUuid($id)->client_id;
        $data = $this->detailedInfo($id);
        $data['amountDue'] = $this->calculateAmountDue($client_id, 0);
        return view('billing.Invoices.InvoicePrint.statement_month_6')->with($data);
    }

    private function detailedInfo($id)
    {

        $account_id = Accounts::getAccountByUuid($id)->id;

        $data['six_months_go'] = $this->getFormatedMonth($id, 0);
        $data['five_months_go'] = $this->getFormatedMonth($id, 1);
        $data['four_months_go'] = $this->getFormatedMonth($id, 2);
        $data['three_months_go'] = $this->getFormatedMonth($id, 3);
        $data['two_months_go'] = $this->getFormatedMonth($id, 4);
        $data['one_months_go'] = $this->getFormatedMonth($id, 5);
        $data['current'] = $this->getFormatedMonth($id, 5);
        $data['patientDetails'] = Patient::getPatientDataById($account_id);
        $data['date'] = Carbon::now()->toFormattedDateString();
        $data['name'] = $this->CompanyIdentityDetails();
        $data['accountDetails'] = Accounts::getAccount($id);
        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
//        $data['totalPaid'] = InvoicePayments::totalPaid($id,$startDate, $endDate);
//        $data['accounts'] = InvoicePayments::getInvoiceDetails($ID, $startDate, $endDate);

        return $data;
    }


    public function InvoiceDetails($id): array
    {
        $data['invoiceDetails'] = InvoiceCompanyProfile::invoiceSettings();
        $data['name'] = $this->CompanyIdentityDetails();
        $data['accounts'] = BillingInvoice::getPrintInvoiceData($id);
        return $data;
    }

    public function generateInvoiceNumber(): string
    {
        $uniqueId = DB::table('package_invoice')->max('id') + 1;
        $date = Carbon::now()->format('Ymd');
        return 'INV-' . $date . '-' . str_pad($uniqueId, 4, '0', STR_PAD_LEFT);
    }
}
