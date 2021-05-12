<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileService
{
    public function storeAvatar($request)
    {
        $this->deleteOldAvatar();
        $avatarPath = $request->_avatar->store('profile', 'public');
        Image::make(public_path("storage/{$avatarPath}"))->fit(128, 128)->save();
        auth()->user()->update(['avatar' => $avatarPath]);
    }

    public function deleteOldAvatar()
    {
        $oldAvatarPath = auth()->user()->avatar;
        Storage::disk('local')->delete("public/{$oldAvatarPath}");
    }

    public function formatSessions($s)
    {
        $other_sessions = array_filter($s, function ($item) use (&$current_session) {
            if (Session::getId() == $item['id']) {
                $current_session = $item;
                return false;
            }
            return true;
        });

        return [
            "current_session" => $current_session,
            "other_sessions" => $other_sessions,
        ];
    }
}
