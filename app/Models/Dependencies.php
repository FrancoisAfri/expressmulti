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

    protected $table = 'contacts_person';

    protected $fillable = [
        'company_id', 'first_name', 'surname', 'date_of_birth',
        'email', 'cell_number',
    ];

    protected static $logName = 'Contacts Person  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Contacts Person Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
}
