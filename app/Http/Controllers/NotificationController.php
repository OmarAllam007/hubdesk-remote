<?php

namespace App\Http\Controllers;

use App\Helpers\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    function index()
    {
        $notifications = auth()->user()->notifications()->paginate(10);

        $notifications->getCollection()->transform(function ($notification) {
            return new UserNotification($notification);
        });

        return view('notifications.index', compact('notifications'));
    }

    function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return redirect()->back();
    }

    function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        $userNotification = new UserNotification($notification);
        return redirect()->to($userNotification->url);
    }
}
