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
use RealRashid\SweetAlert\Facades\Alert;

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
			// tenant database configuration
			$tenantDatabaseConfig = [
				'driver'    => 'pgsql',
				'host'      => env('DB_HOST', '127.0.0.1'),
				'database'  => $currentDatabaseName,
				'username'  => env('DB_USERNAME'),
				'password'  => env('DB_PASSWORD'),
				'charset' => 'utf8',
				'prefix' => '',
				'prefix_indexes' => true,
				'schema' => 'public',
				'sslmode' => 'prefer',
			];
			// Temporarily switch to another database
			$DB_HOST = env('DB_HOST');
			$DB_PORT = env('DB_PORT');
			$DB_DATABASE = env('DB_DATABASE');
			$DB_USERNAME = env('DB_USERNAME');
			$DB_PASSWORD = env('DB_PASSWORD');

			\Config::set('database.connections.'.$DB_DATABASE, [
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
			$currentDatabaseNames = DB::connection()->getDatabaseName();

			// get detail
			$newClient = Patient::where('email',$client->email)->first();

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
			\Config::set("database.connections.$currentDatabaseName", $tenantDatabaseConfig);
			DB::purge($currentDatabaseName);
			DB::reconnect($currentDatabaseName);
			DB::setDefaultConnection($currentDatabaseName);
		}
		alert()->success('SuccessAlert', 'Company Details Updated Successfully');
		activity()->log('Company Updated');
		return back();
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
		/*  @if( $client->payment_status == 1  ??  '')
			@if( $client->packages->package_type == 1  ??  '')
				<div class="text-lg-right">
					<a href="https://payf.st/yfx5i" target="_blank">
						<button type="button" class="btn btn-block btn-lg btn-danger waves-effect waves-light">
							<i class="mdi mdi-basket mr-1"></i> Subcribe (Monthy Subcription)
						</button>
					</a>
				</div>
			@else 
				<div class="text-lg-right">
					<a href="https://payf.st/yfx5i" target="_blank">
						<button type="button" class="btn btn-block btn-lg btn-danger waves-effect waves-light">
							<i class="mdi mdi-basket mr-1"></i> Subscribe (Yearly Subscription)
						</button>
					</a>
				</div>
			@endif
		@endif   */
        $data =  $this->companyService->renderEditCompanyPage();
		//return $data;
        return view('security.client.edit_client_management')->with($data);

    }
	public function cancelSubscription()
    {
        alert()->success('SuccessAlert', 'Your subscription was successful. Email confirmatin have been sent to you!!');
		return redirect("/users/view_company_details");

    } 
	public function SuccessSubscription()
    {
        alert()->success('SuccessAlert', 'Your subscription was successful. Email confirmatin have been sent to you!!');
		return redirect("/users/view_company_details");

    } 
	public function notifySubscription()
    {
        $data =  $this->companyService->renderEditCompanyPage();
		//return $data;
        return view('security.client.edit_client_management')->with($data);

    }
}
