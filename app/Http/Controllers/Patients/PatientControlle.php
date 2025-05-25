<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddDependencyRequest;
use App\Http\Requests\AddContactPersonRequest;
use App\Http\Requests\AddPackagesRequest;
use App\Models\Country;
use App\Models\CompanyIdentity;
use App\Models\ContactPerson;
use App\Models\Packages;
use App\Models\Patient;
use App\Models\Companies_temp;
use App\Models\ContactPersonTemp;
use App\Models\Url;
use App\Models\Province;
use App\Services\CommunicationService;
use App\Services\ClientService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class PatientControlle extends Controller
{
    use BreadCrumpTrait, CompanyIdentityTrait;

    /**
     * @var ClientService
     */
    private $clientService;
    /**
     * @var CommunicationService
     */
    private $communicationService;

    /**
     * @var
     */

    public function __construct(CommunicationService $communicationService, ClientService $clientService)
    {
        $this->ClientService = $clientService;
        $this->communicationService = $communicationService;


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->breadcrumb(
            'Client',
            'Client page for Client related settings',
            'patient_details',
            'Client Profile',
            'Client Details'
        );
		$data['packages'] = Packages::getPackages();
        $data['country'] = Country::getAllCountries();

        return view('client.index')->with($data);
    }
	// view all packages function
	public function packagesView()
    {
        $data = $this->breadcrumb(
            'Client',
            'Packages page for all packages management',
            'packages_view',
            'Client Profile',
            'Client Details'
        );
		
		$data['packages'] = Packages::getPackages();
		 
        return view('client.packages')->with($data);
    }
	// store packages
	public function storePackage(AddPackagesRequest $request)
    {
        $requestData = $request->validationData();
        $this->ClientService->persistPackages($requestData);
        alert()->success('SuccessAlert', 'Record Created Successfully');
        return response()->json(['message' => 'success'], 200);
    }
	
	// package update
	public function packageUpdate(AddPackagesRequest $request, Packages $package)
    {
        $requestData = $request->validationData();
        $this->ClientService->updatePackage($requestData, $package);
        alert()->success('SuccessAlert', 'Record Updated Successfully');
        return response()->json(['message' => 'success'], 200);
    }

	// delete packages
    public function destroyPackage(Packages $package)
    {
        $package->delete();
        Alert::toast('Record Deleted Successfully ', 'success');
        return redirect()->back();
    }
	// active/de-activate package
	public function activatePackage(Packages $package): RedirectResponse
    {
        $this->ClientService->activatePackage($package);
        Alert::toast('Record Status changed Successfully ', 'success');
        activity()->log('Package status changed');
        return back();
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


    public function clientslist()
    {
		
        $data = $this->breadcrumb(
            'Client',
            'Client page for Client related settings',
            'clients_details',
            'Client Profile',
            'Client Management'
        );

        $data['clients'] = Patient::getPatientDetails();

        return view('client.client_management')->with($data);
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
      //  $request->validate([
         //   'name' => 'required|max:255',
         //   'package_id' => 'required',
         //   'email' => 'required|unique:companies',
         //   'phone_number' => 'required|unique:companies'
        //]);

        $patientRecord = $this->ClientService->persistClientTempData($request);
		
        alert()->success('SuccessAlert', 'New record have been saved successfully');
        activity()->log('Client Information created');
		if (!empty($patientRecord->uuid))
			return redirect()->route('client_details.show', $patientRecord->uuid);
		else return redirect()->route('clientManagement.index');
    }

	// store dependencies
    public function storeContactPerson(AddContactPersonRequest $request)
    {
        $requestData = $request->validationData();
        $this->ClientService->persistContactPerson($requestData);
        alert()->success('SuccessAlert', 'Record Created Successfully');
		activity()->log('Client contact person created');
        return response()->json(['message' => 'success'], 200);
    } 
	// save package


    public function destroyContactPerson(ContactPerson $contact)
    {
        $contact->delete();
        Alert::toast('Record Deleted Successfully ', 'success');
		activity()->log('Client contact person deleted');
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
            'Client page for Client details settings',
            'client_details',
            'Client Profile',
            'Client Management'
        );

        $client = Patient::getPatientByUuid($id);
		
        if (empty($client)) {
			// get all client in the system
            $data['clients'] = Patient::getPatientDetails();
			// redirect to client index page
            return redirect()->route('clientManagement.index')->with($data);

        } else {
            // return to client edit page
            $data['client'] = Patient::getPatientByUuid($id);
            $data['contactPersons'] = ContactPerson::where('company_id', $client->id)->get();
			$data['packages'] = Packages::getPackages();

            return view('client.client_management_edit')->with($data);
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

        $this->ClientService->updateClientDetails($request, $id);

        alert()->success('SuccessAlert', 'Your changes have been updated successfully');
        activity()->log('Client Information Updated');
        return back();
    }

    public function patientManagementGuest(Request $request, $id)
    {

        $this->ClientService->completeGuestPatient($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->ClientService->destroyPatientRecords($id);

        Alert::toast('Record Status destroyed Successfully ', 'success');
        activity()->log('Client status destroyed');
        return back();
    }


    /**
     * @param Patient $patient
     * @return RedirectResponse
     */
    public function activateClient(Patient $client): RedirectResponse
    {

        $this->ClientService->activeClient($client);
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
	////
	public function approvals()
    {
        $data = $this->breadcrumb(
            'Client',
            'Client page for Client related settings',
            'clients_details',
            'Client Profile',
            'Client Management'
        );

        $data['clients'] = Companies_temp::getPatientDetails();

        return view('client.client_management_approval')->with($data);
    }
	/// approve client 
	public function approveClient($id): RedirectResponse
    {

		$client = Companies_temp::getPatientByUuid($id);
		return $client;
        $this->ClientService->approveClient($client);
        Alert::toast('Client have been approved Successfully ', 'success');
        activity()->log('Client status changed');
        return back();
    }
	
	
	// decline client
	public function declineClient($id): RedirectResponse
    {
		$client = Companies_temp::getPatientByUuid($id);
        $this->ClientService->declineClient($client);
        Alert::toast('Client have been declined Successfully ', 'success');
        activity()->log('Client status changed');
        return back();
    }
	// temp Show
	public function showTemp($id)
    {

        $data = $this->breadcrumb(
            'Client ',
            'Client page for Client details settings',
            'client_details',
            'Client Profile',
            'Client Management'
        );

        $client = Companies_temp::getPatientByUuid($id);

		$data['client'] = $client ;
		$data['contactPersons'] = ContactPersonTemp::where('company_id', $client->id)->get();
		$data['packages'] = Packages::getPackages();

		return view('client.client_management_edit_temp')->with($data);
    }
}
