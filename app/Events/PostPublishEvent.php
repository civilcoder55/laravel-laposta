<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostPublishEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $account;
    public $error;
    public function __construct($post, $account, $error = false)
    {
        $this->post = $post;
        $this->account = $account;
        $this->error = $error;
    }

}
