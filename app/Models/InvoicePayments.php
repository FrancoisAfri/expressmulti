<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class InvoicePayments extends Model
{
    use HasFactory;

    protected $table = 'invoice_payment';

    protected $fillable = [
        'billing_invoice_id', 'client_id', 'date',
        'amount', 'paid', 'owed', 'note', 'invoice_number',
        'status', 'accounts_id', 'description','payment_type'
    ];


    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'accounts_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(BillingInvoice::class, 'invoice_number', 'invoice_number');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'client_id', 'id');
    }


    public function billingProcedures()
    {
        return $this->belongsTo(BillingProcedures::class, 'billing_invoice_id', 'id');
    }

    public static function getAllrecordsByAccountdId($id)
    {
        return InvoicePayments::where('accounts_id', $id)->get();
    }

    public static function getDailySummary($type){
        return InvoicePayments::with(
            'invoice',
            'patient',
            'billingProcedures'
        )
            ->where('payment_type',$type)
            ->whereDate(
                'created_at', Carbon::today()
            )
            ->get();
    }

    public static function getAccouintsInfo(){
       return InvoicePayments::with(
            'invoice',
            'account',
            'patient',
            'billingProcedures'
        )
            ->where('owed' ,'>=',0)
            ->where('paid' ,0)
            ->orderBy('client_id','DESC')
            ->get();
    }

    public static function getCreditSummary(){
        return InvoicePayments::with(
            'invoice',
            'account',
            'patient',
            'billingProcedures'
        )
            ->where('paid', '!=' ,0)
            ->whereDate(
                'created_at', Carbon::today()
            )
            ->get();
    }

    public static function getInvoiceDetails($id, $startDate, $endDate)
    {
        return InvoicePayments::with(
            'account',
            'invoice',
            'patient',
            'billingProcedures'
        )
            ->where('client_id', $id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

    }



        public static function getDetailsForLastSixMonths($id, $year, $month)
        {

           $id =  Accounts::getAccountByUuid($id)->id;
            return InvoicePayments::with(
                'account',
                'invoice',
                'patient',
                'billingProcedures'
            )
                ->where('client_id', $id)
                ->whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '=', $month)
               ->get();

        }


    public static function getPaymentSummary($startDate, $endDate, $type)
    {
            return InvoicePayments::with(
                'invoice',
                'patient',
                'billingProcedures'
            )
                ->where('payment_type',$type)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();


    }

    public static function getSummaryByMonth($month){
        return InvoicePayments::with(
            'invoice',
            'patient',
            'billingProcedures',
            'account'
        )->whereMonth(
            'created_at',$month
        )->get();
    }



    public static function getDailySummaryForType($type){
        return InvoicePayments::with(
            'invoice',
            'patient',
            'billingProcedures'
        )
            ->where('payment_type',$type)
            ->whereDate(
                'created_at', Carbon::today()
            )
            ->get();
    }


    public static function getPaymentSummaryForType($type , $startDate ,$endDate ){
         return InvoicePayments::with(
            'invoice',
            'patient',
            'billingProcedures'
        )
            ->where('payment_type',$type)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    }

    public static function getTotalTransactions($startDate, $endDate){
        return InvoicePayments::whereBetween('created_at', [$startDate, $endDate])
        ->count();
    }

    public static function getTotalPatients($startDate, $endDate){
        return InvoicePayments::select('client_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct()->get();
    }



    public static function getOpeningBalance($id, $startDate, $endDate)
    {
        $balance =   InvoicePayments::Where('client_id', $id)
            ->whereBetween('date', [$startDate, $endDate])
            ->first();
        if (isset($balance))
            return $balance->owed;
        else return $balance;
    }

    public static function AmountDue($id, $startDate ,$endDate ){
        $query =  BillingInvoice::select('invoice_balance_amount')
            ->where('accounts_id',$id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('id', 'desc')
            ->get();

        if (isset($query))
            return $query->sum('invoice_balance_amount');
        else return 0;
    }

    public static function totalPaid($id, $startDate,$endDate)
    {
        $totalPaid =  InvoicePayments::where('accounts_id', Accounts::getAccountByUuid($id)->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->first();

        if (isset($totalPaid))
            return $totalPaid->sum('paid');
        else return 0;

    }

    public static function totalPaidThisMonth($id, $year,$month)
    {

        $totalPaid =  InvoicePayments::where('client_id', $id)
            ->whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->get();

        if (isset($totalPaid))
            return $totalPaid->sum('paid');
        else return 0;

    }

    public static function getInvoicePaymentDetails()
    {

        return InvoicePayments::
        with(
            'account',
            'invoice',
            'patient',
            'billingProcedures'
        )->get();

    }

    public function created_at_difference()
    {
        $date = Carbon::today()->subDays(30);
        return InvoicePayments::where('created_at', '>=', $date)->get();

    }

    public function last_30_days()
    {
        $date = Carbon::today()->subDays(30);
        return InvoicePayments::where('created_at', '>=', $date)->get();
    }


}
