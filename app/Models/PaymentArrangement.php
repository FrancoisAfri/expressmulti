<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentArrangement extends Model
{
    use HasFactory;

    protected $table = 'payment_arrangement';

    protected $fillable = [
        'payment_name', 'percentage', 'start_date',
        'end_date', 'in_hospital', 'out_hospital', 'status',
    ];

    public static function getPaymentArrangement()
    {
        return PaymentArrangement::get();
    }

    public static function getPaymentArrangementById($id)
    {
        return PaymentArrangement::where('id', $id)->first();
    }
}
