<?php

namespace App\Services;

use App\Http\Controllers\Billing\InvoiceController;
use App\Http\Controllers\Sms\BulkSmsController;
use App\Mail\PatientsCommunicationEmail;
use App\Mail\sendBookingReminders;
use App\Models\BillingInvoice;
use App\Models\Booking;
use App\Models\BookingReminder;
use App\Models\ContactsCommunication;
use App\Models\HRPerson;
use App\Models\Patient;
use App\Models\SmsTracker;
use App\Models\User;
use App\Models\UserCode;
use App\Models\Url;
use App\Traits\CompanyIdentityTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use http\Env\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
//use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use function PHPUnit\Framework\stringStartsWith;

class CommunicationService
{

    use CompanyIdentityTrait;


    /**
     * @var Booking
     */
    private $booking;
    /**
     * @var Patient
     */
    private $patient;
    /**
     * @var User
     */
    private $user;
    /**
     * @var BulkSmsController
     */
    private $bulkSMSController;
    /**
     * @var ContactsCommunication
     */
    private $ContactsCommunication;
    /**
     * @var BillingService
     */
    private $billingService;


    /**
     * @param BulkSmsController $bulkSMSController
     * @param Booking $booking
     * @param BillingService $billingService
     * @param ContactsCommunication $ContactsCommunication
     * @param Patient $patient
     * @param User $user
     */
    public function __construct(
        BulkSMSController     $bulkSMSController,
        Booking               $booking,
        BillingService        $billingService,
        ContactsCommunication $ContactsCommunication,
        Patient               $patient,
        User                  $user
    )
    {
        $this->booking = $booking;
        $this->ContactsCommunication = $ContactsCommunication;
        $this->bulkSMSController = $bulkSMSController;
        $this->billingService = $billingService;
        $this->patient = $patient;
        $this->user = $user;

    }


    public function remindClientNotification()
    {

        $rem = BookingReminder::with('patient')->get();

        foreach ($rem as $item) {
            $now = date("Y-m-d H:i", strtotime(\Carbon\Carbon::now()->addHour()));

            if ($item->reminder_times < 1) {

                // dd("test");
//                if ($item->date("Y-m-d", strtotime($item->reminder_date)) === $now) {
//                    //send once
////              $this->dispatch()
//                };

            }


            if ($item->reminder_times > 1) {

                $date = Carbon::createFromFormat('Y-m-d H:i:s', $item->reminder_date);
                $date->addWeek($item->repeat_reminder);
                $date->format('Y-m-d H:i:s');


            }

        }

    }

    /**
     * @return void
     */
    public function NotifyUserAboutBooking()
    {

        $todayBookings = $this->booking::whereDate('start', Carbon::today())->with('patient')->get();

        $details = $this->CompanyIdentityDetails();

        foreach ($todayBookings as $bookings) {


            $bookingDetails = [
                'logo' => $details['logo'],
                'name' => $bookings->patient->first_name,
                'surname' => $bookings->patient->surname,
                'title' => $bookings->title,
                'start' => Carbon::parse($bookings->start)->format('d F, Y h:i:s A'),
                'end' => Carbon::parse($bookings->end)->format('d F, Y, h:i:s A'),
                'Notes' => $bookings->Notes
            ];


            $message = "hi" . $bookings->patient->first_name . "We would like to remind you have your appointment" .
                Carbon::parse($bookings->start)->format('d F, Y h:i:s A') .
                "Company name";

            try {

                Mail::to($bookings->patient->email)->send(new sendBookingReminders($bookingDetails));
                $num = strlen($message) / 160;
                $this->smsCounter(ceil($num) , $bookings->patient->phone_number, $message);

            } catch (\Exception $e) {
                echo $e;
            }

        }

    }


    public function sendCompanyEmailCommunication($request)
    {

        if ($request['toggle'] === 'on') {
            $emails = $this->getAllUsersEmails();
        } else {
            $emails = $request['email_address'];
        }

        $CompanyDetails = $this->CompanyIdentityDetails();

        $title = $request['subject'];
        $dt = Carbon::now();
        $time = $dt->toDayDateTimeString();

        foreach ($emails as $key => $email_address) {


            $detail = Patient::getUserDetailsByEmail($email_address);

            $body = $this->str_fix($request['details']);

            $mailData = [
                'first_name' => $detail['first_name'],
                'title' => $title,
                'body' => $request['details'],
                'time' => $time,
                'logo' => $CompanyDetails['logo'],
                'support' => $CompanyDetails['support'],
                'company_name' => $CompanyDetails['company_name'],
                'mailing_name' => $CompanyDetails['mailing_name'],
            ];

            try {
                $status = 1;
                Mail::to($email_address)->send(new PatientsCommunicationEmail($mailData));
                $this->persistCompanyCommunicationData($request, $CompanyDetails, $status, $body);
                alert()->success('SuccessAlert', 'Message Successfully Sent to Client');

            } catch (\Exception $e) {
                $status = 0;
                $this->persistCompanyCommunicationData($request, $CompanyDetails, $status);
                alert()->error('Error', 'Oops Something went Wrong, please try again later');
                return back()->with(
                    'error', 'Oops Something went Wrong, please try again later.'
                );
            }

        }
    }

    /**
     * @return Collection
     */
    private function getAllUsersEmails(): Collection
    {
        $user = collect([]);
        $UserEmails = $this->patient::select('email')->get();
        foreach ($UserEmails as $key => $users) {
            $user->push($users['email']);
        }

        return $user;
    }

    /**
     * @return Collection
     */
    private function getAllUsersPhone(): Collection
    {
        $user = collect([]);
        $UserEmails = $this->patient::select('phone_number')->get();
        foreach ($UserEmails as $key => $users) {
            $user->push($users['phone_number']);
        }
        return $user;
    }

    /**
     * @param $request
     * @return RedirectResponse|void
     */
    public function sendCompanySmsCommunication($request)
    {

        $CommunicationData = $request->all();

        unset($CommunicationData['_token']);

        if ($request['toggle_sms'] === 'on') {
            $cell_number = $this->getAllUsersPhone();
        } else {
            $cell_number = $CommunicationData['cell_number'];
        }

        $mobileArray = array();

        foreach ($cell_number as $cell_numbers) {
            $mobileArray[] = $this->formatCellNo($cell_numbers);
            $CommunicationData['sms_content'] = str_replace("<br>", "", $CommunicationData['sms_content']);
            $CommunicationData['sms_content'] = str_replace(">", "-", $CommunicationData['sms_content']);
            $CommunicationData['sms_content'] = str_replace("<", "-", $CommunicationData['sms_content']);

            $body = $CommunicationData['sms_content'];

            try {
                $status = 1;
                $num = strlen($CommunicationData['sms_content']) / 160;
                $this->persistCompanyCommunicationData($request, $CommunicationData, $status, $body);
                $this->smsCounter(ceil($num) , $mobileArray, $CommunicationData['sms_content']);
                alert()->success('SuccessAlert', 'Message Successfully Sent to Client');

            } catch (\Exception $e) {
                alert()->error('Error', 'Oops Something went Wrong, please try again later');
                return back()->with(
                    'error', 'Oops Something went Wrong, please try again later.'
                );
            }
        }
    }


    /**
     *
     */
    public function smsCounter($num , $mobileArray , $message)
    {
        $sms = SmsTracker::first();
        $counter = $sms->sms_count - $num;

        $failId = $sms->id;

        if ($sms->sms_count < 1) {
            return response()->json(['message' => 'error'], 401);
        } else {
            BulkSMSController::send($mobileArray, $message);
            SmsTracker::where("id", $failId)->update(["sms_count" => $counter]);
        }
    }


    /**
     * @param $sCellNo
     * @return array|string|string[]
     */
    private function formatCellNo($sCellNo)
    {
        # Remove the following characters from the phone number
        $cleanup_chr = array("+", " ", "(", ")", "\r", "\n", "\r\n");
        # clean phone number
        $sCellNo = str_replace($cleanup_chr, '', $sCellNo);
        #Internationalise  the number
        if ($sCellNo[0] == "0") $sCellNo = "27" . substr($sCellNo, 1);
        return $sCellNo;
    }

    /**
     * @param $request
     * @param $CompanyDetails
     * @param $status
     * @return void
     */
    private function persistCompanyCommunicationData($request, $CompanyDetails, $status, $body)
    {

        //$body =  $this->str_fix($request['details']);

        ContactsCommunication::create([
            'communication_type' => $request['communication_type'],
            'company_id' => 1,
            'message' => $body,
            'status' => $status,
            'sent_by' => 1,
            'communication_date' => strtotime(date("Y-m-d")),
        ]);

    }

    /**
     * @param $string
     * @return string
     */
    private function str_fix($string)
    {
        $end = '...';
        $new_str = strip_tags(html_entity_decode($string));
        if (mb_strwidth($string, 'UTF-8') <= 150) {
            return $new_str;
        }
        return rtrim(mb_strimwidth($new_str, 0, 150, '', 'UTF-8')) . $end;
    }

    /**
     * @return void
     */
    public function generateCode()

    {
        $code = rand(1000, 9999);

        UserCode::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['code' => $code]
        );

        $userNumber = auth()->user()->phone;

        $receiverNumber = "2FA login code is " . $code;

        try {

            $this->smsCounter(1 , $userNumber, $receiverNumber);

        } catch (Exception $e) {
//            dd($e);
            info("Error: " . $e->getMessage());

        }

    }

    /**
     * @return void
     */
    public function generateOtp()
    {

        //get user id
        $userId = session()->get('user_locked_out');

        $mobile_no = User::getUserNumber($userId);
        $mobileArray = array();
        $mobileArray[] = $this->formatCellNo($mobile_no->phone_number);;

        # User Does not Have Any Existing OTP
        $verificationCode = UserCode::where('user_id', $userId)
            ->latest()
            ->first();


        $now = Carbon::now();

        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            $otpMessage = "Your OTP is " . $verificationCode['code'];
            $this->bulkSMSController::send($mobileArray, $otpMessage);
            return $verificationCode;
        }


        $otp = rand(123456, 999999);
        // Create a New OTP
        UserCode::create([
            'user_id' => $userId,
            'code' => $otp,
            'expire_at' => Carbon::now()->addMinutes(10)
        ]);

        $otpMessage = "Ibhilimed OTP is " . $otp;

        try {
            BulkSMSController::send($mobileArray, $otpMessage);
        } catch (Exception $e) {
            dd($e->getMessage());
            info("Error: " . $e->getMessage());
        }

    }

    public function sendPatientForm($phone, $message)
    {
        $mobileArray = array();
        $mobileArray[] = $this->formatCellNo($phone);;
        $this->bulkSMSController::send($mobileArray, $message);
    }

    /**
     * @param $id
     * @return void
     */
    public function sendGuestLink($id)
    {

        $data = $this->patient::getPatientDataById($id);

        $pat = Patient::where('id', $id)->first();

//        $url = URL::temporarySignedRoute('guestPatient', now()->addHours(6), ['bid' => $data->uuid]);

        $url = $this->genarateLink($data->uuid);



        $num = strlen($url) / 160;
//                $this->persistCompanyCommunicationData($request, $CommunicationData, $status, $body);
        $this->smsCounter(ceil($num) , $data->phone_number, $url);

//        $pat->sendRegisterBookingNotification($url, $patient ,$request->note, $start_time);
        $pat->sendRegisterBookingNotification($url, $data);
    }


    public function genarateLink($patient){

        $keyUrl = Str::random(5);

        $url = url(route('guestPatient', [
            'bid' => $keyUrl,
        ], false));


        $key = $patient;
        $signature = hash_hmac('sha256', $key , 'user');
        $timestamp = time() + 60*60*6;


        Url::create([
            'title' => $keyUrl,
            'original_url' => $url,
            'signature' => $signature,
            'expires' => $timestamp,
            'shortener_url' => $url,
            'uuid' => $key,
        ]);

        return $url;
    }

}
