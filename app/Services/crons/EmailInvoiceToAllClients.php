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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class EmailInvoiceToAllClients
{
    use  CompanyIdentityTrait;

    public function EmailInvoiceToAllClientsDependingOnTheirSubscriptionBasedOnSubscription()
    {
		$componyDetails = $this->CompanyIdentityDetails();
        // Fetch all patients with their packages and creation dates
        $patients = Patient::with(['packages' => function($query) {
            $query->select('package_id', 'package_type', 'package_price', 'created_at');
        }])->get();

        $currentDate = new DateTime();
		$adminEmails = $componyDetails['admin_email'];
        foreach ($patients as $patient) {
            // Get the package details
            $package = $patient->packages->first(); // Assuming each patient has only one package
            if (!$package) continue;

            $packageType = $package->package_type;
            $packagePrice = $package->package_price;
            $creationDate = new \DateTime($package->creation_date);

            // Determine if an invoice should be sent based on the package type and subscription duration
            if ($packageType == 'monthly') {
                // Send invoice every month for monthly packages
                $invoiceNumber = $this->generateInvoiceNumber();
                $data = $this->prepareInvoiceData($patient, $invoiceNumber, $packagePrice);

                $pdf = PDF::loadView('invoice.invoice_demo', $data)->setPaper([0, 0, 609.4488, 935.433], 'landscape');
                $this->sendInvoiceEmail($patient, $invoiceNumber, $pdf, $adminEmails);
            } elseif ($packageType == 'yearly') {
                // Calculate subscription duration
                $interval = $currentDate->diff($creationDate);

                // Send invoice if exactly one year or 11 months have passed
                if ($interval->y >= 1 || ($interval->m >= 11 && $interval->y == 0)) {
                    $invoiceNumber = $this->generateInvoiceNumber();
                    $data = $this->prepareInvoiceData($patient, $invoiceNumber, $packagePrice);

                    $pdf = PDF::loadView('invoice.invoice_demo', $data);
                    $this->sendInvoiceEmail($patient, $invoiceNumber, $pdf, $adminEmails);
                }
            }
        }
    }

    public function EmailInvoiceToAllClientsDependingOnTheirSubscription()
    {
		ini_set('memory_limit', '100M');
		$componyDetails = $this->CompanyIdentityDetails();
		$adminEmails = !empty($componyDetails['admin_email']) ? $componyDetails['admin_email'] : '';
        $type = 1;
        $companies =  Patient::getAllActiveClients();

        if ($companies->isEmpty()) {
            return 0;
        }

        $date = date('j M Y');
		$details = $this->CompanyIdentityDetails();
		// dd($companies);
		$logo = (!empty($details['company_logo_url'])) ? 'uploads/'.$details['company_logo_url']  : 'images/logo_default.png';

        foreach ($companies as $company) {
          //  dd($company);
            $invoiceNumber = $this->generateInvoiceNumber($company->id);
            $data['invoice_number'] = $invoiceNumber;
            $data['company_details'] = $this->CompanyIdentityDetails();
            $data['date'] = date('j M Y');
            $data['logo'] = $logo;
            $data['company'] = $company;
            $pdf = PDF::loadView('invoice.test', $data)->setPaper('a4', 'landscape');
            // Send email with the PDF attachment
            $this->sendInvoiceEmail($data, $company, $invoiceNumber, $pdf, $adminEmails);
			$pdf = '';
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
        $data['admin_email'] = $componyDetails['admin_email'];

        Mail::send('Email.invoice', $data, function ($message) use ($details, $invoiceNUmber, $data, $pdf) {
			$message->to($data["email"], $data["email"])
				->cc($data["admin_email"]) // <-- Add this line for CC
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

    public function generateInvoiceNumber($id): string
    {
		
		//$companyId = 123; // Replace with the actual company_id you're checking for

		/*$record = DB::table('package_invoice')
			->where('is_paid', false) // is_paid is 0 (false)
			->where('company_id', $id) // company_id matches the provided value
			->whereNotNull('time') // Ensure time is not null before extracting month
			->select(DB::raw('MONTH(FROM_UNIXTIME(time)) as month')) // Extract month from UNIX timestamp
			->first(); // Get the first matching record

		if ($record) {
			$month = $record->month;
			// Do something with the month value
		} else {
			// Handle case where no matching record is found
		}*/
		$record = DB::table('package_invoice')->insert([
			'company_id' => $id, // Set the company_id
			'is_paid' => false, // Set the company_id
			'time' => time(), // Set the current time as a UNIX timestamp
		]);
		
		//$uniqueId = $record->id;
		$uniqueId = DB::table('package_invoice')->max('id');
		
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

        $data['company_logo_url'] = !empty($companyDetails['company_logo_url']) ? $companyDetails['company_logo_url'] : '';
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
        $data['admin_email'] = $companyDetails['admin_email'];
        return $data;
    }

    /**
     * @param $data
     * @param $patient
     * @param string $invoiceNumber
     * @param \Barryvdh\DomPDF\PDF $pdf
     * @return void
     */
    public function sendInvoiceEmail($data, $client, string $invoiceNumber, \Barryvdh\DomPDF\PDF $pdf, $adminEmail): void
    {
        Mail::send('Email.invoice', $data, function ($message) use ($client, $invoiceNumber, $pdf) {
            $message->to($client->contacts->email, $client->contacts->name)
				->cc($adminEmail) // <-- Add this line for CC
                ->subject('Invoice for ' . $client->name)
                ->attachData($pdf->output(), 'Invoice_' . $invoiceNumber . '.pdf');
        });
    }

    /**
     * @param $patient
     * @param $invoiceNumber
     * @param $packagePrice
     * @return array
     */
    private function prepareInvoiceData($patient, $invoiceNumber, $packagePrice)
    {
        return [
            'name' => $this->CompanyIdentityDetails(),
            'companies' => $patient,
            'date' => date('j M Y'),
            'invoice_number' => $invoiceNumber,
            'company_details' => $this->CompanyIdentityDetails(),
            'package_price' => $packagePrice
        ];
    }

//        $patientsWithPackageType =  Patient::with('packages')->whereHas('packages', function ($query) use ($type) {
//        $query->where('package_type', $type);
//    })->get();

        // Eloquent: Get posts with user information
//        $posts = Post::join('users', 'posts.user_id', '=', 'users.id')
//            ->select('posts.*', 'users.name as user_name')
//            ->get();

//        $patientsWithPackageType =  Patient::join('packages', 'patients.id', '=', 'packages.patient_id')
////            ->where('packages.package_type', $type)
////            ->select('patients.*')  // Selects all columns from the patients table
////            ->distinct()           // Ensures that each patient appears only once
//            ->get();
}
