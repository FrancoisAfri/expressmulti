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

class Companies_temp extends Model
{
    use HasFactory,
        Uuids,
        Notifiable,
        LogsActivity;

    protected $table = 'companies_temp';

    protected $fillable = [
        'name', 'email', 'cell_number', 'phone_number', 'res_address', 'post_address',
        'date_joined', 'client_logo', 'is_active', 'payment_method', 'payment_status'
		, 'package_id', 'database_name', 'database_user', 'database_password',
	 'trading_as','vat','tenant_url',
    ];
	

    protected static $logName = 'Client Profile';

    protected $appends = ['full_name'];

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Client Profile {$eventName} ";
    }

	// package relationship
	public function packages(): BelongsTo
    {
        return $this->belongsTo(Packages::class, 'package_id', 'id');
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
        return $this->hasMany(ContactPersonTemp::class, 'company_id', 'id');
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getPatientByUuid($id)
    {
        return Companies_temp::with(
            'packages',
            'contacts'
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
        return Companies_temp::with(
            'packages',
            'contacts'
        )->get();
    }

    public static function getUserDetailsByEmail($email)
    {
        return Companies_temp::where('email', $email)
            ->select('first_name', 'surname')
            ->first();
    }

    /**
     * @return mixed
     */
    public static function getPatientInfo()
    {
        return Companies_temp::select(
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
        return Companies_temp::where('id',$id)->first();
    }

    public static function totalPatients(){
        return Companies_temp::where('is_active',1)->count();
    }

    public static function getDetailsById($id){
        return Companies_temp::where('id',  $id)->first();
    }
	
	 public static function isPackageExist($id){

        $packages =  Companies_temp::where('package_id', $id)->first();

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
