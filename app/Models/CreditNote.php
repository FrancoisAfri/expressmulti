<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    use HasFactory;

    protected $table = 'credit_note';

    protected $fillable = [
        'account_no', 'invoice_id', 'client_id',
        'amount', 'date', 'note'
    ];
}
