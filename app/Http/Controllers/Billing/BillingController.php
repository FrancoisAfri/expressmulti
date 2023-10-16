<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\DTO\CreatePostDTO;
use App\Http\DTO\SubscriberData;
use App\Models\Accounts;
use App\Models\BillingInvoice;
use App\Models\BillingProcedures;
use App\Models\InsuranceHouse;
use App\Models\MedicalAids;
use App\Models\Modifier;
use App\Models\Patient;
use App\Models\PaymentArrangement;
use App\Models\PracticeDoctor;
use App\Models\ProcedureCode;
use App\Models\serviceProvider;
use App\Services\BillingService;
use App\Services\ExternalClientService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BillingController extends Controller
{

    use BreadCrumpTrait, CompanyIdentityTrait;

    /**
     * @var BillingService
     */
    private $billingService;
    /**
     * @var ExternalClientService
     */
    private ExternalClientService $clientService;


    public function __construct(
        BillingService        $billingService
//        ExternalClientService $clientService

    )
    {
        $this->billingService = $billingService;
//        $this->clientService = $clientService;
        $this->middleware('role:Admin|Reception|Billing Manager|Practice Manager');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws GuzzleException
     */
    public function index()
    {
//        $users = $this->clientService->get();

        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'bills',
            'Client Billing',
            'Billing'
        );

        $data['modifier'] = Modifier::getModifier();
        $data['serviceProvider'] = serviceProvider::all();
        $data['patients'] = Patient::getPatientInfo();
        $data['doctors'] = PracticeDoctor::getDoctor();
        $data['insurances'] = InsuranceHouse::getInsuranceHouse();
        $data['medicalAids'] = MedicalAids::getMedicalAids();
        $data['procedureCode'] = ProcedureCode::getProcedureCode();
        $data['paymentArrangement'] = PaymentArrangement::getPaymentArrangement();

        return view('billing.Bills.index')->with($data);
    }

    public function Accounts()
    {
        $data = $this->breadcrumb(
            'Client Billing',
            'Client Billing page for Client related billing',
            'bills',
            'Client Billing',
            'Billing'
        );

        $data['avatar'] = asset('images/m-silhouette.jpg');
        $data['accounts'] = Accounts::with('patient')->get();
        return view('billing.Accounts.index')->with($data);
    }


    public function getPaymentArrangementById($id)
    {
        $payment = PaymentArrangement::getPaymentArrangementById($id);
        return response($payment, 200);

    }

    public function getModifierPercentageById($id)
    {
        $payment = Modifier::getModifierPercentageById($id);
        return response($payment, 200);
    }

    public function getPaymentArrangement()
    {
        $payments = PaymentArrangement::get();
        return response($payments, 200);
    }

    public function getProcedurePrice($id)
    {
        $ProcedurePrice = ProcedureCode::getProcedurePrice($id);
        return response($ProcedurePrice, 200);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, BillingService $billingService)
    {
        //validation
        $validator = validator($request->all(), [
            'patient_no' => 'required',
            'provider_details' => 'required',
            'place_of_service' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $billProcedure = $billingService->persistBillTransanctions($request);
        Alert::toast('Booking Added Successfully ', 'success');

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

    public function destroyAccount($id): RedirectResponse
    {
        $account = Accounts::find($id);
        $account->delete();

        Alert::toast('Record  Deleted Successfully ', 'success');
        return back();
    }


    public function activateAccount($accounts): RedirectResponse
    {
        $account = Accounts::find($accounts);
        $account['status'] == 1 ? $status = 0 : $status = 1;
        $account['status'] = $status;
        $account->update();

        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }
}
