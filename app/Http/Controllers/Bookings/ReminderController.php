<?php

namespace App\Http\Controllers\Bookings;

use App\Http\Controllers\Controller;
use App\Models\BookingReminder;
use App\Models\Patient;
use App\Traits\BreadCrumpTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ReminderController extends Controller
{
    use BreadCrumpTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {




        $data = $this->breadcrumb(
            'Booking Modules',
            'Admin page for bokking related settings',
            'reminder',
            'Reminders',
            'Booking Reminders'
        );


        $data['patients'] = Patient::getPatientInfo();
        $data['reminder'] = BookingReminder::with('patient')->get();

        return view('bookings.reminder')->with($data);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = validator($request->all(), [
            'patient_id' => 'required',
            'note' => 'required',
            'title' => 'required',
            'appointment_date' => 'required',
            'reminder_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $BookingReminder = BookingReminder::create([
            'title' => $request['title'],
            'patient_id' => $request['patient_id'],
            'note' => $request['note'],
            'reminder_date' => $request['reminder_date'],
            'appointment_date' => $request['appointment_date'],
            'repeat_reminder' => $request['repeat_reminder'],
            'reminder_times' => $request['reminder_times'],
        ]);
        Alert::toast('Booking Added Successfully ', 'success');

        return response()->json([
            'success' => true,
            'data' => $BookingReminder
        ], 200);
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

    public function remider()
    {

        BookingReminder::where('repeat_reminder' > 0)->get();

    }
}
