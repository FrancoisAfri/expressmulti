<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Employer extends Model
{
    use HasFactory,
        Uuids,
        LogsActivity;

    protected $table = 'employer';

    protected $fillable = [
        'patient_id', 'employer_name', 'employer_occupation', 'employer_title',
        'employer_phone', 'employer_address', 'employer_suburb', 'employer_city',
        'employer_postal_code', 'employer_province_id',
    ];

    protected static $logName = 'Employer  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Employer Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
}
