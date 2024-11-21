<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class Notifications extends Controller
{
    use ResponseTrait;

    // ?todo return all notification for users
    public function notification(Request $request)
    {
        try {
            $notifications = DatabaseNotification::where('notifiable_id', auth()->user()->id)->get();
            return $this->returnData("notify", $notifications);
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }

    // ?todo read notification for users
    public function readNotification(Request $request, $id)
    {
        $notification = DatabaseNotification::find($id);

        if ($notification && $notification->notifiable_id === auth()->id()) {
            $notification->markAsRead();
        }
        return $this->returnSuccessMessage("Read Success", "R000");
    }

}
