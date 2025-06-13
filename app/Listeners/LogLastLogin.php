<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\DB;



class LogLastLogin
{
    /**
     * Create the event listener.
     */


    /**
     *
     * Handle the event.
     */

//Tijdelijk fix om de aanroep te laten werken
    public function handle(Login $event): void
    {
        $user = \App\Models\User::find($event->user->id);

        if ($user->last_login_at == now()) {
            return;
        }


        Log::info('' );
        Log::info('last_login_at: ' . $user->last_login_at);
        Log::info('last_login_at: ' . now());


        $user->update([
            'previous_login_at' => $user->last_login_at,
            'last_login_at' => now(),
        ]);

    }




}
