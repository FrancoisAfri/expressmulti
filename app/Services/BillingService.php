<?php

namespace App\Services;

use App\Models\Accounts;
use App\Models\BillingInvoice;
use App\Models\BillingProcedures;
use App\Models\InsuranceHouse;
use App\Models\InvoiceCompanyProfile;
use App\Models\InvoicePayments;
use App\Models\MedicalAids;
use App\Models\Medicine;
use App\Models\Modifier;
use App\Models\Patient;
use App\Models\PaymentArrangement;
use App\Models\PracticeDoctor;
use App\Models\ProcedureCode;
use App\Traits\CompanyIdentityTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use http\Env\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BillingService
{

    use CompanyIdentityTrait;



    /**
     * @param $request
     * @return JsonResponse
     */
    public function procedureCodePersist($request)
    {
        $validator = validator($request->all(), [
            'code' => 'required',
            'price' => 'required',
            'description' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return ProcedureCode::create($request->all());

    }

    public function procedureCodeUpdate($request, $code)
    {
        $validator = validator($request->all(), [
            'code' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $ProcedureCode = $this->procedureCode::find($code);
        $ProcedureCode->update($request->all());
        return $ProcedureCode;

    }

    public function procedureCodeAct($id)
    {
        $mod = ProcedureCode::find($id);
        $mod['status'] == 1 ? $status = 0 : $status = 1;
        $mod['status'] = $status;
        $mod->update();
    }


    public function paymentArrangementPersist($request)
    {

        $validator = validator($request->all(), [
            'payment_name' => 'required',
            'percentage' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        ($request['in_hospital'] == 'in_hospital') ? ($in_hospital = 1) : ($in_hospital = 0);
        ($request['in_hospital'] == 'out_hospital') ? ($out_hospital = 1) : ($out_hospital = 0);


        return PaymentArrangement::create([
            'payment_name' => $request['payment_name'],
            'percentage' => $request['percentage'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'end_date' => $request['end_date'],
            'out_hospital' => $out_hospital,
            'in_hospital' => $in_hospital
        ]);
    }

    public function paymentArrangementAct($id)
    {

        $mod = PaymentArrangement::find($id);
        $mod['status'] == 1 ? $status = 0 : $status = 1;
        $mod['status'] = $status;
        $mod->update();
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function DoctorPersist($request)
    {
        $validator = validator($request->all(), [
            'name' => 'required',
            'practice_number' => 'required',
            'speciality' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return PracticeDoctor::create($request->all());
    }

    public function DoctorActivate($id)
    {
        $mod = PracticeDoctor::find($id);
        $mod['status'] == 1 ? $status = 0 : $status = 1;
        $mod['status'] = $status;
        $mod->update();
    }

    public function ModifierStore($request)
    {
        $validator = validator($request->all(), [
            'code' => 'required',
            'procedure_code' => 'required',
            'rules' => 'required',
            'percentage' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return Modifier::create($request->all());
    }

    public function ModifierActivate($id)
    {
        $mod = Modifier::find($id);
        $mod['status'] == 1 ? $status = 0 : $status = 1;
        $mod['status'] = $status;
        $mod->update();
    }

    public function MedicinePersist($request)
    {

        $validator = validator($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        return Medicine::create($request->all());
    }

    public function MedicineActivate($id)
    {
        $mod = Medicine::find($id);
        $mod['status'] == 1 ? $status = 0 : $status = 1;
        $mod['status'] = $status;
        $mod->update();
    }

    public function InsuranceStoreActivate($id)
    {
        $mod = InsuranceHouse::find($id);
        $mod['status'] == 1 ? $status = 0 : $status = 1;
        $mod['status'] = $status;
        $mod->update();
    }

    public function MedicalAidActivate($id)
    {
        $mod = MedicalAids::find($id);
        $mod['status'] == 1 ? $status = 0 : $status = 1;
        $mod['status'] = $status;
        $mod->update();
    }

    public function persistBillTransanctions($request)
    {
        //before storing check if patient has an account
		$AccountDetails = $this->isAccountExist($request['patient_no']);
		// generate invoice number
        $invoiceNumber = $this->generateInvoiceNumber();

        $submitClaim = BillingInvoice::create(
            [
                'invoice_number' => $invoiceNumber,
                'invoice_date' => Carbon::now(),
                'accounts_id' => $AccountDetails['id'],
                'invoice_type' => $request['invoice_type'],
                'medical_aid' => $request['medical_aid'],
                'payment_arrangement' => $request['payment_arrangement'],
                'patient_no' => $request['patient_no'],
                'provider_details' => $request['provider_details'],
                'place_of_service' => $request['place_of_service'],
                'insurance_no' => $request['insurance_no'],
                'policy_no' => $request['policy_no'],
                'broker_details' => $request['broker_details'],
            ]
        );

        $count = count($request['date']);

        for ($i = 0; $i < $count; $i++) {

            BillingProcedures::create(
                [
                    'billing_invoice_id' => $submitClaim['id'],
                    'patient_no' => $request['patient_no'],
                    'accounts_id' => $AccountDetails['id'],
                    'date' => $request->date[$i],
                    'serviceProvider' => !empty($request->service_provider[$i]) ? $request->service_provider[$i] : 0,
                    'procedure_code' => !empty($request->procedure_code[$i]) ? $request->procedure_code[$i] : 0,
                    'modifier' => !empty($request->modifier[$i]) ? $request->modifier[$i] : 0,
                    'nappy_code' => !empty($request->nappy_code[$i]) ? $request->nappy_code[$i] : '',
                    'icd10_code' => !empty($request->icd10_code[$i]) ? $request->icd10_code[$i] : '',
                    'quantity' => !empty($request->quantity[$i]) ? $request->quantity[$i] : 0,
                    'unit_price' => !empty($request->unit_price[$i]) ? $request->unit_price[$i] : 0,
                    'price' => !empty($request->price[$i]) ? $request->price[$i] : 0,
                ]
            );
        };
		// ge total amount of transactions
        $amount = BillingProcedures::where('billing_invoice_id', $submitClaim['id'])->sum('price');

        InvoicePayments::create([
            'billing_invoice_id' => $submitClaim['id'],
            'client_id' => $request['patient_no'],
            'date' => Carbon::now(),
            'amount' => $amount,
            'paid' => 0,
            'owed' => $amount,
            'note' => '',
            'invoice_number' => $invoiceNumber,
            'status' => 5,
            'accounts_id' => $AccountDetails['id'],
//            'description' => 'Payments'.':'.
        ]);

		// update invoice with amount and status
		$submitClaim->invoice_amount = $amount;
		$submitClaim->invoice_balance_amount = $amount;
		$submitClaim->status = 5;
		$submitClaim->update();

		// send email to insurance company
        if ($request['indexCount'] === 'email') {

            $details = BillingInvoice::where('id', $submitClaim->id)->with('patient', 'account')->first();
            $invoiceNUmber = $details->invoice_number;
            $componyDetails = $this->CompanyIdentityDetails();
            //genarate invoice
            $data['invoiceDetails'] = InvoiceCompanyProfile::first();
            $data['name'] = $this->CompanyIdentityDetails();
            $data['accounts'] = BillingInvoice::getPrintInvoiceData($submitClaim->invoice_number);
            $data['email'] = $details->patient->email;
            $data['firstname'] = $details->patient->first_name;
            $data['surname'] = $details->patient->surname;
            $data['logo'] = $componyDetails['logo'];

            $pdf = PDF::loadView('billing.Invoices.InvoicePrint.insuarance_invoice', $data)
                ->setPaper(array(0, 0, 609.4488, 935.433), 'landscape');

            //send email attachment
            try {
                Mail::send('Email.invoice', $data, function ($message) use ($details, $invoiceNUmber, $data, $pdf) {
                    $message->to($data["email"], $data["email"])
                        ->subject('Insurance Claim Invoice for' . $details->patient->email)
                        ->attachData($pdf->output(), 'Billing_invoice_' . $invoiceNUmber . '.pdf');
                });
                $this->updateInvoiceEmailStatus($submitClaim['id'], 1);
            } catch (\Exception $e) {
                echo $e;
                $this->updateInvoiceEmailStatus($submitClaim['id'], 0);
            }
            return back();
        }
    }

    public function updateInvoiceEmailStatus($id, $status)
    {
        $mod = BillingInvoice::find($id);
        $mod['email_sent'] = $status;
        $mod->update();
    }

	/**
	* check if account exist
	*
	*
	**/
	public function isAccountExist($id){

		// check if account exist
        $accounts =  Accounts::where('client_id', $id)->first();

        if (empty($accounts)){
            $this->createAccount($id);
        }
		// get account details
		$AccountDetails = Accounts::getAccountById($id);
        return $AccountDetails;
    }

    /**
     * @param $patient_id
     * @return void
     */
    public function createAccount($patient_id)
    {
        Accounts::create([
            'account_number' => $this->generateAccountId(),
            'client_id' => $patient_id,
            'account_manager' => auth()->user()->id, // not sure what to do
            'status' => 1
        ]);
    }

    /**
     * Generate unique Account ID
     *
     * @return string
     */
    public function generateAccountId(): string
    {
        $number = '';
        $name = $this->CompanyIdentityDetails();

        do {
            for ($i = 8; $i--; $i > 0) {
                $number .= mt_rand(0, 14);
            }
        } while (!empty(DB::table('account')
            ->where(
                'account_number', $number
            )->first(
                [
                    'id'
                ]
            ))
        );

        return $name['header_acronym_regular'] . $number;
    }


    /*
     * generate Invoice Number
     */
    public function generateInvoiceNumber(): string
    {
        $latestInvoice = BillingInvoice::latestInvoice();

        $name = $this->CompanyIdentityDetails();
        if (!isset($latestInvoice)) {
            $invoice = (str_pad((int)1, 6, '0', STR_PAD_LEFT));
        } else {
            $invoice = (str_pad((int)$latestInvoice->id + 1, 6, '0', STR_PAD_LEFT));
        }
        return $name['header_acronym_regular'] . $invoice;
    }


}
