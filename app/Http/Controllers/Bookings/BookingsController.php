<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Mail\sendBookingReminders;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Patient;
use App\Models\User;
use App\Services\BookingService;
use App\Services\CommunicationService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use App\Traits\createNotificationTrait;
use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use MongoDB\Driver\Session;
use RealRashid\SweetAlert\Facades\Alert;
use function Symfony\Component\String\b;

class BookingsController extends Controller
{
    use BreadCrumpTrait, CompanyIdentityTrait ;

    /**
     * @var BookingService
     */
    private $bookingService;
    /**
     * @var Booking
     */
    private $booking;

    /**
     * @param BookingService $bookingService
     */
    public function __construct(
        BookingService $bookingService,
        Booking        $booking
    )
    {
        $this->booking = $booking;
        $this->bookingService = $bookingService;
//        $this -> middleware('role:Admin');
//        $this -> middleware(['role:Reception']);
        $this->middleware('role:Admin|Billing Manager|Practice Manager|Reception');
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, CommunicationService $communicationService)
    {

//        dd(Booking::getNoShowStatus(3));
        $data = $this->breadcrumb(
            'Client booking ',
            'Calender page for Client related bookings',
            'booking_calendar',
            'Client Bookings',
            'Client Calendar'
        );

        $data['patients'] = Patient::getPatientInfo();
        return view('bookings.calender')->with($data);
    }

    public function getWaitingList()
    {
        $data = $this->breadcrumb(
            'Client Waiting Room ',
            'Calender page for Client related bookings',
            'waiting_room',
            'Waiting Room',
            'Waiting Room'
        );
		$myList = $this->booking->where('status', 3)->with('patient')->get();

		foreach ($myList as $list)
		{
			//check_in_time
			$list->waiting_time = $this->bookingService->convertDate($list->check_in_time);
		}
		$data['myList'] = $myList;
        //$data['myList'] = $this->booking->where('status', 3)->with('patient')->get();

		return view('bookings.waitingRoom.index')->with($data);

    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function allBookings()
    {
        $bookings = $this->bookingService->getAllBookings();
        return response($bookings, 200);
    }



    /**
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function GetBookingDetails($id)
    {
        $booking = $this->bookingService->getbookingsById($id);
        return response($booking, 200);
    }

    public function getNoShowBooking($id)
    {
        $data = $this->booking::getNoShowStatus($id);
        return response($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        if (!isset($request->classPatient)) {
            $validator = validator($request->all(), [
                'first_name' => 'required',
                'surname' => 'required',
                'email' => 'required|unique:patient',
                'cell_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:patient',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

        }

        $booking = $this->bookingService->persistBookingData($request);

        Alert::toast('Booking Added Successfully', 'success');
        return response()->json($booking);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function CreateOrUpdateBooking(Request $request)
    {

        $booking = $this->bookingService->createOrUpdateBooking($request);

        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $booking
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->booking::find($id)->delete($id);

        Alert::toast('Booking Status Deleted Successfully ', 'success');

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function CancelBooking($id, Request $request)
    {

        $booking = $this->booking::find($id);

        $booking->update([
            'className' => $request['className'],
            'status' => 6
        ]);

        Alert::toast('Booking Canceled Successfully ', 'success');

        return response()->json();

    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function CheckinBooking($id, Request $request)
    {

        $time = Carbon::now();
        $time->addHour(2);

        $booking = $this->booking::find($id);
        $booking->update([
            'className' => $request['className'],
            'status' => 3,
            'check_in_time' => $time
        ]);

        Alert::toast('Booking Checked In Successfully ', 'success');
        return response()->json();
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function CheckoutBooking($id, Request $request)
    {

        $time = Carbon::now();
        $time->addHour(2);

        $booking = $this->booking::find($id);
        $booking->update([
            'className' => $request['className'],
            'status' => 4,
            'check_in_out' => $time
        ]);

        Alert::toast('Booking Checked Out Successfully ', 'success');
        return response()->json();
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function NoShowBooking($id, Request $request)
    {
        $booking = $this->booking::find($id);

        $booking->update([
            'className' => $request->className,
            'status' => 2
        ]);

        Alert::toast('Booking status updated Successfully ', 'success');
        return response()->json();
    }
}
