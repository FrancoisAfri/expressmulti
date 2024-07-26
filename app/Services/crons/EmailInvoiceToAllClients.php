<?php

namespace App\Services\crons;

use App\Models\BillingInvoice;
use App\Models\CompanyIdentity;
use App\Models\Packages;
use App\Models\Patient;
use App\Traits\CompanyIdentityTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailInvoiceToAllClients
{
    use  CompanyIdentityTrait;

    //query client management table. get all client, check their packages. now there is two packages,
    // one monthly and yearly.
    // if monthly send invoice, the invoice amount should be the price of the package.
    // if yearly check the creation date of the client is one year. if one year send invoice. 11 months send invoice.
    public function EmailInvoiceToAllClientsDependingOnTheirSubscription()
    {

        $invoiceNUmber = $this->generateInvoiceNumber();
        $patientsWithPackageType = Packages::getPatientsByPackageType(1);
        $data['name'] = $this->CompanyIdentityDetails();
        $data['companies'] = Patient::with('packages')->get();
        $data['date'] = $currentDate = date('j M Y');
        $data['invoice_number'] = $invoiceNUmber;
        $data['company_details'] = $this->CompanyIdentityDetails();

        $pdf = PDF::loadView('billing.invoice', $data)->setPaper(array(0, 0, 609.4488, 935.433), 'landscape');

        foreach ($patientsWithPackageType as $patient) {
            Mail::send('Email.invoice', $data, function ($message) use ($invoiceNUmber, $patient, $data , $pdf) {

                $message->to($patient->email, $patient->email)
                    ->subject('Invoice for ' . $patient->name)
                    ->attachData($pdf->output(), 'Billing_invoice_' . $invoiceNUmber . '.pdf');
            });
        }

    }

    public function createInvoice()
    {
        $details = BillingInvoice::where('id', $id)->with('patient', 'account')->first();
        $componyDetails = $this->CompanyIdentityDetails();
        $info = $this->InvoiceDetails($details->invoice_number);
        $invoiceNUmber = $details->invoice_number;
        $pdf = PDF::loadView('billing.Invoices.quotation', $info)->setPaper(array(0, 0, 609.4488, 935.433), 'landscape');

        $data['email'] = $details->patient->email;
        $data['firstname'] = $details->patient->first_name;
        $data['surname'] = $details->patient->surname;
        $data['logo'] = $componyDetails['logo'];

        Mail::send('Email.invoice', $data, function ($message) use ($details, $invoiceNUmber, $data, $pdf) {
            $message->to($data["email"], $data["email"])
                ->subject('Invoice for ' . $details->patient->first_name)
                ->attachData($pdf->output(), 'Billing_invoice_' . $invoiceNUmber . '.pdf');
        });

    }


    public function create()
    {
        $users = User::all(); // Replace with logic to fetch users who need invoices

        foreach ($users as $user) {
            // Generate PDF for each user (example)
            $pdf = PDF::loadView('invoices.invoice_pdf', ['user' => $user]);
            $pdfPath = storage_path('app/invoices/' . $user->id . '_invoice.pdf');
            $pdf->save($pdfPath);

            // Send email with invoice attached
            Mail::to($user->email)->send(new InvoiceEmail($pdfPath));

            // Clean up temporary PDF file if needed
            unlink($pdfPath);
        }
    }

    public function generateInvoiceNumber(): string
    {
        $uniqueId = DB::table('package_invoice')->max('id') + 1;
        $date = Carbon::now()->format('Ymd');
        return 'INV-' . $date . '-' . str_pad($uniqueId, 4, '0', STR_PAD_LEFT);
    }

    protected function CompanyIdentityDetails()
    {
        $companyDetails = CompanyIdentity::systemSettings();

        $data['logo'] = (!empty($companyDetails['company_logo_url'])) ?
            asset('uploads/'.$companyDetails['company_logo_url'] ) : asset('images/logo_default.png');

        $data['logos'] = (!empty($companyDetails['company_logo_url'])) ?
            asset('uploads/' . $companyDetails['company_logo_url']) :
            $data['system_background_image_url'] = $companyDetails['system_background_image_url'];

        $data['mailing_address'] = $companyDetails['mailing_address'];
        $data['mailing_name'] = $companyDetails['mailing_name'];
        $data['company_name'] = $companyDetails['company_name'];
        $data['header_name_bold'] = $companyDetails['header_name_bold'];
        $data['header_acronym_bold'] = $companyDetails['header_acronym_bold'];
        $data['header_acronym_regular'] = $companyDetails['header_acronym_regular'];
        $data['address'] = $companyDetails['address'];
        $data['suburb'] = $companyDetails['suburb'];
        $data['city'] = $companyDetails['city'];
        $data['postal_code'] = $companyDetails['postal_code'];
        $data['monthly_revenue_target'] = $companyDetails['monthly_revenue_target'];
        $data['daily_revenue_target'] = $companyDetails['daily_revenue_target'];
        $data['terms_and_conditions'] = $companyDetails['terms_and_conditions'];
        return $data;
    }


}
