<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaService
{
    private $originalPath;
    private $thumbPath;

    public function __construct()
    {
        $this->originalPath = "media/" . auth()->user()->id . "/original";
        $this->thumbPath = "media/" . auth()->user()->id . "/thumb";
    }

    public function store($request)
    {
        $mediaName = md5(Str::random(40) . microtime()) . "." . $request->media->getClientOriginalExtension();

        $mediaOriginalPath = request('media')->storeAs($this->originalPath, $mediaName); // save original at storage/app/media/{id}/original/{name}

        $mediaThumbPath = request('media')->storeAs($this->thumbPath, $mediaName); // save thumbnail at storage/app/media/{id}/thumb/{name}

        Image::make(storage_path("app/{$mediaThumbPath}"))->fit(120, 120)->save(); // resize image thumbnail

        return auth()->user()->media()->create([
            'name' => $mediaName,
            'original_path' => $mediaOriginalPath,
            'thumb_path' => $mediaThumbPath,
        ]);
    }

    public function delete($path)
    {
        Storage::disk('local')->delete($path);
    }

    public function destroy($media)
    {
        $exist = DB::table('media_post')->where(['media_id' => $media->id])->first(['id']);
        if ($exist) {
            return ['success' => false, 'message' => "media file #{$media->id} linked to some posts please delete posts first"];
        }
        $media->delete();
        return ['success' => true];
    }
}
