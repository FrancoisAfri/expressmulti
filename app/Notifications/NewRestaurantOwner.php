<?php

namespace App\Notifications;

use App\Traits\CompanyIdentityTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRestaurantOwner extends Notification
{
    use Queueable, CompanyIdentityTrait;

    public $token;
    public $temp_password;
    private $user;
    private $domain;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $temp_password, $user, $domain)
    {
        $this->token = $token;
        $this->temp_password = $temp_password;
        $this->user = $user;
        $this->domain = $domain;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
		$url = $this->domain."/reset-password/".$this->token."?email=".$notifiable->getEmailForPasswordReset();
		
		$companyDetails = $this->CompanyIdentityDetails();
        $companyEmail = $companyDetails['mailing_address'];
        $companyUser = $companyDetails['mailing_name'];
        $companyName = $companyDetails['company_name'];
        $support = $companyDetails['support'];

        return (new MailMessage)
            ->from($companyEmail, $companyUser)
            ->subject(__('New Account !'))
            ->greeting('Hello ' . $this->user->first_name)
            ->subject(__('New Account Created!'))
            ->line(__('You are receiving this email because a new account has been created in your name.'))
            ->line(__('You temporary password is ' . $this->temp_password))
            ->line(__('You will be asked to change the password .'))
            ->action(__('Activate Account'), $url)
            ->line(__('If you did not request a account , no further action is required.'))
            ->line(__('Kind regards'))
            ->line(__($companyUser));
			
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->first_name,
            'email' => $this->user->email,
        ];
    }
}
