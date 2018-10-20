<?php

namespace Acme\Domains\Users\Traits;

use Illuminate\Notifications\Notifiable;

trait HasNotifications
{
    use Notifiable;

    /**
     * Route notifications for the Twilio channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForTwilio($notification)
    {
        return $this->mobile;
    }

    /**
     * Route notifications for the Authy channel.
     *
     * @return int
     */
    public function routeNotificationForAuthy()
    {
        return $this->authy_id;
    }
}