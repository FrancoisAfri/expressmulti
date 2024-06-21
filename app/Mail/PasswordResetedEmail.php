<?php

namespace App\Mail;

use App\Traits\CompanyIdentityTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetedEmail extends Mailable
{
    use Queueable, SerializesModels , CompanyIdentityTrait;

    /**
     * @var mixed
     */
    private $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyDetails = $this->CompanyIdentityDetails();
        $companyEmail = $companyDetails['mailing_address'];
        $companyUser = $companyDetails['mailing_name'];
        $companyName = $companyDetails['company_name'];
        $support = $companyDetails['support'];
        $logo = $companyDetails['logo'];

        $mailData = [
            'name' =>  $this->name,
            'companyEmail' => $companyEmail,
            'logo' => $logo,
            'support' => $support,
            'company_name' => $companyName,
            'mailing_name' => $companyUser
        ];

        return $this->subject( 'Confirmation Booking!' )
            ->view('Email.PasswordResetedEmail')
            ->with('mailData', $mailData);
    }
}
