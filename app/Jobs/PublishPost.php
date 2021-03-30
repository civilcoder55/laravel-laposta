<?php

namespace App\Jobs;

use App\Events\PostPublishEvent;
use App\PostaBot\Publisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $post;
    private $account;

    public function __construct($post, $account)
    {
        $this->post = $post;
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Publisher::driver($this->account->platform)->publishPost($this->post, $this->account);
        event(new PostPublishEvent($this->post, true));
    }


    public function failed()
    {
        $post = $this->post;
        event(new PostPublishEvent($post, false));
    }
}
