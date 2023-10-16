<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Models\BillingProcedures;
use App\Models\InsuranceHouse;
use App\Models\InvoiceCompanyProfile;
use App\Models\InvoicePayments;
use App\Models\MedicalAids;
use App\Models\Medicine;
use App\Models\Modifier;
use App\Models\PaymentArrangement;
use App\Models\PracticeDoctor;
use App\Models\ProcedureCode;
use App\Services\BillingService;
use App\Traits\BreadCrumpTrait;
use App\Traits\FileUpload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BillingSetupController extends Controller
{
    use BreadCrumpTrait ,FileUpload;

    private $billingService;
    /**
     * @var ProcedureCode
     */
    private $procedureCode;

    public function __construct(
        BillingService $billingService,
        ProcedureCode  $procedureCode
    )
    {
        $this->billingService = $billingService;
        $this->procedureCode = $procedureCode;
        $this->middleware('role:Admin|Reception|Practice Manager');
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
            'setup',
            'Client Billing',
            'Billing Setup'
        );

//        $data['patients'] = Patient::getPatientInfo();
        return view('billing.index')->with($data);
    }


    public function setup()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'setup',
            'Client Billing',
            'Billing Setup'
        );

//        BillingInvoice::truncate();
//        InvoicePayments::truncate();
//        BillingProcedures::truncate();

        $data['profile'] = InvoiceCompanyProfile::all();
        return view('billing.invoiceProfile.setup')->with($data);
    }

    public function SetupStore(Request $request)
    {

        $Profile =   InvoiceCompanyProfile::create([
            "company_name" => $request['company_name'],
            "registration_number" => $request['registration_number'],
            "vat_number" => $request['vat_number'],
            "bank_name" => $request['bank_name'],
            "bank_branch_code" => $request['bank_branch_code'],
            "bank_account_name" => $request['bank_account_name'],
            "bank_account_number" => $request['bank_account_number'],
        ]);

        Alert::toast('Booking Added Successfully ', 'success');

        $this->uploadImage($request, 'letter_head', 'InvoiceCompanyProfile', $Profile);

        return response()->json([
            'success' => true,
            'data' => $Profile
        ], 200);

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

    public function procedureCode()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'procedureCode page for Client related billing',
            'procedureCode',
            'Client Billing',
            'procedureCode'
        );

        $data['procedureCode'] = ProcedureCode::getProcedureCode();
        return view('billing.procedureCode.index')->with($data);
    }

    public function procedureCodePersist(Request $request)
    {

        $ProcedureCode = $this->billingService->procedureCodePersist($request);

        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $ProcedureCode
        ], 200);
    }

    public function procedureCodeUpdate(Request $request, $code)
    {

        $ProcedureCode = $this->procedureCode::find($code);
        $ProcedureCode->update($request->all());

        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();

    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function procedureCodeAct($id)
    {
        $this->billingService->procedureCodeAct($id);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }


    public function paymentArrangement()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'setup',
            'Client Billing',
            'Billing Setup'
        );

        $data['paymentArrangement'] = PaymentArrangement::getPaymentArrangement();
        return view('billing.paymentArrangement.index')->with($data);
    }


    public function paymentArrangementPersist(Request $request): JsonResponse
    {

        $PaymentArrangement = $this->billingService->paymentArrangementPersist($request);

        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $PaymentArrangement
        ], 200);

    }

    public function paymentArrangementUpdate(Request $request)
    {

        ($request['in_hospital'] == 'in_hospital') ? ($in_hospital = 1) : ($in_hospital = 0);
        ($request['in_hospital'] == 'out_hospital') ? ($out_hospital = 1) : ($out_hospital = 0);

        $paymentArrangement = PaymentArrangement::find($request['formId']);

        $paymentArrangement->update([
            'payment_name' => $request['payment_name'],
            'percentage' => $request['percentage'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'end_date' => $request['end_date'],
            'out_hospital' => $out_hospital,
            'in_hospital' => $in_hospital
        ]);

        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();
    }


    public function paymentArrangementActivate($id)
    {
        $this->billingService->paymentArrangementAct($id);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }

    public function practiceDoctors()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'setup',
            'Client Billing',
            'Billing Setup'
        );

        $data['doctor'] = PracticeDoctor::getDoctor();
        return view('billing.Doctor.index')->with($data);
    }

    public function DoctorPersist(Request $request)
    {

        $Doctor = $this->billingService->DoctorPersist($request);

        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $Doctor
        ], 200);
    }

    public function DoctorUpdate(Request $request)
    {
        $practiceDoctor = PracticeDoctor::find($request['formId']);
        $practiceDoctor->update($request->all());
        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();
    }

    public function DoctorActivate($id)
    {
        $this->billingService->DoctorActivate($id);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }


    /**
     * @return Application|Factory|View
     */
    public function Modifier()
    {
        $data = $this->breadcrumb(
            'Modifier ',
            'Modifier page for Client related billing',
            'modifier',
            'Client Billing',
            'Modifier'
        );

        $data['modifier'] = Modifier::getModifier();
        $data['procedureCode'] = ProcedureCode::all();
        return view('billing.Modifier.index')->with($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ModifierStore(Request $request)
    {

        $validator = validator($request->all(), [
            'procedure_code' => 'required',
            'code' => 'required',
            'rules' => 'required',
            'percentage' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $Modifier = $this->billingService->ModifierStore($request);

        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $Modifier
        ], 200);
    }

    public function ModifierUpdate(Request $request)
    {

        $modifier = Modifier::find($request['formId']);
        $modifier->update($request->all());
        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();
    }

    public function ModifierActivate($id)
    {
        $this->billingService->ModifierActivate($id);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }


    public function Medicine()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'setup',
            'Client Billing',
            'Billing Setup'
        );

        $data['medicine'] = Medicine::getMedicine();

        return view('billing.Medicine.index')->with($data);
    }

    public function MedicineStore(Request $request)
    {

        $validator = validator($request->all(), [
            'name' => 'required',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $medicine = Medicine::create($request->all());

        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $medicine
        ], 200);
    }

    public function MedicineUpdate(Request $request)
    {

        $medicine = Medicine::find($request['formId']);
        $medicine->update($request->all());
        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();
    }

    public function MedicineActivate($id)
    {
        $this->billingService->MedicineActivate($id);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }

    public function Insurance()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'setup',
            'Client Billing',
            'Billing Setup'
        );

        $data['insurances'] = InsuranceHouse::getInsuranceHouse();

        return view('billing.Insurance.index')->with($data);
    }

    public function InsuranceStore(Request $request)
    {

        $validator = validator($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $Insurance = InsuranceHouse::create($request->all());

        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $Insurance
        ], 200);
    }

    public function InsuranceStoreUpdate(Request $request)
    {

        $insurance = InsuranceHouse::find($request['formId']);
        $insurance->update($request->all());
        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();
    }

    public function InsuranceStoreActivate($id)
    {

        $this->billingService->InsuranceStoreActivate($id);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }

    public function MedicalAid()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'setup',
            'Client Billing',
            'Billing Setup'
        );

        $data['medicalAids'] = MedicalAids::all();

        return view('billing.MedicalAids.index')->with($data);
    }

    public function MedicalAidStore(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $MedicalAids = MedicalAids::create($request->all());

        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $MedicalAids
        ], 200);
    }

    public function MedicalAidUpdate(Request $request)
    {

        $medicalAid = MedicalAids::find($request['formId']);
        $medicalAid->update($request->all());
        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();
    }

    public function MedicalAidActivate($id)
    {
        $this->billingService->MedicalAidActivate($id);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }
}
