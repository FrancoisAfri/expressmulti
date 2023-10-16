<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class MainMember extends Model
{
    use HasFactory,
        Uuids,
        LogsActivity;

    protected $table = 'main_member';

    protected $fillable = [
        'patient_id', 'main_member_id_number', 'main_member_passport_number', 'main_member_passport_origin_country_id',
        'main_member_first_name', 'main_member_initial', 'main_member_surname', 'main_member_title',
        'main_member_relation', 'main_member_dependent_code', 'main_member_email',
        'main_member_phone', 'main_member_employer_name', 'main_member_employer_surname', 'main_member_employer_address',
        'main_member_employer_suburb', 'main_member_contact_city', 'main_member_contact_postalCode','main_member_employer_phone'

    ];

    protected static $logName = 'MainMember  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "MainMember Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }


}
