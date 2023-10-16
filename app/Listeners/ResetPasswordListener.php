<?php

namespace App\Listeners;

use App\Models\PasswordHistory;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ResetPasswordListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PasswordReset $passwordReset
     * @return void
     */
    public function handle(PasswordReset $passwordReset)
    {
        $passwordHistory = PasswordHistory::create([
            'user_id' => $passwordReset->user->id,
            'password' => $passwordReset->user->password
        ]);
    }
}
