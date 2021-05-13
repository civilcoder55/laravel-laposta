<?php

namespace App\PostaBot\PublisherProviders;

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter
{
    public $connection;
    public function publishPost($post, $account)
    {
        try {
            $consumer_key = config('services.twitter.client_id');
            $consumer_secret = config('services.twitter.client_secret');
            $token = $account->token;
            $secret = $account->secret;

            $this->connection = new TwitterOAuth($consumer_key, $consumer_secret, $token, $secret);
            $this->connection->setTimeouts(30, 60);
            // split messages every 280 chars as twitter max chars is 280
            $messages_array = str_split($post->message, 280);

            // upload media
            $media_ids = "";
            foreach ($post->media as $index => $media) {
                $id = $this->uploadSinglePhoto($media->original_path);
                $media_ids .= "$id,";
            }

            //split every 4 media as twitter max media per post is 4
            $media_ids_array = array_map(function ($arr) {
                return implode(",", $arr);
            }, array_chunk(explode(",", $media_ids), 4));

            $tweets = array_map(function ($message, $media_ids) {
                $result = [];
                if ($message) {$result['status'] = $message;}
                if ($media_ids) {$result['media_ids'] = $media_ids;}
                return $result;
            }, $messages_array, $media_ids_array);

            $parent = "";
            foreach ($tweets as $tweet) {
                if ($parent) {
                    $tweet = array_merge($tweet, ['in_reply_to_status_id' => $parent]);
                }

                $result = $this->connection->post('statuses/update', $tweet);

                if ($this->connection->getLastHttpCode() != 200) {
                    throw new \Exception($this->connection->getLastBody()->errors[0]->message);
                }

                $parent = $result->id;
            }

        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

    }

    private function uploadSinglePhoto($path)
    {
        $media = $this->connection->upload('media/upload', ['media' => storage_path("app/{$path}")]);
        return $media->media_id_string;
    }

}
