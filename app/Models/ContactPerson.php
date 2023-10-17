<?php

namespace App\Models;

use App\Notifications\regitserPatientNotification;
use App\Notifications\BookingNotification;
use App\Notifications\ResetPasswordNotification;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPerson extends Model
{
    use HasFactory,
        Uuids,
        Notifiable,
        LogsActivity;
		
	protected $table = 'contacts_person';

    protected $fillable = [
        'company_id', 'first_name', 'surname', 'phone_number', 'date_of_birth', 'email',
        'cell_number','status',
    ];
	
	protected static $logName = 'Client Profile';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Client Profile {$eventName} ";
    }
	
	// relationship
	
	public function company(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'company_id', 'id');
    }
	// get all clients info
	public static function getContactInfo()
    {
        return ContactPerson::with('company')
            ->where('status',1)
            ->get();
    }
	// get user by email
	public static function getUserDetailsByEmail($email)
    {
        return ContactPerson::where('email', $email)
            ->select('first_name', 'surname')
     
}
