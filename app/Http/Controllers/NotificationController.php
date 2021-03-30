<?php

namespace App\Http\Controllers;


class NotificationController extends Controller
{

    public function index()
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(15);
        return view('notifications', compact('notifications'));
    }


    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->where(['id' => $id])->first();
        if ($notification) {
            $notification->delete();
            return redirect(route('notifications.index'))->with('status', "Notification deleted successfully");
        }
        return redirect(route('notifications.index'));
    }

    public function destroyAll()
    {
        auth()->user()->notifications()->delete();
        return redirect(route('notifications.index'))->with('status', "All notifications deleted successfully");
    }
}
