<?php

namespace App\Console;

use App\Jobs\PublishPost;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        //
    ];
    protected function schedule(Schedule $schedule)
    {
        // command to run every minute to get every posts that should be published between now and after a minute
        $schedule->call(function () {
            $now = Carbon::now()->timestamp;
            $posts = Post::with(['accounts', 'media'])->where(['draft' => 0, 'locked' => 0])
                ->whereBetween('schedule_date', [$now + 1, $now + 60])->get();
            foreach ($posts as $post) {
                foreach ($post->accounts as $account) {
                    PublishPost::dispatch($post, $account)->delay($post->schedule_date);
                }
            }
        })->everyMinute();

    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
