<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Repositories\Facades\UserRepository;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{

    public function index()
    {
        $allNotifications = UserRepository::getNotifications();
        return view('main.notifications', compact('allNotifications'));
    }

    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);
        $notification->delete();
        Cache::forget('notifications.' . auth()->user()->id);
        return redirect()->route('notifications.index')->with('status', "Notification deleted successfully");
    }

    public function destroyAll()
    {
        auth()->user()->notifications()->delete();
        Cache::forget('notifications.' . auth()->user()->id);
        return redirect()->route('notifications.index')->with('status', "All notifications deleted successfully");
    }
}
