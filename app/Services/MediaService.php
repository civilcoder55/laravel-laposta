<?php

namespace App\Services;

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
        $media = [];
        foreach ($request->file('media') as $file) {
            $mediaName = md5(Str::random(40) . microtime()) . "." . $file->getClientOriginalExtension();
            $mediaOriginalPath = $file->storeAs($this->originalPath, $mediaName); // save original at storage/app/media/{id}/original/{name}
            $mediaThumbPath = $file->storeAs($this->thumbPath, $mediaName); // save thumbnail at storage/app/media/{id}/thumb/{name}
            Image::make(storage_path("app/{$mediaThumbPath}"))->fit(120, 120)->save(); // resize image thumbnail
            $m = auth()->user()->media()->create([
                'name' => $mediaName,
                'original_path' => $mediaOriginalPath,
                'thumb_path' => $mediaThumbPath,
            ]);
            $media[] = ['id' => $m->id, 'name' => $mediaName];
        }

        return $media;
    }

    public function delete($media)
    {
        if ($media->posts()->where(['draft' => 0, 'locked' => 0])->count() != 0) {
            return ['success' => false, 'message' => "Media #$media->id attached to some queued posts , please delete posts first"];
        }
        $media->delete();
        return ['success' => true];
    }
}
