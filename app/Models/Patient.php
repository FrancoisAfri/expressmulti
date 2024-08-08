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
		, 'package_id', 'database_name', 'database_user', 'database_password', 'package_type',
	 'trading_as','vat','tenant_url',
    ];


    protected static $logName = 'Client Profile Temp';

    protected $appends = ['full_name'];

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Client Profile Temp {$eventName} ";
    }

	// package relationship
	public function packages(): BelongsTo
    {
        return $this->belongsTo(Packages::class, 'package_id', 'id');
    }

	public function contacts(): HasMany
    {
        return $this->hasMany(ContactPerson::class, 'company_id', 'id');
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getPatientByUuid($id)
    {
        return Patient::with(
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
        return Patient::with(
            'packages',
            'contacts'
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

    public static function getPaymentStatus($vat)
    {
        return Patient::where('vat', $vat)
            ->select('payment_status')->first();
    }

}
