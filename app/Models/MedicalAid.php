<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class MedicalAid extends Model
{
    use HasFactory,
        Uuids,
        LogsActivity;

    protected $table = 'medical_aid';

    protected $fillable = [
        'holder_name', 'patient_id', 'medical_aid_scheme_name', 'surname',
        'id_number', 'passport_no', 'email', 'phone_no', 'medical_aid_plan',
        'medical_aid_no', 'medical_aid_dep_code',
    ];

    protected static $logName = 'MedialAid  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "MedialAid Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }


}
