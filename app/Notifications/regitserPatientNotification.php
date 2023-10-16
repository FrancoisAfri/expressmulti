<?php

namespace App\Notifications;

use App\Traits\CompanyIdentityTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class regitserPatientNotification extends Notification
{
    use Queueable, CompanyIdentityTrait;

    private $url;
    private $user;
    private $note;
    private $date;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($url, $user, $note, $date)
    {
        $this->url = $url;
        $this->user = $user;
        $this->note = $note;
        $this->date = $date;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $companyDetails = $this->CompanyIdentityDetails();
        $companyEmail = $companyDetails['mailing_address'];
        $companyUser = $companyDetails['mailing_name'];
        $companyName = $companyDetails['company_name'];
        $support = $companyDetails['support'];

        return (new MailMessage)
            ->from($companyEmail, $companyUser)
            ->subject(__('Appointment Confirmation!'))
            ->greeting('Hello ' . $this->user->first_name)
            ->line(__('You have an appointment for' . ' ' . $this->note . ' ' . 'with' . ' ' . $companyName. ' '  . 'on  '))
            ->line(__(' ' . $this->date))
            ->line(__('Please click here Complete Profile'))
            ->action(__('Complete Your Profile'), $this->url)
            ->line(__('If you are having any issues or have any questions, please contact us on' . ' ' . $support))
            ->line(__('Kind regards' ))
            ->line(__($companyUser));
    }

    public function toDatabase($notifiable)
    {

        return [
            'id' => $this->user->id,
            'name' => $this->user->first_name,
            'email' => $this->user->email,
        ];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

//        return [
//
//        ];
    }
}
