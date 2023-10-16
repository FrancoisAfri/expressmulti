<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Services\BookingService;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    use BreadCrumpTrait;

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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//

    public function index(Request $request)
    {

        $date_range = "2022-12-01 to 2022-12-26";
        $dates = explode("to", $date_range);


        $dates1 = $dates[0];
        $dates12 = $dates[1];


        $data = $this->breadcrumb(
            'Settings Modules',
            'Admin page for security related settings',
            'audits',
            'Settings',
            'Security Audits'
        );




        if ($request->ajax()) {


            $data = Booking::select('booking.*', 'patient.first_name as name', 'patient.surname as 2ndname',
                'booking_status.name as booking_status')
                ->leftJoin('patient', 'booking.patient_id', '=', 'patient.id')
                ->leftJoin('booking_status', 'booking.status', '=', 'booking_status.id');

            return Datatables::of($data)
                ->filter(function ($instance) use ($dates, $request) {
//
//                    if ($request->get('booking_status') === null) {
//                        $instance =  Booking::displayRecords();
//                    }
//
                    if ($request->get('booking_status')) {
                        $instance->where('booking.status', $request->get('booking_status'));
                    }


                    if (!empty($request->get('date_range'))) {
                        $dates = explode("to", $request->get('date_range'));
                        $startDate = $dates[0];
                        $endDate = $dates[1];
                        $instance->whereBetween('booking.created_at', [$startDate, $endDate]);
                    }

                })
                ->addIndexColumn()
                ->make(true);
        }

        $data['status'] =  BookingStatus::all();
        return view('bookings.reports.index')->with($data);
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
        //
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
