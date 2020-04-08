<?php

namespace App\Listeners\OrderListeners;

use App\Events\CheckoutOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\OrderSummary;


class SendEmailSummary
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
     * @param  CheckoutOrder  $event
     * @return void
     */
    public function handle(CheckoutOrder $order)
    {
        request()->user()->notify(new OrderSummary($order->order));
    }
}