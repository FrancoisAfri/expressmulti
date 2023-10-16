<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class BillingProcedures extends Model
{
    use HasFactory;

    protected $table = 'non_invoice_type';

    protected $fillable = [
        'billing_invoice_id', 'date', 'serviceProvider',
        'procedure_code', 'modifier', 'nappy_code', 'icd10_code',
        'quantity', 'unit_price', 'price', 'patient_no', 'accounts_id'
    ];


    public function invoices()
    {
        return $this->belongsTo(BillingInvoice::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class,'patient_no','id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'accounts_id','id');
    }

    public function serviceProviders()
    {
        return $this->belongsTo(serviceProvider::class, 'serviceProvider', 'id');
    }
    public function procedureCode()
    {
        return $this->belongsTo(ProcedureCode::class, 'procedure_code', 'id');
    }

    public function modifiers()
    {
        return $this->belongsTo(Modifier::class, 'modifier', 'id');
    }
    public function last_30_days()
    {
        $date = Carbon::today()->subDays(30);
        return BillingProcedures::where('created_at', '>=', $date)->get()->sum('price');

    }

    public static function getDetailedInfo()
    {
       return BillingProcedures::with('patient','account')
           ->orderBy('date', 'desc')
           ->get();

       // dd($var);
    }

}
