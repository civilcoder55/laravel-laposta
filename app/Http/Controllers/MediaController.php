<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $user_media = auth()->user()->media()->get(['id', 'name']);
        return view('media', compact('user_media'));
    }


    public function store(Request $request)
    {


        $data = request()->validate([
            'media' => ['required', 'image', 'mimes:jpeg,png,jpg'],
        ]);
        $userId = auth()->user()->id;
        // create hash from path and original name as media name ...  file hash as unique name
        $mediaName = md5_file($data['media']->getRealPath()) . "." . $data['media']->getClientOriginalExtension();
        $mediaPath = request('media')->storeAs("media/{$userId}/original", $mediaName); // save file
        $thumbPath = request('media')->storeAs("media/{$userId}/thumb", $mediaName); // save thumbnail

        $thumb = Image::make(storage_path("app/{$thumbPath}"))->fit(120, 120); // resize image thumbnail
        $thumb->save();

        $media = auth()->user()->media()->create([
            'name' => $mediaName,
            'original_path' => $mediaPath,
            'thumb_path' => $thumbPath,
        ]);

        return response()->json(['success' => true, 'name' => $mediaName, 'id' => $media->id, 'name' => $media->name]);
    }


    public function showOriginal($mediaName)
    {
        $userId = auth()->user()->id;
        if (preg_match('/^[a-f0-9]{32}.(jpg|png|jpeg)$/i', $mediaName) && Storage::disk('local')->exists("media/{$userId}/original/{$mediaName}")) {
            return response()->file(storage_path("app/media/{$userId}/original/{$mediaName}"));
        }
        abort('404');
    }

    public function showThumb($mediaName)
    {
        $userId = auth()->user()->id;
        if (preg_match('/^[a-f0-9]{32}.(jpg|png|jpeg)$/i', $mediaName) && Storage::disk('local')->exists("media/{$userId}/thumb/{$mediaName}")) {
            return response()->file(storage_path("app/media/{$userId}/thumb/{$mediaName}"));
        }
        abort('404');
    }


    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);
        $media->delete();
        return response()->json(['success' => true]);
    }
}
