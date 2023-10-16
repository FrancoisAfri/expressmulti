<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Accounts extends Model
{
    use HasFactory , Uuids;

    protected $table = 'account';

    protected $fillable = [
        'account_number', 'client_id', 'account_manager', 'status'
    ];

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class,'client_id','id');
    }

    public function billingProcedures(){
        return $this->hasMany(BillingProcedures::class);
    }

    public function invoices(){
        return $this->hasMany(BillingInvoice::class);
    }

    public function invoicePayments(){
        return $this->hasMany(InvoicePayments::class);
    }



    public static function getUserAccountDetails(){
        return Accounts::with('patient')->get();
    }

    public static function isAccountExist($id){

        $accounts =  Accounts::where('client_id', $id)->first();

        $isAccountExist = 0;
        if (isset($accounts) === True){
            $isAccountExist = 1;
        }
        else{
              $isAccountExist = 0;
        }

        return $isAccountExist;
    }


    public static function getAccountById($id){
       return $accounts =  Accounts::where('client_id', $id)->first();
    }


    public static function getAccountByUuid($id){
        return $accounts =  Accounts::where('uuid', $id)->first();
    }

    public static function getAccountDetails($id){
      return  Accounts::where('uuid' , $id)
            ->with(
                'patient',
                'billingProcedures',
                'invoices',
                'invoicePayments'
            )
            ->get();
    }

    public static function getAccount($id){
        return  Accounts::where('uuid' , $id)
            ->first();
    }

    public static function getAccounts(){


       return Accounts::with(['patient', 'billingProcedures','invoices','invoicePayments' => function($q)  {
// Query the name field in status table
            $q->where('paid', '!=', '0');
        }])->orderBy('client_id','DESC')
            ->get();



    }

    public function created_at_difference()
    {
        $date = Carbon::today()->subDays(30);
        return Accounts::where('created_at','>=',$date)->get();

    }
}
