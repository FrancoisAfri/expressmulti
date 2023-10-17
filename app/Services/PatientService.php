<?php

namespace App\Services;

use App\Models\Accounts;
use App\Models\Packages;
use App\Models\Dependencies;
use App\Models\Doctor;
use App\Models\EmergencyContact;
use App\Models\Employer;
use App\Models\Guarantor;
use App\Models\MainMember;
use App\Models\MedicalAid;
use App\Models\Patient;
use App\Services\BillingService;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class PatientService
{
    use FileUpload;

    /**
     * @var \App\Services\BillingService
     */
    private $billingService;

    public function __construct(
        BillingService $billingService
    ){
        $this->billingService = $billingService;
    }



    /**
     * @param $request
     * @return void
     */
    public function persistPatientData($request)
    {

        try {
            DB::beginTransaction();

            if (isset($request['date_of_birth'])) {
                $request['date_of_birth'] = str_replace('/', '-', $request['date_of_birth']);
                $request['date_of_birth'] = strtotime($request['date_of_birth']);
            }

            $mobile = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('phone_number'));
            $request->merge(array('phone_number' => $mobile));


            $patientRecord = Patient::create($request->all());

            $request->request->add(['patient_id' => $patientRecord['id']]);

            EmergencyContact::create($request->all());

            Doctor::create($request->all());

            Guarantor::create($request->all());

            Employer::create($request->all());

            MainMember::create($request->all());

            /*
             * create a new account
             */
            $this->billingService->createAccount($patientRecord['id']);


            $medic = new MedicalAid();
            $medic->fill(
                $request->all()
            );
            $medic->save();

            /*
          * avatar
          */
            $this->uploadImage($request, 'profile_pic', 'profile_pic', $patientRecord);

            DB::commit();

            return $patientRecord;
        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }

    }


    /**
     * @param $request
     * @param $id
     * @return void
     */
    public function updatePatientDetails($request, $id)
    {

        try {

            DB::beginTransaction();

            if (isset($request['date_of_birth'])) {
                $request['date_of_birth'] = str_replace('/', '-', $request['date_of_birth']);
                $request['date_of_birth'] = strtotime($request['date_of_birth']);
            }

            $patientRecord = Patient::find($id);
            $patientRecord->update($request->all());

            $EmergencyContact = EmergencyContact::where('patient_id', $id)->first();
            $EmergencyContact->fill($request->all())->save();

            $Doctor = Doctor::where('patient_id', $id)->first();
            $Doctor->fill($request->all())->save();

            $MedicalAid = MedicalAid::where('patient_id', $id)->first();
            $MedicalAid->fill($request->all())->save();

            $Guarantor = Guarantor::where('patient_id', $id)->first();
            $Guarantor->fill($request->all())->save();

            $employer = Employer::where('patient_id', $id)->first();
            $employer->fill($request->all())->save();

            $main = MainMember::where('patient_id', $id)->first();
            $main->fill($request->all())->save();

            $this->uploadImage($request, 'profile_pic', 'profile_pic', $patientRecord);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }

    }


    /**
     * @param $id
     * @return void
     */
    public function destroyPatientRecords($id)
    {

        try {
            $patient = Patient::find($id);
            $patient->delete();

            $EmergencyContact = EmergencyContact::where('patient_id', $id)->first();
            $EmergencyContact->delete();

            $Doctor = Doctor::where('patient_id', $id)->first();
            $Doctor->delete();

            $MedicalAid = MedicalAid::where('patient_id', $id)->first();
            $MedicalAid->delete();

            $Guarantor = Guarantor::where('patient_id', $id)->first();
            $Guarantor->delete();

            $employer = Employer::where('patient_id', $id)->first();
            $employer->delete();

            $main = MainMember::where('patient_id', $id)->first();
            $main->delete();

            Dependencies::where('patient_id', $id)->delete();;

            DB::commit();

        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }


    }

    /**
     * @param $patient
     * @return void
     */
    public function ManagePatient($patient)
    {
        /**
         * check if user is a guest and has  an account before activating
         * if not create a new account for them
         */
        $AccountExist = Accounts::isAccountExist($patient['id']);

        if ($patient->is_active  === 0 && $AccountExist === 0){
            Accounts::create([
                'account_number' => $this->billingService->generateAccountId(),
                'client_id' => $patient['id'],
                'account_manager' => auth()->user()->id, // not sure what to do
                'status' => 1
            ]);
        }

        $patient['is_active'] == 1 ? $status = 0 : $status = 1;
        $patient['is_active'] = $status;
        $patient->update();
    }
	// deactivate/ activate package
	public function activatePackage($package)
    {
		
		$package['status'] == 1 ? $status = 0 : $status = 1;
		$package['status'] = $status;
		$package->update();
    }


    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function persistDependencies($request){

        try {

            DB::beginTransaction();

            Packages::create([
                'package_name' => $request['package_name'],
                'no_table' => $request['no_table'],
                'status' => 1,
            ]);

            DB::commit();

        }catch (Exception $ex){
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"

            return response()->json(['message' => $ex], 400);
        }
    }
	// update package
	 public function updatePackage($request, $package){

        try {

			$package['package_name'] = !empty($request['package_name']) ? $request['package_name'] : '';
			$package['no_table'] = !empty($request['no_table']) ? $request['no_table'] : '';
			$package->update();

        }catch (Exception $ex){
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"

            return response()->json(['message' => $ex], 400);
        }
    }
	//save contact person
	public function persistContactPerson($request){

        try {

            DB::beginTransaction();

            Packages::create([
                'patient_id' => $request['patient_id'],
                'dependency_first_name' => $request['dependency_first_name'],
                'dependency_surname' => $request['dependency_surname'],
                'dependency_code' => $request['dependency_code'],
                'dependency_id_number' => $request['dependency_id_number'],
                'dependency_passport_number' => $request['dependency_passport_number'],
                'dependency_passport_origin_country_id' => $request['dependency_passport_origin_country_id'],
                'dependency_date_of_birth' => $request['dependency_date_of_birth'],
            ]);

            DB::commit();

        }catch (Exception $ex){
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"

            return response()->json(['message' => $ex], 400);
        }
    }

    public function completeGuestPatient($request, $id){
        dd($request);
    }


}
