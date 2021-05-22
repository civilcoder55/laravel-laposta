<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileInfoRequest;
use App\Http\Requests\ProfilePasswordRequest;
use App\Repositories\UserRepository;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index()
    {
        $s = UserRepository::getSessions();
        $sessions = ProfileService::formatSessions($s);
        $socialStatus = UserRepository::getSocialStatus();
        return view('main.profile', compact('sessions', 'socialStatus'));
    }

    public function update(ProfileInfoRequest $request)
    {
        if ($request->_avatar) {
            ProfileService::storeAvatar($request);
        }
        auth()->user()->update($request->validated());
        return redirect()->route('profile.index')->with('status', 'Your information updated successfully');
    }

    public function updatePassword(ProfilePasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->password)]);
        return redirect()->route('profile.index')->with('status', 'Your password updated successfully');
    }

    public function destroySession($id)
    {
        $session = auth()->user()->session()->where(['pub_id' => $id])->firstOrFail();
        $this->authorize('delete', $session);
        Session::getHandler()->destroy($session->id);
        return redirect()->route('profile.index')->with('status', 'Session destroyed successfully');
    }
}
