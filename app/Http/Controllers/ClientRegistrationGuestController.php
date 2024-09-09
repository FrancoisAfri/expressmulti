<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddDependencyRequest;
use App\Models\CompanyIdentity;
use App\Services\CommunicationService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use App\Models\ContactPerson;
use App\Http\Requests\NewClientRequest;
use App\Models\Packages;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use App\Services\ClientService;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class ClientRegistrationGuestController extends Controller
{
	use BreadCrumpTrait, CompanyIdentityTrait;

	/**
     * @var ClientService
     */
    private $clientService;

	public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // data to display on views
		$data = $this->breadcrumb(
            'Client ',
            'Client page for Client related settings',
            'patient_details',
            'Client Profile',
            'Client Details'
        );
		// views call


		$data['packages'] = Packages::getPackages();
        return view('guest.client_management')->with($data);
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
    public function store(NewClientRequest $request)
    {

        $clientID = $this->clientService->persistClientTempData($request);
        activity()->log('New Client Registration');
		//return redirect("/make-payment/$clientID");
		return redirect("/create-new-tenant/$clientID");
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
}
