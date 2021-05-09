<?php

namespace App\Jobs;

use App\Events\PostPublishEvent;
use App\PostaBot\Facades\Publisher;
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
        event(new PostPublishEvent($this->post, 'success'));
    }

    public function failed()
    {
        event(new PostPublishEvent($this->post, 'danger'));
    }
}
