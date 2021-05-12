<?php

namespace App\PostaBot\PublisherProviders;

use Exception;
use Illuminate\Support\Facades\Http;

class Facebook
{
    public function publishPost($post, $account)
    {
        try {
            $uid = $account->uid;
            $message = $post->message;
            $token = $account->token;

            if ($post->media->count() > 0) {
                $url = "https://graph.facebook.com/$uid/feed?message=$message&access_token=$token";

                foreach ($post->media as $index => $media) {
                    $id = $this->uploadSinglePhoto($uid, $token, $media->original_path);
                    $url .= "&attached_media%5B$index%5D=%7B%22media_fbid%22%3A%22$id%22%7D";
                }
                $response = Http::post($url);

            } else {
                $response = Http::post("https://graph.facebook.com/$uid/feed?message=$message&access_token=$token");
            }
        } catch (\Throwable $th) {
            throw new Exception("internal error happened");
        }

        if (!$response->successful()) {
            throw new Exception($response->json()['error']['message']);
        }

    }

    private function uploadSinglePhoto($uid, $token, $path)
    {
        $response = Http::attach('attachment', file_get_contents(storage_path("app/{$path}")), 'photo.jpg')->post("https://graph.facebook.com/$uid/photos", ['access_token' => $token, 'published' => false]);
        return $response->json()['id'];
    }

}
