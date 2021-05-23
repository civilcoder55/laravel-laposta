<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use WhichBrowser\Parser;

class ProfileService
{
    public static function storeAvatar($request)
    {
        self::deleteOldAvatar();
        $avatarPath = $request->_avatar->store('profile', 'public');
        Image::make(public_path("storage/{$avatarPath}"))->fit(128, 128)->save();
        auth()->user()->update(['avatar' => $avatarPath]);
    }

    private static function deleteOldAvatar()
    {
        $oldAvatarPath = auth()->user()->avatar;
        Storage::disk('local')->delete("public/{$oldAvatarPath}");
    }

    public static function formatSessions($s)
    {
        return array_map(function ($session) {
            $agent = new Parser($session['user_agent']);
            $session['browser'] = $agent->browser->toString();
            $session['os'] = $agent->os->toString();
            $session['device'] = $agent->device->type;
            $session['status'] = (Session::getId() == $session['id']) ? 'This session' : '';
            $session['last_activity'] = ($session['status'] == 'This session') ? 'Active Now' : Carbon::createFromTimestamp($session['last_activity'])->diffForHumans();
            return $session;
        }, $s);
    }
}
