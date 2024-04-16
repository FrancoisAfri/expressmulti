<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Activitylog\Traits\LogsActivity;

\Carbon\Carbon::setToStringFormat('d-m-Y');

class HRPerson extends Model
{
    use HasFactory, Uuids, LogsActivity;

    protected $dates = [
        'date_of_birth',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];

    //Specify the table name
    public $table = 'hr_people';

    // Mass assignable fields
    protected $fillable = [
        'first_name', 'surname', 'user_id', 'middle_name', 'maiden_name', 'aka', 'initial', 'email', 'cell_number',
        'phone_number', 'id_number', 'date_of_birth', 'passport_number', 'drivers_licence_number', 'drivers_licence_code',
        'proof_drive_permit', 'proof_drive_permit_exp_date', 'drivers_licence_exp_date', 'gender', 'own_transport', 'marital_status',
        'ethnicity', 'profile_pic', 'status', 'division_level_1', 'division_level_2', 'division_level_3', 'employee_number',
        'division_level_4', 'division_level_5', 'leave_profile', 'manager_id', 'second_manager_id', 'date_joined', 'date_left', 'role_id',
        'position', 'bio', 'res_address', 'res_suburb', 'res_city', 'res_postal_code', 'res_province_id',

    ];

    protected static $logName = 'Users Details';

    protected static $logAttributes = ['*'];

    protected static $logAttributesToIgnore = ['text'];

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

//    protected static $logAttributes = ['first_name','surname','user_id','gender', 'email', 'phone_number', 'type', 'status'];

    /**
     * @param string $eventName
     * @return string
     */

    public function getProfilePicUrlAttribute()
    {
        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        return (!empty($this->profile_pic)) ? Storage::disk('local')->url("avatars/$this->profile_pic") : (($this->gender === 2) ? $f_silhouette : $m_silhouette);
    }

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "User Details {$eventName} ";
    }


    //Relationship hr_person and user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function moduleAcess()
    {
        return $this->belongsTo(module_access::class, 'user_id');
    }


    //Full Name accessor
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->surname;
    }

    public function initials(){
        $words = explode(" ", $this->first_name );
        $initials = null;
        foreach ($words as $w) {
            $initials .= $w[0];
        }
        return strtoupper($initials);
    }



    public function setEncyptedIdNumber(): string
    {
        return Crypt::encryptString($this->id_number);
    }

    public function getEncyptedIdNumber(): string
    {
        return Crypt::decryptString($this->id_number);
    }


    public static function getAllUsers()
    {
        return HRPerson::where('status', 1)->get();
    }


    public static function getUserDetails($employeeNumber)
    {
        return HRPerson::where(['employee_number' => $employeeNumber, 'status' => 1])
            ->select('id', 'first_name', 'surname', 'email', 'employee_number')
            ->first();
    }

    public static function getEmployeeNumber()
    {
        $users = HRPerson::where(
            'status', 1
        )->pluck('employee_number');

        return $filteredCollection = $users->filter();
    }

    /**
     * @param $first_name
     * @param $surname
     * @return string
     */
    public static function getFullName($first_name, $surname): string
    {
        return $first_name . ' ' . $surname;
    }

    public static function STATUS_SELECT(): \Illuminate\Support\Collection
    {
        $status = [
            '1' => 'Active',
            '0' => 'De-Activated',
        ];

        return collect($status);
    }

    public static function getAllUsersByName()
    {
        return HRPerson::orderBy('first_name', 'asc')->get();
    }

    public static function getDetailsOfLoggedUser()
    {
        return HRPerson::where('user_id', Auth::id())->first();
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserDetailsByEmail($email)
    {
        return HRPerson::where('email', $email)
            ->select('first_name', 'surname')
            ->selectRaw("(CASE WHEN (gender = 1) THEN 'Female' ELSE 'Male' END) as gender_text")
            ->first();
    }
	
	public function getWaiterList()
    {
        return HRPerson::where('role_id', 4)
            ->select('first_name', 'surname', 'id as hr_id', 'initial')
            ->first();
    }
	public function getWaiterStatus($waiter)
    {
        $user = HRPerson::with('user')->where('id',$waiter)->first();
		if (!empty($user->user->online) && $user->user->online == 1)
			return 1;
		else return 0;
    }
}
