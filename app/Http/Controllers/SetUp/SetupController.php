<?php

namespace App\Http\Controllers\SetUp;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyIdentityRequest;
use App\Models\CompanyIdentity;
use App\Models\Country;
use App\Models\PublicHolidays;
use App\Services\CompanyIdentityService;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    use BreadCrumpTrait;

    /**
     * @var CompanyIdentityService
     */
    private $companyIdentityService;

    public function __construct(CompanyIdentityService $companyIdentityService)
    {
        $this->companyIdentityService = $companyIdentityService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->breadcrumb(
            'Settings Modules',
            'Admin page for security related settings',
            'setup',
            'Settings',
            'Company Identity'
        );
		// get central domain
        $centralDomains = env('CENTRAL_DOMAINS');
        // get host url
        $host = request()->getHost();

        if ($host === $centralDomains) $showFields = 1;
		else $showFields = 2;
        $data['showFields'] = $showFields;
        $data['companyDetails'] = $this->companyIdentityService->ViewComponyIdenties();
        return view('security.company-identity.index')->with($data);
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
     * @param CompanyIdentityRequest $request
     * @param CompanyIdentityService $companyIdentityService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CompanyIdentityRequest $request)
    {
        $this->companyIdentityService->createOrUpdateCompanyIdentity($request);
        alert()->success('SuccessAlert', 'Your changes have been saved successfully');
        return back()
            ->with('message', 'User created successfully.');
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

    public function publicHolidaysIndex()
    {
        $data = $this->breadcrumb(
            'Settings Modules',
            'Admin page for security related settings',
            'public-holiday',
            'Settings',
            'Public holiday'
        );

        $data['countries'] = Country::getAllCountriesByName();
        $data['holidays'] = PublicHolidays::getAllHolidays();
        return view('security.holidays.index')->with($data);

    }

    public function createPublicHoliday(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required',
            'day' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->companyIdentityService->persistHolidays($request);
        alert()->success('SuccessAlert', 'Your changes have been saved successfully');
        return response()->json(['message' => 'success'], 200);
    }

    public function editPublicHoliday(Request $request,  $holiday)
    {
        $validator = validator($request->all(), [
            'name' => 'required',
            'day' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->companyIdentityService->editHolidays($request , $holiday);
        alert()->success('SuccessAlert', 'Your changes have been saved successfully');
        return response()->json(['message' => 'success'], 200);
    }

    public function destroyHoliday(PublicHolidays $holiday){

        $holiday->delete();
        alert()->success('SuccessAlert', 'Your changes have been saved successfully');
        return redirect()->back();
    }
}
