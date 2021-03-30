<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostPublishEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $post;
    public $status;

    public function __construct($post, $status)
    {
        $this->post = $post;
        $this->status = $status;
    }

}
