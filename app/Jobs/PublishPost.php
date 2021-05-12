<?php

namespace App\Jobs;

use App\Events\PostPublishEvent;
use App\PostaBot\Facades\Publisher;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $post;
    private $account;

    public function __construct($post, $account)
    {
        $this->post = $post;
        $this->account = $account;
    }

    public function handle()
    {
        Publisher::driver($this->account->platform)->publishPost($this->post, $this->account);
        event(new PostPublishEvent($this->post, $this->account));
    }

    public function failed(Exception $exception)
    {
        $error = $exception->getMessage();
        event(new PostPublishEvent($this->post, $this->account, $error));
    }
}
