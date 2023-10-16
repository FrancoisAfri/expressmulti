<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BillingInvoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';

    protected $fillable = [
        'invoice_type', 'medical_aid', 'payment_arrangement', 'patient_no', 'provider_details',
        'place_of_service', 'insurance_no', 'policy_no', 'broker_details', 'accounts_id',
        'invoice_number', 'invoice_date', 'invoice_amount'
        , 'invoice_balance_amount', 'status', 'invoice', 'medical_aid'
    ];


    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_no', 'id');
    }


    public function billingProcedures()
    {
        return $this->hasMany(BillingProcedures::class);
    }


    public function InvoicePayments()
    {
        return $this->hasMany(InvoicePayments::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'accounts_id', 'id');
    }

    public static function latestInvoice()
    {
        return BillingInvoice::latest()->first();
    }

    // relationship between invoices and medical aids
    public function medicalAids(): BelongsTo
    {
        return $this->belongsTo(MedicalAids::class, 'medical_aid', 'id');
    }

    public static function getInvoiceDetails($id)
    {
        return BillingInvoice::where('patient_no', $id)
            ->with(
                'patient',
                'account',
                'billingProcedures',
                'InvoicePayments'
            )
            ->get();
    }

    public static function getMedicalDetails()
    {
        return BillingInvoice::with(
            'medicalAids',
            'patient',
            'account',
            'billingProcedures',
            'InvoicePayments'
        )
            ->get();
    }

    public static function getCreditSummary()
    {
        return BillingInvoice::with(
            'medicalAids',
            'patient',
            'account',
            'billingProcedures',
            'InvoicePayments'
        )
//            ->whereDate(
//                'created_at', Carbon::today()
//            )
            ->get();
    }

    public static function getInvoices($id)
    {
        return BillingInvoice::where('accounts_id', $id)
            ->with('billingProcedures', 'invoicePayments')
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getPrintInvoiceData($id)
    {
        return BillingInvoice::where('invoice_number', $id)
            ->with(
                'billingProcedures.serviceProviders',
                'billingProcedures.procedureCode',
                'billingProcedures.modifiers',
                'invoicePayments',
                'patient',
                'account')
            ->get();
    }

    public static function getBillingData()
    {

        return BillingInvoice::with(
            'medicalAids',
            'billingProcedures.serviceProviders',
            'billingProcedures.procedureCode',
            'billingProcedures.modifiers',
            'invoicePayments',
            'patient',
            'account')
            ->groupBy('medical_aid')
//              ->orderBy('medicalAids.name', 'asc')
            ->get();
    }

    // get top perfomorming medical aids
    public static function topMedicalAids()
    {
        return BillingInvoice::with('medicalAids')
            ->select(
                ['invoice_type', 'medical_aid',
                    DB::raw(
                        'SUM(invoice_amount) AS total_amount'
                    )
                ]
            )->groupBy('invoice_type')
            ->groupBy('medical_aid')
            ->where('invoice_type', 1)
            ->orderBy('total_amount', 'desc')
            ->limit(6)
            ->get();
    }

    public static function SchemeageAnalysisdate($type)
    {
        // get date range
        $from_date = $to_date = '';
        if ($type == 1) {
            $from_date = Carbon::now()->subDays(30);
            $to_date = Carbon::now();
        } elseif ($type == 2) {
            $from_date = Carbon::now()->subDays(60);
            $to_date = Carbon::now()->subDays(30);
        } elseif ($type == 3) {
            $from_date = Carbon::now()->subDays(90);
            $to_date = Carbon::now()->subDays(60);
        } elseif ($type == 4) {
            $from_date = Carbon::now()->subDays(120);
            $to_date = Carbon::now()->subDays(90);
        } elseif ($type == 5) {
            $from_date = Carbon::now()->subDays(150);
            $to_date = Carbon::now()->subDays(120);
        } elseif ($type == 6) {
            $from_date = Carbon::now()->subDays(200);
            $to_date = Carbon::now()->subDays(150);
        }
        // query data
//        return $result = BillingInvoice::where('accounts_id', $accountID)
//            ->whereBetween('created_at', [$from_date, $to_date])
//            ->sum('invoice_balance_amount');

        return BillingInvoice::where('invoice_type', 1)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('invoice_balance_amount');
    }


    public static function dataMedic()
    {
//        return BillingInvoice::with('medicalAids')
////            ->join('medical_aids', 'invoice.medical_aid', '=', 'medical_aids.id')
//            ->join('invoice_payment', 'invoice.id', '=', 'invoice_payment.billing_invoice_id')
//            ->join('patient', 'invoice.patient_no', '=', 'patient.id')
//            ->orderBy('medical_aid')
//            ->where('medical_aid', '>', 0)
//            ->get()
//            ->groupBy(function ($data) {
//                return $data->medical_aid;
//            });

//        MedicalAid::join('invoice', 'invoice.medical_aid', '=', 'medical_aids.id')

    }


    public function medicAnalysis($type)
    {

        $from_date = $to_date = '';
        if ($type == 1) {
            $from_date = Carbon::now()->subDays(30);
            $to_date = Carbon::now();
        } elseif ($type == 2) {
            $from_date = Carbon::now()->subDays(60);
            $to_date = Carbon::now()->subDays(30);
        } elseif ($type == 3) {
            $from_date = Carbon::now()->subDays(90);
            $to_date = Carbon::now()->subDays(60);
        } elseif ($type == 4) {
            $from_date = Carbon::now()->subDays(120);
            $to_date = Carbon::now()->subDays(90);
        } elseif ($type == 5) {
            $from_date = Carbon::now()->subDays(150);
            $to_date = Carbon::now()->subDays(120);
        } elseif ($type == 6) {
            $from_date = Carbon::now()->subDays(200);
            $to_date = Carbon::now()->subDays(150);
        }
        return BillingInvoice::join('medical_aids', 'invoice.medical_aid', '=', 'medical_aids.id')
            ->join('invoice_payment', 'invoice.id', '=', 'invoice_payment.billing_invoice_id')
            ->whereBetween('invoice_payment.created_at', [$from_date, $to_date])
            ->orderBy('medical_aid')->get()->groupBy(function ($data) {
                return $data->medical_aid;
            });
    }


    public static function ageAnalysisInsuarance($type)
    {

        $from_date = $to_date = '';
        if ($type == 1) {
            $from_date = Carbon::now()->subDays(30);
            $to_date = Carbon::now();
        } elseif ($type == 2) {
            $from_date = Carbon::now()->subDays(60);
            $to_date = Carbon::now()->subDays(30);
        } elseif ($type == 3) {
            $from_date = Carbon::now()->subDays(90);
            $to_date = Carbon::now()->subDays(60);
        } elseif ($type == 4) {
            $from_date = Carbon::now()->subDays(120);
            $to_date = Carbon::now()->subDays(90);
        } elseif ($type == 5) {
            $from_date = Carbon::now()->subDays(150);
            $to_date = Carbon::now()->subDays(120);
        } elseif ($type == 6) {
            $from_date = Carbon::now()->subDays(200);
            $to_date = Carbon::now()->subDays(150);
        }
        return $result = BillingInvoice::where('invoice_type', 3)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('invoice_balance_amount');
    }

    public static function ageAnalysisTotalPatient($type)
    {
        $from_date = $to_date = '';
        if ($type == 1) {
            $from_date = Carbon::now()->subDays(30);
            $to_date = Carbon::now();
        } elseif ($type == 2) {
            $from_date = Carbon::now()->subDays(60);
            $to_date = Carbon::now()->subDays(30);
        } elseif ($type == 3) {
            $from_date = Carbon::now()->subDays(90);
            $to_date = Carbon::now()->subDays(60);
        } elseif ($type == 4) {
            $from_date = Carbon::now()->subDays(120);
            $to_date = Carbon::now()->subDays(90);
        } elseif ($type == 5) {
            $from_date = Carbon::now()->subDays(150);
            $to_date = Carbon::now()->subDays(120);
        } elseif ($type == 6) {
            $from_date = Carbon::now()->subDays(200);
            $to_date = Carbon::now()->subDays(150);
        }
        return $result = BillingInvoice::where('invoice_type', 2)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('invoice_balance_amount');
    }

    public static function PatientAccountAgeAnalysisTotal($accountID, $type)
    {

        $from_date = $to_date = '';
        if ($type == 1) {
            $from_date = Carbon::now()->subDays(30);
            $to_date = Carbon::now();
        } elseif ($type == 2) {
            $from_date = Carbon::now()->subDays(60);
            $to_date = Carbon::now()->subDays(30);
        } elseif ($type == 3) {
            $from_date = Carbon::now()->subDays(90);
            $to_date = Carbon::now()->subDays(60);
        } elseif ($type == 4) {
            $from_date = Carbon::now()->subDays(120);
            $to_date = Carbon::now()->subDays(90);
        } elseif ($type == 5) {
            $from_date = Carbon::now()->subDays(150);
            $to_date = Carbon::now()->subDays(120);
        } elseif ($type == 5) {
            $from_date = Carbon::now()->subDays(180);
            $to_date = Carbon::now()->subDays(150);
        } elseif ($type == 6) {
            $from_date = Carbon::now()->subDays(210);
            $to_date = Carbon::now()->subDays(180);
        } elseif ($type == 7) {
            $from_date = Carbon::now()->subDays(250);
            $to_date = Carbon::now()->subDays(210);
        } elseif ($type == 8) {
            $from_date = Carbon::now()->subDays(300);
            $to_date = Carbon::now()->subDays(250);
        }
        $result = BillingInvoice::where('accounts_id', $accountID)
            ->where('invoice_type', 2)
            ->whereBetween('created_at', [$from_date, $to_date])
            ->sum('invoice_balance_amount');


    }


    public static function getAmountDue($id, $year, $month)
    {
        $query = BillingInvoice::select('invoice_balance_amount')
            ->where('patient_no', $id)
            ->whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->orderBy('id', 'desc')
            ->get();


        if (isset($query))
            return $query->sum('invoice_balance_amount');
        else return 0;
    }
}
