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

class Patient extends Model
{
    use HasFactory,
        Uuids,
        Notifiable,
        LogsActivity;

    protected $table = 'members';

    protected $fillable = [
        'title', 'first_name', 'initial', 'surname', 'email', 'cell_number', 'phone_number',
        'id_number', 'res_address', 'res_suburb', 'res_city', 'res_postal_code', 'res_province_id',
        'passport_number', 'passport_origin_country_id', 'date_of_birth', 'gender',
        'profile_pic', 'is_active', 'is_employed', 'occupation', 'employee_number', 'employee_phone',
        'postal_address', 'postal_suburb', 'postal_city', 'postal_postal_code', 'postal_province_id',
        'payment_details', 'payment_method','is_active','is_accepted'
    ];

    protected static $logName = 'Members  Details';

    protected $appends = ['full_name'];

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Members Details {$eventName} ";
    }


    public function sendRegisterBookingNotification($url='' , $user='' , $name='', $date='')
    {
        $this->notify(new regitserPatientNotification($url , $user,$name, $date));
    }

    public function sendBookingNotification($user , $name, $date)
    {
        $this->notify(new BookingNotification($user,$name, $date));
    }


    /**
     * @return HasMany
     */
    public function emergency(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    /**
     * @return HasMany
     */
    public function medicalAid(): HasMany
    {
        return $this->hasMany(MedicalAid::class);
    }

    /**
     * @return HasMany
     */
    public function doctor(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    /**
     * @return HasMany
     */
    public function guarantor(): HasMany
    {
        return $this->hasMany(Guarantor::class);
    }

    /**
     * @return HasMany
     */
    public function employer(): HasMany
    {
        return $this->hasMany(Employer::class);
    }

    public function mainMember(): HasMany
    {
        return $this->hasMany(MainMember::class);
    }



    public static function getPatientGender()
    {
        return [
            1 => 'Male',
            2 => 'Female'
        ];
    }


    public function addDoctor($doctor)
    {
       return $this->doctor()->save();
    }


    /**
     * @param $id
     * @return mixed
     */
    public static function getPatientByUuid($id)
    {
        return Patient::with(
            'emergency',
            'medicalAid',
            'doctor',
            'guarantor',
            'employer',
            'mainMember'
        )->where(
            [
                'uuid' => $id
            ]
        )->first();
    }

    public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->surname);
   }

    /**
     * @return Builder[]|Collection
     */
    public static function getPatientDetails()
    {
        return Patient::with(
            'emergency',
            'medicalAid',
            'doctor',
            'guarantor',
            'employer'
        )->get();
    }

    public static function getUserDetailsByEmail($email)
    {
        return Patient::where('email', $email)
            ->select('first_name', 'surname')
            ->first();
    }

    /**
     * @return mixed
     */
    public static function getPatientInfo()
    {
        return Patient::select(
            'id',
            'cell_number',
            'phone_number',
            'first_name',
            'surname',
            'email',
            'is_active'
        )
            ->where('is_active',1)
            ->get();
    }

    public static function getPatientDataById($id){
        return Patient::where('id',$id)->first();
    }

    public static function totalPatients(){
        return Patient::where('is_active',1)->count();
    }

    public static function getDetailsById($id){
        return Patient::where('id',  $id)->first();
    }

}
