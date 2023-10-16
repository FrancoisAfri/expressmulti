<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    use HasFactory,
        Uuids,
        LogsActivity;

    protected $table = 'booking';

    protected $fillable = [
        'patient_id', 'title', 'start', 'end', 'first_name', 'status',
        'surname', 'email', 'cell_number', 'className', 'Notes', 'check_in_time',
        'check_in_out'
    ];

    protected static $logName = 'Booking  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Booking Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BookingStatus::class, 'status', 'id');
    }

    public static function bookingStatus()
    {

        return collect([
            ['1' => 'confirmed'],
            ['2' => 'no_show'],
            ['3' => 'check In'],
            ['4' => 'check Out'],
            ['5' => 'invoiced'],
            ['6' => 'Cancel'],
        ]);

    }


    public static function getAllBookings()
    {

        return Booking::select(
            'id',
            'title',
            'start',
            'end',
            'email',
            'className',
            'first_name',
            'surname',
            'Notes',
            'cell_number',
            'status',
            'patient_id',
        )->whereIn('status', [1, 3, 4, 5, 6])
            ->get();
    }

    public static function getBookingById($id)
    {
        return Booking::where('id', $id)
            ->select(
                'id',
                'title',
                'start',
                'end',
                'className',
                'first_name',
                'status',
                'Notes',
                'surname',
                'cell_number',
            )->first();

    }

    public static function getBookingStatusById($id)
    {
        return Booking::where('patient_id', $id)
            ->select('status')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public static function getNoShowStatus($id)
    {
        $data = Booking::where(
            [
                'patient_id' => $id,
                'status' => 2,
            ]
        )
            ->orderBy('id', 'DESC')
            ->first();

        if (isset($data)) {
            return 1;
        } else
            return 0;

    }

    public static function displayRecords()
    {

        return Booking::select('booking.*', 'patient.first_name as name', 'patient.surname as 2ndname',
            'booking_status.name as booking_status')
            ->leftJoin('patient', 'booking.patient_id', '=', 'patient.id')
            ->leftJoin('booking_status', 'booking.status', '=', 'booking_status.id')
            ->orderBy('booking.id')
            ->get();


    }

    public static function getAllRecordsFromStatus($status)
    {
        return Booking::select('booking.*', 'patient.first_name as name', 'patient.surname as 2ndname',
            'booking_status.name as booking_status')
            ->leftJoin('patient', 'booking.patient_id', '=', 'patient.id')
            ->leftJoin('booking_status', 'booking.status', '=', 'booking_status.id')
            ->orderBy('booking.id')
            ->where('booking.status', $status)
            ->get();
    }


    public function getBookingForMonth()
    {
        return Booking::whereMonth('created_at', Carbon::now()->month)->count();
    }

    public function getBookingForShowedUp()
    { 
        return Booking::whereIn('status', [3, 4, 5])->count();
    }

    public function getBookingForNoShow()
    {
        return Booking::whereIn('status', [2, 6])->count();
    }
}
