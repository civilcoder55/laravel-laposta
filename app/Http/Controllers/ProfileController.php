<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileInfoRequest;
use App\Http\Requests\ProfilePassworfRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $s = auth()->user()->session()->orderBy('last_activity', 'desc')->get()->toArray();
        $other_sessions = array_filter($s, function ($item) use (&$current_session) {
            if (Session::getId() == $item['id']) {
                $current_session = $item;
                return false;
            }
            return true;
        });
        return view('profile', compact('other_sessions', 'current_session'));
    }

    public function update(ProfileInfoRequest $request)
    {

        if (request('avatar')) {
            $oldImagePath = auth()->user()->profile->image;
            Storage::disk('local')->delete("public/{$oldImagePath}");
            $imagePath = $request->avatar->store('profile', 'public');
            Image::make(public_path("storage/{$imagePath}"))->fit(128, 128)->save();
            auth()->user()->profile->update(['image' => $imagePath]);
        }

        auth()->user()->update($request->validated());
        return redirect("/profile")->with('status', 'Your information updated successfully');
    }

    public function updatePassword(ProfilePassworfRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->password)]);
        return redirect("/profile")->with('status', 'Your password updated successfully');
    }

    public function destroy($id)
    {
        $session = auth()->user()->session()->where(['pub_id' => $id])->first();
        if ($session) {
            $this->authorize('delete', $session);
            Session::getHandler()->destroy($session->id);
            return redirect("/profile")->with('status', 'Session destroyed successfully');
        }
        return redirect("/profile");

    }
}
