<?php

namespace App\Models;

use App\Http\Controllers\Sms\BulkSmsController;
use App\Notifications\NewUserNotification;
use App\Notifications\NewRestaurantOwner;
use App\Traits\LockableTrait;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use
        HasApiTokens,
        LockableTrait,
        HasFactory,
        Notifiable,
        HasRoles,
        HasApiTokens,
        LogsActivity;


    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'phone_number', 'google_id',
        'facebook_id', 'password', 'type', 'status',
        'last_login_at', 'last_login_ip', 'user_fcm_token', 'online',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'phone_verified_at' => 'datetime',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getNameAttribute()
    {
        return $this->causer->name ?? null;
    }

    protected static $logName = 'Users';

    protected static $logAttributes = ['name', 'email', 'phone_number', 'type', 'status'];
    protected static $ignoreChangedAttributes = ['password', 'updated_at'];


//    protected static $recordEvents = ['created', 'updated', ''];


    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "User {$eventName} ";
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */

    protected $appends = [
        'name',
        //'profile_photo_url',

    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendNewUserPasswordResetNotification($token, $temp_password ,$user)
    {
        $this->notify(new NewUserNotification($token, $temp_password ,$user));
    }
	
	public function sendOwnerPasswordResetNotification($token, $temp_password ,$user, $domain)
    {
        $this->notify(new NewRestaurantOwner($token, $temp_password ,$user, $domain));
    }



    /**
     * The relationships between user and hr_person / contacts_contacts.
     *
     * @return array HRPerson|ContactPerson hasOne
     */
    public function person()
    {
        return $this->hasOne(HRPerson::class, 'user_id');
    }


    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function passwordSecurity()
    {
        return $this->hasOne(PasswordSecurity::class);
    }


    public function addPerson($person)
    {
        return $this->person()->save($person);
    }


    public function updateUser($person)
    {
        return $this->person()->update($person);
    }

    public static function getAllActiveUsers()
    {
        return User::where('status', 1)->get();
    }


    public static function getUsersByUuid($id)
    {

        return User::where(['uuid' => $id])->first();
    }

    public static function defaultAvatar()
    {
        $user = Auth::user()->load('person');
        return ($user->person->gender === 0) ? asset('images/m-silhouette.jpg') : asset('images/f-silhouette.jpg');
    }

    public static function getLatestCreatedUser()
    {
        return User::latest()->first();
    }

    public static function getUserDetails($email)
    {
        return User::where('email', $email)
            ->select('name', 'email')
            ->first();
    }

    /**
     * @param $value
     * @return string
     */
    public function getDecryptedAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getUserNumber($id)
    {
        return User::where(
            'id', $id
        )->select(
            'phone_number'
        )->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getUserById($id)
    {
        return User::where('id', $id)->first();
    }
}
