<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingReminder extends Model
{
    use HasFactory;

    protected $table = 'booking_reminder';

    protected $fillable = [
        'title', 'patient_id', 'note',
        'reminder_date', 'appointment_date',
        'repeat_reminder','reminder_times'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
}
