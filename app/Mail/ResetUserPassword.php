<?php

namespace App\Mail;

use App\Traits\CompanyIdentityTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetUserPassword extends Mailable
{
    use Queueable , CompanyIdentityTrait ;

    /**
     * @var mixed
     */
    private $url;
    /**
     * @var mixed
     */
    private $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $name)
    {
        $this->url = $url;
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
//            'temp_password' => $this->temp_password,
//            'token' => $this->token,
            'companyEmail' => $companyEmail,
            'logo' => $logo,
            'url' => $this->url,
            'support' => $support,
            'company_name' => $companyName,
            'mailing_name' => $companyUser
        ];

        return $this->subject('Reset Your Password !')
            ->view('Email.NewUser.ResetPassword')
			->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->with('mailData', $mailData);
    }
}
