<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = auth()->user()->posts()->with(['accounts', 'media'])->get();
        return view('posts.index', compact(['posts']));
    }

    public function create()
    {
        $userMedia = auth()->user()->media;
        $userAccounts = auth()->user()->accounts()->get(['name', 'type', 'id']);
        return view('posts.create', compact(['userMedia', 'userAccounts']));
    }

    public function store(PostRequest $request)
    {
        $post = auth()->user()->posts()->create(['is_draft' => (bool) $request->draft, 'message' => $request->message, 'schedule_date' => $request->schedule_date]);
        $post->media()->attach($request->media);
        $post->accounts()->attach($request->accounts);
        return redirect()->back()->with('status', 'Your Post saved successfuly');
    }

    public function edit(Post $post)
    {
        $post->load(['accounts', 'media']);
        $postMedia = array_map(function ($item) {
            return $item['id'];
        }, $post->media->toArray());

        $postAccounts = array_map(function ($item) {
            return $item['id'];
        }, $post->accounts->toArray());

        $userMedia = auth()->user()->media;
        $userAccounts = auth()->user()->accounts()->get(['name', 'type', 'id']);
        return view('posts.edit', compact(['userMedia', 'userAccounts', 'post', 'postMedia', 'postAccounts']));
    }

    public function update(PostRequest $request, Post $post)
    {

        $post->update(['is_draft' => (bool) $request->draft, 'message' => $request->message, 'schedule_date' => $request->schedule_date]);
        $post->media()->detach();
        $post->media()->attach($request->media);
        $post->accounts()->detach();
        $post->accounts()->attach($request->accounts);
        return redirect()->route('posts.index')->with('status', 'Your Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->back()->with('status', 'Your Post deleted successfully');
    }
}
