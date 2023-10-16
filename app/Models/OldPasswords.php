<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldPasswords extends Model
{
    use HasFactory;

    protected $table = 'old_password';

    protected $fillable = [
        'email'
    ];

    protected $hidden = [
        'password'
    ];
}
