<?php

namespace PMW\Listeners;

use PMW\Events\UserTerdaftar;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use PMW\Mail\RegisterMail;

class KirimEmailRegistrasi
{

    /**
     * Handle the event.
     *
     * @param  UserTerdaftar  $event
     * @return void
     */
    public function handle(UserTerdaftar $event)
    {
        $user = $event->user;

        Mail::to($user->email)->send(new RegisterMail($user, $event->password));
    }

}
