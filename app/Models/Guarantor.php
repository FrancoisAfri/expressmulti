<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Guarantor extends Model
{
    use HasFactory,
        Uuids,
        LogsActivity;

    protected $table = 'guarantor';

    protected $fillable = [
        'patient_id', 'guarantor_id_number', 'guarantor_passport_number',
        'guarantor_passport_origin_country_id', 'guarantor_first_name',
        'guarantor_initial', 'guarantor_surname', 'guarantor_title', 'guarantor_relation',
        'guarantor_email', 'guarantor_phone', 'emergency_contact_cell_number', 'relation',
        'guarantor_employer_first_name', 'guarantor_employer_phone', 'guarantor_employer_address',
        'guarantor_employer_suburb', 'guarantor_employer_city', 'guarantor_employer_postal',
    ];

    protected static $logName = 'Guarantor  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Guarantor Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
}
