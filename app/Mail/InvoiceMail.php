<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var mixed
     */
    private $pdfContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdfContent)
    {
        $this->pdfContent = $pdfContent;
    }

    public function build(): InvoiceMail
    {
        return $this->subject('Your Monthly Invoice')
            ->attachData($this->pdfContent, 'invoice.pdf', [
                'mime' => 'application/pdf',
            ])
            ->view('Email.invoice');
    }
}
