<?php

namespace App\Services;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Artisan;
use App\Traits\CompanyIdentityTrait;
use App\Models\Accounts;
use App\Models\Packages;
use App\Models\ContactPerson;
use App\Models\ContactPersonTemp;
use App\Models\Dependencies;
use App\Models\Doctor;
use App\Models\EmergencyContact;
use App\Models\Employer;
use App\Models\User;
use App\Models\PasswordHistory;
use App\Models\PasswordSecurity;
use App\Models\HRPerson;
use App\Models\Guarantor;
use App\Models\MainMember;
use App\Models\Tenant;
use App\Models\Patient;
use App\Models\Companies_temp;
use App\Mail\ClientApplicationApproval;
use App\Mail\SendDebitOrderForm;
//use App\Services\BillingService;
use App\Services\Modules\ModuleService;
use App\Traits\FileUpload;
//use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Exception;
//use Stancl\Tenancy\Tenant;
use Illuminate\Support\Str;

class ClientService
{
    use FileUpload, CompanyIdentityTrait;
    /**
     * @var \App\Services\BillingService
     */
    private $moduleService;
	
    public function __construct(ModuleService $moduleService)
	{
        $this->moduleService = $moduleService;
    }



    /**
     * @param $request
     * @return void
     */
    public function persistClientData($request)
    {
		$contactNumber = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('contact_number'));
		$cellNumber = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('cell_number'));
		$mobile = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('phone_number'));
		$request->merge(array('phone_number' => $mobile));
		$request->merge(array('cell_number' => $cellNumber));
		$request->merge(array('contact_number' => $contactNumber));

		$patientRecord = Patient::create($request->all());

		$request->request->add(['company_id' => $patientRecord['id']]);

		// save contact person
		ContactPerson::create([
			'company_id' => $patientRecord->id,
			'first_name' => $request['first_name'],
			'surname' => $request['surname'],
			'contact_number' => $request['contact_number'],
			'email' => $request['contact_email'],
			'status' => 1
		]);
		// save logo
		if ($request->hasFile('client_logo')) {
            $fileExt = $request->file('client_logo')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('client_logo')->isValid()) {
                $fileName = time() . "client_logo." . $fileExt;
                $request->file('client_logo')->storeAs('Images/client_logo/', $fileName);
                //Update file name in the database
                $patientRecord->client_logo = $fileName;
                $patientRecord->update();
            }
        }
		//$this->uploadImage($request, 'client_logo', 'client_logo', $patientRecord);

		/*
		 * create a new database
		 */
		// make db name
		$name = str_replace(' ', '', $patientRecord['name']);
		$name = strtolower($name);

		$url = $this->createTenant($name, $request['first_name'], $request['surname'], $request['contact_email'], $contactNumber);

		// update database name in the system
		$patientRecord['database_name'] = $url;
		$patientRecord->update();

		return $url;
    }

	/**
     * @param $request
     * @return void
     */
    public function persistClientTempData($request)
    {
		$contactNumber = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('contact_number'));
		$cellNumber = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('cell_number'));
		$mobile = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('phone_number'));
		$request->merge(array('phone_number' => $mobile));
		$request->merge(array('cell_number' => $cellNumber));
		$request->merge(array('contact_number' => $contactNumber));

		$patientRecord = Companies_temp::create($request->all());

		$request->request->add(['company_id' => $patientRecord['id']]);

		// save contact person
		ContactPersonTemp::create([
			'company_id' => $patientRecord->id,
			'first_name' => $request['first_name'],
			'surname' => $request['surname'],
			'contact_number' => $request['contact_number'],
			'email' => $request['contact_email'],
			'status' => 1
		]);
		//  send email to admin 
		$companyDetails = $this->CompanyIdentityDetails();
        $companyEmail = !empty($companyDetails['admin_email']) ? $companyDetails['admin_email'] : '';
		if (!empty($companyEmail))
			Mail::to($companyEmail)->send(new ClientApplicationApproval());
		// send debit order form to client
		if (!empty($request['contact_email']))
			Mail::to($request['contact_email'])->send(new SendDebitOrderForm($request['first_name']));
		
		
		return $patientRecord->id;
    }

	public function persistClient($companyID)
    {
        $clientRecord = $this->SaveCompany($companyID);
        /*
         * create a new database
         */
		// make db name
		$name = str_replace(' ', '', $clientRecord['name']);
		$name = strtolower($name);

		$url = $this->createTenant($name, $clientRecord);

		// update database name in the system
		$clientRecord['database_name'] = $name;
		$clientRecord['tenant_url'] = $url;
		$clientRecord->update();
		
		//delete temp company and contact person
		
		//$company = Companies_temp::find($companyID);
		//$contactTemp = ContactPersonTemp::where('company_id',$company->id)->first();

		return $url;
    }
    /**
     * @param $request
     * @param $id
     * @return void
     */
    public function updateClientDetails($request, $id)
    {
        try {

            DB::beginTransaction();

            $patientRecord = Patient::find($id);
            $patientRecord->update($request->all());

            $this->uploadImage($request, 'client_logo', 'client_logo', $patientRecord);

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

            $contact = ContactPerson::where('company_id', $id)->first();
            $contact->delete();

            DB::commit();

        } catch (\Exception $exp) {
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
        }
    }

    /**
     * @param $patient
     * @return void
     */
   	
	public function activeClient($client)
	{
		try {
			// Toggle is_active
			$client->is_active = $client->is_active == 1 ? 0 : 1;
			$client->update();

			// Store main connection
			$currentConnection = DB::getDefaultConnection();
			$currentDatabase = DB::connection($currentConnection)->getDatabaseName();

			$tenantDatabase = $client->database_name ?? '';

			if (!empty($tenantDatabase)) {
				$tenantConnectionName = 'tenant_connection';

				// Configure tenant DB
				Config::set("database.connections.$tenantConnectionName", [
					'driver'   => 'pgsql',
					'host'     => env('DB_HOST', '127.0.0.1'),
					'port'     => env('DB_PORT', '5432'),
					'database' => $tenantDatabase,
					'username' => env('DB_USERNAME'),
					'password' => env('DB_PASSWORD'),
					'charset'  => 'utf8',
					'prefix'   => '',
					'schema'   => 'public',
					'sslmode'  => 'prefer',
				]);

				// Switch to tenant
				DB::purge($tenantConnectionName);
				DB::reconnect($tenantConnectionName);
				DB::setDefaultConnection($tenantConnectionName);

				// Perform operation in tenant DB
				$newClient = \App\Models\Patient::where('email', $client->email)->first();

				if ($newClient) {
					$newClient->payment_status = 1;
					$newClient->save();
				}

				// Restore connection
				DB::disconnect($tenantConnectionName);
				DB::setDefaultConnection($currentConnection);
				DB::reconnect($currentConnection);
			}
		} catch (\Exception $e) {
			Log::error('Error toggling client status: ' . $e->getMessage());
			// Optional: throw or handle error gracefully
		}
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
    public function persistPackages($request){

        try {

            DB::beginTransaction();

            Packages::create([
                'package_name' => $request['package_name'],
                'no_table' => $request['no_table'],
                'price' => $request['price'],
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
			$package['price'] = !empty($request['price']) ? $request['price'] : '';
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

			$contactNumber = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request['contact_number']);

			ContactPerson::create([
			'company_id' => $request['company_id'],
			'first_name' => $request['first_name'],
			'surname' => $request['surname'],
			'contact_number' => $contactNumber,
			'email' => $request['email'],
			'status' => 1
			]);

            DB::commit();

        }
		catch (Exception $ex){
            DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"

            return response()->json(['message' => $ex], 400);
        }
    }

    public function completeGuestPatient($request, $id){
        dd($request);
    }

	/// migrate database for new system
	/*public function migrate($outputLog)
    {
        //try {
            Artisan::call('migrate', ["--force"=> true], $outputLog);
        //} catch(Exception $e){
           // return $this->response($e->getMessage(), 'error', $outputLog);
        //}
        return $this->seed($outputLog);
    }

	// run seed folder after migration
	public function seed($outputLog)
    {
        //try {
            // Artisan::call('db:seed', ['--force' => true], $outputLog);
            Artisan::call('db:seed', array_filter([
                '--class' => 'Database\\Seeders\\DatabaseSeeder',
                '--force' => true,
            ]));
        //} catch(Exception $e) {
            //return $this->response($e->getMessage(), 'error', $outputLog);
        //}
        return $this->response('Installation finished', 'success', $outputLog);
    }*/
	// create a nee database

	public function createDB($dbname)
	{

		$outputLog = '';
		// create new database
		DB::statement("CREATE DATABASE $dbname");

		// connect to the new database
		config(['database.connections.pgsql.database' => $dbname]);
		DB::reconnect('pgsql');
		// call migration function
		Artisan::call('migrate', ["--force"=> true], $outputLog);

		// run seed
		Artisan::call('db:seed', array_filter([
			'--class' => 'Database\\Seeders\\DatabaseSeeder',
			'--force' => true,
		]));
		// reconnect to the master database
		config(['database.connections.pgsql.database' => 'expressdb']);
		DB::reconnect('pgsql');
		return $dbname;
	}
	// create new tenant
	public function createTenant($dbname, $clientRecord)
	{
		$centralDomains = env('CENTRAL_DOMAINS');
		$tenant_id = Str::slug($dbname, '');

		// save tenant and domain
		$tenant = Tenant::create([
            'id' => $tenant_id
        ]);

        $domain = $tenant['id'] . '.' . $centralDomains;
        $tenant->createDomain([
            'domain' => $domain
        ]);
		// run migration
        $this->tenantMigrate($tenant['id']);
        // seed
        $this->tenantSeed($tenant['id']);

        $random_pass = Str::random(10);
        $password = Hash::make($random_pass);
		// get contact record
		$contacts = ContactPerson::where('company_id',$clientRecord->id)->first();

        $tenant->run(function() use ($clientRecord, $contacts, $password, $random_pass, $domain)
        {
			// save company
			$client = $this->SaveCompanyTenant($clientRecord);

            $user = $this->SaveUser($contacts->first_name, $contacts->email, $password, $contacts->contact_number);

            $this->SaveHr($contacts->first_name, $contacts->surname, $contacts->email, $contacts->contact_number, $user);

            PasswordHistory::createPassword($user->id ,$password);
			//Assign roles
			$user->assignRole(5);
			PasswordSecurity::addExpiryDate($user->id);
			// save rights 
			$this->moduleService::giveUserAccessAllModules($user->id, 4);
			// send email
			$forgotPassword =  new ForgotPasswordController();
			$forgotPassword->sendResetEmail($user->email , $random_pass ,$user,$domain);
        });

        tenancy()->initialize($tenant);

		return $domain;

	}
	// tenant code from the net
	/*public function store(Request $request)
    {
        $this->validate($request, [
            'company' => 'required',
            'domain' => 'required|unique:domains',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirm'
        ]);
        $tenant_id = '-' . Str::slug($request->company, '-');
        $domain = $request->domain . '.' . 'saas.test';

        $tenant = Tenant::create([
            'id' => $tenant_id
        ]);


        $tenant->createDomain([
            'domain' => $domain
        ]);
		// run migration
		Artisan::call(‘tenants:migrate’);

		Artisan::call('tenants:migrate', [
			'--tenants' => [$tenant['id']]
		]);
		// seed
		Artisan::call('tenants:seed', [
			'--tenants' => [$tenant['id']]
		]);

        $tenant->run(function()
        {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        });

        tenancy()->initialize($tenant);

        return redirect($domain);
    }*/
    /**
     * @param $id
     * @return void
     */
    public function tenantMigrate($id): void
    {
        Artisan::call('tenants:migrate', [
            '--tenants' => [$id]
        ]);
    }

    /**
     * @param $id
     * @return void
     */
    public function tenantSeed($id): void
    {
        Artisan::call('tenants:seed', [
            '--tenants' => [$id]
        ]);
    }

    /**
     * @param $firstname
     * @param $surname
     * @param $email
     * @param $contactNumber
     * @param $user
     * @return void
     */
    function SaveHr($firstname, $surname, $email, $contactNumber, $user): void
    {
        $person = new HRPerson();
        $person->first_name = $firstname;
        $person->surname = $surname;
        $person->email = $email;
        $person->cell_number = $contactNumber;
        $person->status = 1;
        $user->addPerson($person);
    }

    /**
     * @param $firstname
     * @param $email
     * @param string $password
     * @param $contactNumber
     * @return mixed
     */
    function SaveUser($firstname, $email, string $password, $contactNumber)
    {
        $user = User::create([
            'name' => $firstname,
            'email' => $email,
            'password' => $password,
            'phone_number' => $contactNumber,
            'lockout_time' => 10,
            'type' => 0,
            'status' => 1,
        ]);
        return $user;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $phone_number
     * @param string $cell_number
     * @param string $res_address
     * @param string $post_address
     * @param int $package_id
     * @param string $trading_as
     * @param string $vat
     * @return mixed
     */
    public function SaveCompany($companyID)
    {
		
		$company = Companies_temp::find($companyID);
		$contactTemp = ContactPersonTemp::where('company_id',$company->id)->first();
		
		// get details from model
		$name = !empty($company->name) ? $company->name : '';
		$email = !empty($company->email) ? $company->email : '';
		$cell_number = !empty($company->cell_number) ? $company->cell_number : '';
		$phone_number = !empty($company->phone_number) ? $company->phone_number : '';
		$res_address = !empty($company->res_address) ? $company->res_address : '';
		$post_address = !empty($company->post_address) ? $company->post_address : '';
		$package_id = !empty($company->package_id) ? $company->package_id : 0;
		$trading_as = !empty($company->trading_as) ? $company->trading_as : '';
		$vat = !empty($company->vat) ? $company->vat : '';
		// assign variable
		$first_name = !empty($contactTemp->first_name) ? $contactTemp->first_name : '';
		$surname = !empty($contactTemp->surname) ? $contactTemp->surname : '';
		$contact_number = !empty($contactTemp->contact_number) ? $contactTemp->contact_number : '';
		$contact_email = !empty($contactTemp->email) ? $contactTemp->email : '';
		
		// save company
        $clientRecord = Patient::create([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'contact_number' => $cell_number,
            'res_address' => $res_address,
            'post_address' => $post_address,
            'date_joined' => time(),
            'payment_status' => 1,
            'package_id' => $package_id,
            'trading_as' => $trading_as,
            'vat' => $vat,
            'is_active' => 1
        ]);
		
		// save comtact person
        ContactPerson::create([
            'company_id' => $clientRecord->id,
            'first_name' => $first_name,
            'surname' => $surname,
            'contact_number' => $contact_number,
            'email' => $contact_email,
            'status' => 1
        ]);
		$company->delete();
		$contactTemp->delete();
        return $clientRecord;
    } 
	// save company and contact in tenant record
	public function SaveCompanyTenant($clientRecord)
    {
		
		// get details from model
		$name = !empty($clientRecord->name) ? $clientRecord->name : '';
		$email = !empty($clientRecord->email) ? $clientRecord->email : '';
		$cell_number = !empty($clientRecord->cell_number) ? $clientRecord->cell_number : '';
		$phone_number = !empty($clientRecord->phone_number) ? $clientRecord->phone_number : '';
		$res_address = !empty($clientRecord->res_address) ? $clientRecord->res_address : '';
		$post_address = !empty($clientRecord->post_address) ? $clientRecord->post_address : '';
		$package_id = !empty($clientRecord->package_id) ? $clientRecord->package_id : 0;
		$trading_as = !empty($clientRecord->trading_as) ? $clientRecord->trading_as : '';
		$vat = !empty($clientRecord->vat) ? $clientRecord->vat : '';
		// assign variable
		$first_name = !empty($clientRecord->contacts->first_name) ? $clientRecord->contacts->first_name : '';
		$surname = !empty($clientRecord->contacts->surname) ? $clientRecord->contacts->surname : '';
		$contact_number = !empty($clientRecord->contacts->contact_number) ? $clientRecord->contacts->contact_number : '';
		$contact_email = !empty($clientRecord->contacts->email) ? $clientRecord->contacts->email : '';
		
		// save company
        $record = Patient::create([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'contact_number' => $cell_number,
            'res_address' => $res_address,
            'post_address' => $post_address,
            'date_joined' => time(),
            'payment_status' => 1,
            'package_id' => $package_id,
            'trading_as' => $trading_as,
            'vat' => $vat,
            'is_active' => 1
        ]);
		
		// save comtact person
        ContactPerson::create([
            'company_id' => $record->id,
            'first_name' => $first_name,
            'surname' => $surname,
            'contact_number' => $contact_number,
            'email' => $contact_email,
            'status' => 1
        ]);
        return $record;
    }
	// approve company
	public function approveClient($client)
    {
        $url = $this->persistClient($client->id);
		return $url;
    }
	// decline company
	public function declineClient($client)
    {
		$company = Companies_temp::find($client->id);
		$contactTemp = ContactPersonTemp::where('company_id',$company->id)->first();
		//$company->delete();
		//$contactTemp->delete();
		return 1;
    }
}
