<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function read(Request $request, $id)
    {
        $notification = $request
            ->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect($notification->data['url'] ?? back());
    }

    public function readAll(Request $request)
    {
        $request
            ->user()
            ->unreadNotifications
            ->markAsRead();

        return back();
    }
}
