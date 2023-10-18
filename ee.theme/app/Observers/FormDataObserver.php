<?php

namespace App\Observers;

use App\Models\FormData;
use App\Utils\NotificationUtil;

class FormDataObserver
{
    /**
     * Handle the FormData "created" event.
     *
     * @param  \App\Models\FormData $formData
     * @return void
     */
    public function created(FormData $formData)
    {
        NotificationUtil::getInstance()->notifyFormSubmittedToOwner($formData);
    }

    /**
     * Handle the FormData "updated" event.
     *
     * @param  \App\Models\FormData $formData
     * @return void
     */
    public function updated(FormData $formData)
    {
        //
    }

    /**
     * Handle the FormData "deleted" event.
     *
     * @param  \App\Models\FormData $formData
     * @return void
     */
    public function deleted(FormData $formData)
    {
        //
    }

    /**
     * Handle the FormData "restored" event.
     *
     * @param  \App\Models\FormData $formData
     * @return void
     */
    public function restored(FormData $formData)
    {
        //
    }

    /**
     * Handle the FormData "force deleted" event.
     *
     * @param  \App\Models\FormData $formData
     * @return void
     */
    public function forceDeleted(FormData $formData)
    {
        //
    }
}
