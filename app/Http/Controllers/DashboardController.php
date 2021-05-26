<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = UserRepository::getStatistics();
        return view('main.dashboard', compact('statistics'));
    }

    public function calendar()
    {
        $posts = UserRepository::getScheduledPosts();
        return view('main.calendar', compact('posts'));
    }
}
