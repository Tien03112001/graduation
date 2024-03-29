<?php

namespace App\Observers;

use App\Models\Order;
use App\Utils\NotificationUtil;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order $order
     * @return void
     */
    public function created(Order $order)
    {
        NotificationUtil::getInstance()->notifyOrderCreatedToCustomer($order);
        NotificationUtil::getInstance()->notifyOrderCreatedToOwner($order);
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order $order
     * @return void
     */
    public function updated(Order $order)
    {
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
