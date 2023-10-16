<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Doctor extends Model
{
    use HasFactory,
        Uuids,
        LogsActivity;

    protected $table = 'doctor';

    protected $fillable = [
        'patient_id', 'doc_name', 'doc_email', 'doc_phone',
    ];

    protected static $logName = 'Doctor  Details';

    protected function getDescriptionForEvent(string $eventName):string
    {
        return "Doctor Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }


}
