<?php

namespace App\Http\Controllers\SetUp;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Sms\BulkSmsController;
use App\Mail\PatientsCommunicationEmail;
use App\Models\CompanyIdentity;
use App\Models\HRPerson;
use App\Models\Patient;
use App\Models\ContactPerson;
use App\Models\SmS_Configuration;
use App\Models\SmsTracker;
use App\Notifications\SmsAddedNotification;
use App\Services\CommunicationService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class ContactControllere extends Controller
{
    use BreadCrumpTrait, CompanyIdentityTrait;

    /**
     * @var CommunicationService
     */
    private $communicationService;

    public function __construct(CommunicationService $communicationService)
    {
        $this->communicationService = $communicationService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $data = $this->breadcrumb(
            'Settings Modules',
            'Admin page for security related settings',
            'manage',
            'Settings',
            'Manage Users'
        );
        $data['SmSConfiguration'] = SmS_Configuration::first();
        $data['SmsTracker'] = SmsTracker::all();
        return view('patients.sms_settings')->with($data);
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

         SmS_Configuration::updateOrCreate(
            [
                'id' => $request->get('user_id'),
            ],
            [
                'sms_provider' => $request['sms_provider'],
                'sms_username' => $request['sms_username'],
                'sms_password' => $request['sms_password'],

            ]
        );

        alert()->success('SuccessAlert', 'Details Successfully updated');

        return back();
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

    /**
     * @return Application|Factory|View
     */
    public function sendMessages()
    {

        $data = $this->breadcrumb(
            'Communication Modules',
            'Admin page for security related settings',
            'send-message',
            'Communication',
            'Send Manage'
        );

        //$data['SmSConfiguration'] = SmS_Configuration::first();
       // $data['SmsTracker'] = SmsTracker::first();
        $data['contacts'] = ContactPerson::getContactInfo();
        return view('client.send_message')->with($data);
    }


    public function addPatientSms(Request $request)
    {

        $validator = validator($request->all(), [
            'sms_count' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // check if sms tracker have been added already
        $smsTracker = SmsTracker::where('is_active', '=', 1)->first();
        if (empty($smsTracker->sms_count)) {
            // add sms
            SmsTracker::create([
                'sms_count' => $request['sms_count'],
                'is_active' => 1,
            ]);
        } else {
            $oldCount = $smsTracker->sms_count;
            // update sms
            $smsTracker->sms_count = $oldCount + $request['sms_count'];
            $smsTracker->update();
        }

        $users = collect([
            [
                'name' => 'accounts',
                'email' => 'accounts@ibhilimed.co.za',
            ],
            [
                'name' => 'info',
                'email' => 'info@ibhilimed.co.za'
            ], [
                'name' => 'francois',
                'email' => 'info@mkhayamk.co.za'
            ],
        ]);

        // send email
//        $this->notify(new SmsAddedNotification());
        foreach ($users as $user) {
            Notification::route('mail', $user['email'])->notify(new SmsAddedNotification());
        }

        alert()->success('SuccessAlert', 'Record Created Successfully');
        return response()->json(['message' => 'success'], 200);
    }

    public function editPatientSms(Request $request)
    {

        $Module = SmsTracker::find($request['formId']);
        $Module->update($request->all());
        // send email about sms update
        $users = collect([
            [
                'name' => 'accounts',
                'email' => 'accounts@ibhilimed.co.za',
            ],
            [
                'name' => 'info',
                'email' => 'info@ibhilimed.co.za'
            ], [
                'name' => 'francois',
                'email' => 'info@mkhayamk.co.za'
            ],
        ]);

        // send email
//        $this->notify(new SmsAddedNotification());
        foreach ($users as $user) {
            Notification::route('mail', $user['email'])->notify(new SmsAddedNotification());
        }
        Alert::toast('Record Updated Successfully ', 'success');
        return response()->json();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function sendEmailCommunication(Request $request): RedirectResponse
    {
        $this->communicationService->sendCompanyEmailCommunication($request);
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse|void
     * @throws ValidationException
     */
    public function sendSmsMessages(Request $request)
    {
        $this->communicationService->sendCompanySmsCommunication($request);
        return back();
    }

}
