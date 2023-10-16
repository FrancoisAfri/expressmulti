<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class contacts_setup extends Model
{
    use HasFactory , LogsActivity;

    //Specify the table name
    public $table = 'contacts_setup';

    // Mass assignable fields
    protected $fillable = [
        'sms_provider', 'sms_username', 'sms_password'];
}
