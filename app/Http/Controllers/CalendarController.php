<?php

namespace App\Http\Controllers;

class CalendarController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = auth()->user()->getScheduledPosts();
        return view('calendar', compact('posts'));
    }

}
