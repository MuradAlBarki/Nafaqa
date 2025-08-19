<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        $notification->markAsRead();

        // Redirect to the original URL stored in notification
        // $url = $notification->data['url'] ?? route('dashboard');

        // return redirect($url);
        return back();
    }
}
