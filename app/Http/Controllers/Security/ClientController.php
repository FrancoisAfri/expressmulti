<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\Packages;
use App\Models\Patient;
use App\Models\ContactPerson;
use App\Services\CompanyService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    use BreadCrumpTrait, CompanyIdentityTrait;


    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        $data = $this->breadcrumb(
            'Client ',
            'Client page for Client related settings',
            'patient_details',
            'Client Profile',
            'Client Details'
        );
        // views call


        $data['packages'] = Packages::getPackages();
        return view('security.client.client')->with($data);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
    }

    public function editCompanyDetails(Request $request, Patient $client)
    {
		//die('ddd');
		//get central domain
		$centralDomains = env('CENTRAL_DOMAINS');
		// get host url
		$host = request()->getHost();
		if ($host === $centralDomains)
		{
			// Main system
			$client->update($request->all());
			// save contact person
			$client->load('contacts');
			$contact = ContactPerson::find($client->contacts->id);
			$contact->first_name = !empty($request->first_name) ? $request->first_name :'';
			$contact->surname = !empty($request->surname) ? $request->surname :'';
			$contact->email = !empty($request->email) ? $request->email :'';
			$contact->contact_number = !empty($request->contact_number) ? $request->contact_number : '';
			$contact->update();

		}
		else
		{
			// Likely a tenant
			$client->update($request->all());
			// save contact person
			$client->load('contacts');
			$contact = ContactPerson::find($client->contacts->id);
			$contact->first_name = !empty($request->first_name) ? $request->first_name :'';
			$contact->surname = !empty($request->surname) ? $request->surname :'';
			$contact->email = !empty($request->email) ? $request->email :'';
			$contact->contact_number = !empty($request->contact_number) ? $request->contact_number : '';
			$contact->update();
			// connect to main database to change
			$currentDatabaseName = DB::connection()->getDatabaseName();

			// Temporarily switch to another database
			$DB_HOST = env('DB_HOST');
			$DB_PORT = env('DB_PORT');
			$DB_DATABASE = env('DB_DATABASE');
			$DB_USERNAME = env('DB_USERNAME');
			$DB_PASSWORD = env('DB_PASSWORD');

			Config::set('database.connections.'.$DB_DATABASE, [
				'driver'    => env('DB_CONNECTION'),
				'host'      => env('DB_HOST'),
				'database'  => env('DB_DATABASE'),
				'username'  => env('DB_USERNAME'),
				'password'  => env('DB_PASSWORD'),
			]);

			DB::purge($DB_DATABASE);
			DB::reconnect($DB_DATABASE);

			// Set the connection to the other database
			DB::setDefaultConnection($DB_DATABASE);
			// get detail
			$newClient = Patient::where('database_name',$client->database_name)->first();
			$newClient->update($request->all());
			$newClient->load('contacts');
			$newContact = ContactPerson::find($newClient->contacts->id);
			$newContact->first_name = !empty($request->first_name) ? $request->first_name :'';
			$newContact->surname = !empty($request->surname) ? $request->surname :'';
			$newContact->email = !empty($request->email) ? $request->email :'';
			$newContact->contact_number = !empty($request->contact_number) ? $request->contact_number : '';
			$newContact->update();

			// disconnect database
			DB::disconnect($DB_DATABASE);
			// reconnect to database
			DB::purge($currentDatabaseName);
			DB::reconnect($currentDatabaseName);
			DB::setDefaultConnection($currentDatabaseName);

		}

		return back();
        //logic
        //open connection to db
        // save details
        //close connection
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function editCompany()
    {
        $data =  $this->companyService->renderEditCompanyPage();
        return view('security.client.edit_client_management')->with($data);

    }
}
