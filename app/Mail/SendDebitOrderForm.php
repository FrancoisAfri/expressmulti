<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CompanyIdentity; 

class SendDebitOrderForm extends Mailable
{
    use Queueable, SerializesModels , CompanyIdentityTrait;
	public $first_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
		$adminEmails = $companyDetails['admin_email'];
        $subject = "New client regitration on $companyName.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['admin_email'] = $adminEmails;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['dashboard_url'] = url('/clients/approvals');

        return $this->view('Email.send_debit_order_form')
			->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
			->subject($subject)
			->with($data)
			->attach(storage_path('app/debit-order/debit_oder_form.pdf'));
    }
}
