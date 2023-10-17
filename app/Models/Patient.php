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

    protected $table = 'companies';

    protected $fillable = [
        'name', 'email', 'cell_number', 'phone_number', 'res_address', 'post_address',
        'date_joined', 'client_logo', 'is_active', 'payment_method', 'payment_status'
		, 'package_id',
    ];
	

    protected static $logName = 'Client Profile';

    protected $appends = ['full_name'];

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Client Profile {$eventName} ";
    }


    public function sendRegisterBookingNotification($url='' , $user='' , $name='', $date='')
    {
        $this->notify(new regitserPatientNotification($url , $user,$name, $date));
    }

    public function sendBookingNotification($user , $name, $date)
    {
        $this->notify(new BookingNotification($user,$name, $date));
    }

	public function contacts(): HasMany
    {
        return $this->hasMany(ContactPerson::class);
    }
    /**
     * @return HasMany
     */
    public function emergency(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
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
	
	 public static function isPackageExist($id){

        $packages =  Patient::where('package_id', $id)->first();

        $isPackagesExist = 0;
        if (isset($packages) === True){
            $isPackagesExist = 1;
        }
        else{
              $isPackagesExist = 0;
        }

        return $isPackagesExist;
    }

}
