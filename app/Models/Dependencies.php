<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Dependencies extends Model
{
    use HasFactory;
    use HasFactory,
        Uuids,
        LogsActivity;

    protected $table = 'dependancy';

    protected $fillable = [
        'patient_id', 'dependency_first_name', 'dependency_surname', 'dependency_date_of_birth',
        'dependency_age', 'dependency_code', 'dependency_id_number', 'dependency_passport_number',
        'dependency_passport_origin_country_id',
    ];

    protected static $logName = 'Dependencies  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Dependencies Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
}
