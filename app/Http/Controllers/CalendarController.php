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
        $posts = auth()->user()->posts()->where(['is_draft' => 0 , 'success'=>null])->get();
        return view('calendar', compact('posts'));
    }

}
