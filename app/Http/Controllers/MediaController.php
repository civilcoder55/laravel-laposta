<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Models\Media;
use App\Repositories\UserRepository;
use App\Services\MediaService;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{

    public function index()
    {
        $userMedia = UserRepository::getMedia();
        return view('main.media', compact('userMedia'));
    }

    public function store(MediaRequest $request)
    {
        $media = MediaService::store($request);
        return response()->json(['success' => true, 'media' => $media]);
    }

    public function showOriginal($mediaName)
    {
        $userId = auth()->id();
        if (preg_match('/^[a-f0-9]{32}.(jpg|png|jpeg)$/i', $mediaName) && Storage::disk('local')->exists("media/{$userId}/original/{$mediaName}")) {
            return response()->file(storage_path("app/media/{$userId}/original/{$mediaName}"));
        }
        abort('404');
    }

    public function showThumb($mediaName)
    {
        $userId = auth()->id();
        if (preg_match('/^[a-f0-9]{32}.(jpg|png|jpeg)$/i', $mediaName) && Storage::disk('local')->exists("media/{$userId}/thumb/{$mediaName}")) {
            return response()->file(storage_path("app/media/{$userId}/thumb/{$mediaName}"));
        }
        abort('404');
    }

    public function destroy(Media $media)
    {
        $this->authorize('delete', $media);
        $response = MediaService::delete($media);
        return response()->json($response);
    }
}
