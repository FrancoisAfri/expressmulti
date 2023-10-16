<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddDependencyRequest;
use App\Models\Accounts;
use App\Models\Country;
use App\Models\CompanyIdentity;
use App\Models\Dependencies;
use App\Models\Doctor;
use App\Models\EmergencyContact;
use App\Models\Employer;
use App\Models\Guarantor;
use App\Models\MedicalAid;
use App\Models\MedicalAids;
use App\Models\Patient;
use App\Models\Url;
use App\Models\Province;
use App\Services\BillingService;
use App\Services\CommunicationService;
use App\Services\PatientService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use RealRashid\SweetAlert\Facades\Alert;

class PatientControlle extends Controller
{
    use BreadCrumpTrait, CompanyIdentityTrait;

    /**
     * @var PatientService
     */
    private $patientService;
    /**
     * @var CommunicationService
     */
    private $communicationService;

    /**
     * @var
     */
    private $billingService;

    public function __construct(
        CommunicationService $communicationService,
        PatientService       $patientService,
        BillingService       $billingService

    )
    {
        $this->patientService = $patientService;
        $this->communicationService = $communicationService;
        $this->billingService = $billingService;


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = $this->breadcrumb(
            'Client ',
            'Client page for Client related settings',
            'patient_details',
            'Client Profile',
            'Client Details'
        );

        $data['medicalAids'] = MedicalAids::getMedicalAids();
        $data['country'] = Country::getAllCountries();

        return view('patients.index')->with($data);
    }

    public function GuestForm($key)
    {

        $expire = Url::getExpired($key);
        $now = mktime();
        if ($expire <= $now) {
            return response()->view('errors.link-expired');
        }

        $id = Url::getlink($key);

        //delete link

        $terms = CompanyIdentity::first();
        $companyDetails = $this->CompanyIdentityDetails();
        $data = $this->breadcrumb(
            'Client ',
            'Client page for Client related settings',
            'patient_details',
            'Client Profile',
            'Client Details'
        );

        $data['medicalAids'] = MedicalAids::getMedicalAids();
        $patient = Patient::getPatientByUuid($id);
//        dd($patient);
        $data['logo'] = $companyDetails['logo'];
        $data['patient'] = Patient::getPatientByUuid($id);
        $data['gender'] = Patient::getPatientGender();
        $data['country'] = Country::getAllCountries();
        $data['terms_and_conditions'] = strip_tags(html_entity_decode($companyDetails['terms_and_conditions']));
        $data['dependencies'] = Dependencies::where('patient_id', $patient->id)->get();

        return view('patients.guestPatientForm')->with($data);
    }

    public function AcceptTerms(Request $request)
    {

        $validator = validator($request->all(), [
            'is_accepted' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        Patient::where("id", $request['user_id'])->update(["is_accepted" => $request['is_accepted']]);
        return response()->json();
    }

    private function str_fix($string)
    {
        $end = '...';
        $new_str = strip_tags(html_entity_decode($string));
        if (mb_strwidth($string, 'UTF-8') <= 150) {
            return $new_str;
        }
        return rtrim(mb_strimwidth($new_str, 0, 150, '', 'UTF-8')) . $end;
    }


    public function patientManagement()
    {

        $data = $this->breadcrumb(
            'Client ',
            'Client page for Client related settings',
            'patient_details',
            'Client Profile',
            'Client Management'
        );


        $data['patient'] = Patient::getPatientDetails();
        $data['gender'] = Patient::getPatientGender();
        $data['country'] = Country::getAllCountries();

        return view('patients.patient_management')->with($data);
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {


        $request->validate([
            'first_name' => 'required|max:255',
            'initial' => 'required',
            'surname' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'email' => 'required|unique:patient',
            'phone_number' => 'required|unique:patient'
        ]);

        if ($request->radioInline == 'id_number') {
            $request->validate([
                'id_number' => 'required|unique:patient'
            ]);
        } elseif ($request->radioInline == 'passport') {
            $request->validate([
                'passport_number' => 'required|unique:patient'
            ]);
        }

        $patientRecord = $this->patientService->persistPatientData($request);

        alert()->success('SuccessAlert', 'Your changes have been saved successfully');
        activity()->log('Client Information created');
        return redirect()->route('patient_details.show', $patientRecord->uuid);
    }

    public function storeDependencies(AddDependencyRequest $request)
    {
        $requestData = $request->validationData();
        $this->patientService->persistDependencies($requestData);
        alert()->success('SuccessAlert', 'Record Created Successfully');
        return response()->json(['message' => 'success'], 200);
    }


    public function destroyDependencies(Dependencies $dependency)
    {
        $dependency->delete();
        Alert::toast('Record Deleted Successfully ', 'success');
        return redirect()->back();
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
            'Client ',
            'Client page for Client related settings',
            'patient_details',
            'Client Profile',
            'Client Management'
        );

        $patient = Patient::getPatientByUuid($id);


        if (empty($patient)) {
            $data['patient'] = Patient::getPatientDetails();
            $data['gender'] = Patient::getPatientGender();
            $data['country'] = Country::getAllCountries();

            return redirect()->route('patientManagement.index')->with($data);

        } else {
            $data['medicalAids'] = MedicalAids::getMedicalAids();
            $data['patient'] = Patient::getPatientByUuid($id);
            $data['gender'] = Patient::getPatientGender();
            $data['country'] = Country::getAllCountries();
            $data['dependencies'] = Dependencies::where('patient_id', $patient->id)->get();

            return view('patients.patient_management_edit')->with($data);
        }


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
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->patientService->updatePatientDetails($request, $id);

        alert()->success('SuccessAlert', 'Your changes have been saved successfully');
        activity()->log('Client Information updated');
        return back();
    }

    public function patientManagementGuest(Request $request, $id)
    {

        $this->patientService->completeGuestPatient($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->patientService->destroyPatientRecords($id);

        Alert::toast('Record Status destroyed Successfully ', 'success');
        activity()->log('Client status destroyed');
        return back();
    }


    /**
     * @param Patient $patient
     * @return RedirectResponse
     */
    public function activatePatient(Patient $patient): RedirectResponse
    {

        $this->patientService->ManagePatient($patient);
        Alert::toast('Record Status changed Successfully ', 'success');
        activity()->log('Client status changed');
        return back();
    }

    public function renewGuestSession($id)
    {

        $this->communicationService->sendGuestLink($id);
        Alert::toast('guest session link sent ', 'success');
        activity()->log('guest session link sent');
        return back();
    }
}
