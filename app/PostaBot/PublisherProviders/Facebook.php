<?php

namespace App\PostaBot\PublisherProviders;

use Exception;
use Illuminate\Support\Facades\Http;

class Facebook
{
    public function publishPost($post, $account)
    {
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

        if (!$response->successful()) {
            $log = [[
                'status' => 'danger',
                'message' => ($e = $response->json()['error']['message']) ? $e : "error happened while publishing the post with $account->name $account->type",
            ]];
            $post->update(['status' => 'failed', 'logs' => $log]);
            throw new Exception();
        }

        $log = [[
            'status' => 'success',
            'message' => "Post published successfully at $post->schedule_date to $account->name $account->type",
        ]];
        $post->update(['status' => 'success', 'logs' => $log]);

    }

    private function uploadSinglePhoto($uid, $token, $photo)
    {
        $response = Http::attach('attachment', file_get_contents(storage_path("app/{$photo}")), 'photo.jpg')->post("https://graph.facebook.com/$uid/photos", ['access_token' => $token, 'published' => false]);
        return $response->json()['id'];
    }

}
