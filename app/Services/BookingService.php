<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingNotification;
use App\Models\Doctor;
use App\Models\EmergencyContact;
use App\Models\Employer;
use App\Models\Guarantor;
use App\Models\MainMember;
use App\Models\MedicalAid;
use App\Models\modules;
use App\Models\Patient;
use App\Models\User;
use App\Models\Url;
use App\Notifications\regitserPatientNotification;
use App\Traits\createNotificationTrait;
use App\Traits\CompanyIdentityTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

//use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Array_;
use function PHPUnit\Framework\isFalse;

class BookingService
{

    use createNotificationTrait, CompanyIdentityTrait;

    /**
     * @var CommunicationService
     */
    private $communicationService;
    /**
     * @var Patient
     */
    private $patient;
    /**
     * @var Booking
     */
    private $booking;

    public function __construct(
        Booking              $booking,
        CommunicationService $communicationService,
        Patient              $patient
    )
    {
        $this->booking = $booking;
        $this->communicationService = $communicationService;
        $this->patient = $patient;
    }

    /**
     * @return JsonResponse
     */
    public function getAllBookings()
    {
        $bookings = $this->booking::getAllBookings();


        if (count($bookings) < 1) {
            return response()->json([
                'message' => 'No data found'
            ],
                404);
        }

        return $bookings;
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function getbookingsById($id)
    {
        $bookings = $this->booking::getBookingById($id);

        if (isset($bookings) === false) {
            return response()->json([
                'message' => 'No data found'
            ],
                404);
        }

        return $bookings;
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function persistBookingData($request)
    {
		// call company identity trails
		$companyDetails = $this->CompanyIdentityDetails();
        if ($request['classPatient'] == Null) {

            $patient = Patient::create(
                [
                    'first_name' => $request->first_name,
                    'surname' => $request->surname,
                    'phone_number' => $request->cell_number,
                    'email' => $request->email,
                    'is_active' => 0,
                    'is_accepted' => 0,
                ]
            );

            $request->request->add(['patient_id' => $patient['id']]);

            EmergencyContact::create($request->all());

            Doctor::create($request->all());

            Guarantor::create($request->all());

            Employer::create($request->all());

            MainMember::create($request->all());

            MedicalAid::create($request->all());

            $pat = Patient::getDetailsById($patient->id);

            $url =  $this->genarateLink($patient->uuid);

            $start_time = Carbon::parse($request->start)->format('l jS \of F Y h:i:s A');

            //communication
            $pat->sendRegisterBookingNotification($url, $patient ,$request->note, $start_time);

            $this->sendBookingSms($companyDetails,$request,$url,$patient);

            return $booking = $this->saveBooking($request, $patient->id, $request->end);
        } else {

			// send sms to confirm booking$url
            $pat = Patient::where('id',  $request->classPatient)->first();;

			$message = "Appointment Confirmed with " . $companyDetails['company_name']." " .
                Carbon::parse($request->start)->format('d/m/y h:i A') .
                " at ".$companyDetails['hospital'];


            $start_time = Carbon::parse($request->start)->format('l jS \of F Y h:i:s A');
            $pat->sendBookingNotification($pat ,$request->note, $start_time);

            $this->communicationService->sendPatientForm($pat->phone_number, $message);

            return $booking = $this->saveBooking($request, $request->classPatient, $request->end);
        }
    }



    public function sendBookingSms($companyDetails,$request,$url,$patient ){
		
        $message = $url." Appointment Confirmed with " .$companyDetails['company_name']." " .
            Carbon::parse($request->start)->format('d/m/y h:i A') .
            " at ".$companyDetails['hospital']." click on the link to complete your profile";

        $this->communicationService->sendPatientForm($request->cell_number, $message);

        $this->persistNotification(
            1,
            $patient->first_name,
            $patient->email,
            'New Profile Registration',
            $patient->first_name . ' has registered a new profile',
            get_class($patient),
            $patient->id,
            $url
        );
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

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function editBooking(Request $request, $id)
    {

//        dd($request);
        $booking = $this->booking::find($id);
        $booking->update($request->all());
        return $booking;
    }

    public function createOrUpdateBooking($request)
    {

        if ($request->allDay === 'true') {
            $end = $request->start;
        } else
            $end = $request->end;

        $id = $request->userId;


        if ($id === null) {
            return $this->saveBooking($request, $request->classPatient, $end);

        } else {

            $booking = $this->booking::find($id);

            if ($request['Datetoggler']  === 'no'){
                $booking->update(
                    [
                        'cell_number' => $request->cell_number,
                        'className' => $request->className,
                        'end' => $end,
                        'email' => $request->email,
                        'first_name' => $request->first_name,
                        'patient_id' => $request->classPatient,
                        'Notes' => $request->note,
                        'surname' => $request->surname,
                        'title' => $request->title,
                        'start' => $request->start,
                    ]
                );
            }else{
                $booking->update(
                    [
                        'cell_number' => $request->cell_number,
                        'className' => $request->className,
                        'start' => $request->date_time_start,
                        'end' => $request->date_time_end,
                        'email' => $request->email,
                        'first_name' => $request->first_name,
                        'patient_id' => $request->classPatient,
                        'Notes' => $request->note,
                        'surname' => $request->surname,
                        'title' => $request->title,
                    ]
                );
            }

            return $booking;
        }


    }

    /**
     * @param $request
     * @return false|string
     */
    private function removeDay($request)
    {
        $originalDate = $request;
        $date = date_create($originalDate);
        $subtractDate = date_sub($date, date_interval_create_from_date_string(1 . " days"));
        return date_format($subtractDate, 'Y-m-d');
    }


    /**
     * @param $request
     * @param $patientId
     * @param $endDate
     * @return void
     */
    private function saveBooking($request, $patientId, $end)
    {

       return $this->booking::create([
            'cell_number' => $request->cell_number,
            'className' => $request->className,
            'end' => $end,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'patient_id' => $patientId,
            'Notes' => $request->note,
            'surname' => $request->surname,
            'title' => $request->title,
            'start' => $request->start,
        ]);
    }

    /**
     * @param $request
     * @return string
     */
    private function alignTimewithUtc($request )
    {
        $day = $request;
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $day);
        $date->addHours(2); // Subtracts 1 day
        return $date->format('Y-m-d H:i:s');
    }
	/// calculate date and time
	public function convertDate($checktime)
    {
		//check_in_time
		if (!empty($checktime))
		{
			$from_time = strtotime($checktime);
			$to_time = time();
			$diff_minutes = round(abs($from_time - $to_time) / 60,2);
			if ($diff_minutes > 60)
			{
				$hours = floor($diff_minutes/60);
				$minutes = floor($diff_minutes - ($hours*60));
				$waitingTime = $hours."h".$minutes."minutes";
			}
			else
			{
				$hours = 0;
				$minutes = $diff_minutes;
				$waitingTime = $minutes." minutes";
			}
		}
		else $waitingTime = '';
		return $waitingTime;
    }
}
