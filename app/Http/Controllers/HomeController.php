<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        //get posts statistics count with one query
        $statistics = auth()->user()->posts()->select(
            array(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN is_draft = 0 && success IS NULL THEN 1 ELSE 0 END) AS scheduled"),
                DB::raw("SUM(CASE WHEN is_draft = 0 && success = 1 THEN 1 ELSE 0 END) AS success"),
                DB::raw("SUM(CASE WHEN is_draft = 0 && success = 0 THEN 1 ELSE 0 END) AS failed")
            )
        )->first();
        $accounts = auth()->user()->accounts()->whereNotNull('parent_id')->count();
        return view('home', compact(['accounts', 'statistics']));
    }
}
