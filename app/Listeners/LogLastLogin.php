<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LogLastLogin
{
    /**
     * Create the event listener.
     */


    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $event->user->update([
            'last_login_at' => now(),
        ]);
    }

//    public function handle(Login $event): void
//    {
//        $event->user->update([
//            'updated_at' => now(), // we testen hiermee of de update werkt
//        ]);
//    }

//    public function handle(Login $event): void
//    {
//        dd('Listner werkt')($event->user);
//    }
}
