<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class EmergencyContact extends Model
{
    use HasFactory,
        Uuids,
        LogsActivity;

    protected $table = 'next_of_kin';

    protected $fillable = [
        'patient_id', 'emergency_contact_name', 'emergency_contact_surname',
        'email', 'emergency_contact_cell_number', 'relation'
    ];

    protected static $logName = 'Emergency Contact';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Emergency Contact Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }


}
