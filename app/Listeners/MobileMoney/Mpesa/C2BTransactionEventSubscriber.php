<?php

namespace App\Listeners\MobileMoney\Mpesa;

use App\Events\MobileMoney\Mpesa\TransactionCompleted;
use App\Models\User;
use App\Notifications\SendTransactionCompletedNotification;
use Illuminate\Events\Dispatcher;

class C2BTransactionEventSubscriber
{
    /**
     * Handle completion of transactions
     *
     * @param $event
     * @return void
     * @author Alvin G. Kaburu <geekaburu@amprest.co.ke>
     */
    public function handleTransactionCompleted($event): void
    {
        //  Get the payload
        $payload = $event->payload;

        //  Get the project
        $project = $event->project;

        //  Get the primary user
        $user = User::where('email', 'dev@amprest.co.ke')->first();

        //  if the user is defined
        if($user) $user->notify(new SendTransactionCompletedNotification(
            $payload, $project
        ));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(TransactionCompleted::class, [ 
            C2BTransactionEventSubscriber::class, 'handleTransactionCompleted' 
        ]);
    }
}