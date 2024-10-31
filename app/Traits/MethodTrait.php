<?php
namespace App\Traits;

use App\Models\User;
use App\Notifications\SuccessNotify;
use Illuminate\Support\Facades\Notification;

trait MethodTrait
{

    // ?todo send notification

    protected function successNotification(User $user, $type, $msg)
    {
        return Notification::send($user, new SuccessNotify($type, $msg));
    }



}