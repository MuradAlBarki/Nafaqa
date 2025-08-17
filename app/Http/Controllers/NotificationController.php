<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        // Make sure this notification belongs to the logged-in user
        if ($notification->notifiable_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        // Redirect to the original URL stored in notification
        $url = $notification->data['url'] ?? route('dashboard');

        return redirect($url);
    }
}
