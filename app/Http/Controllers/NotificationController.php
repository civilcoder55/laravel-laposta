<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{

    public function index()
    {
        $allNotifications = UserRepository::getNotifications(); //"notifications" variable name already used in view composer
        return view('main.notifications', compact('allNotifications'));
    }

    public function read(Notification $notification)
    {
        $this->authorize('read', $notification);
        $notification->markAsRead();
        Cache::forget('notifications.' . auth()->user()->id);
        return response()->json(['success' => true]);
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
