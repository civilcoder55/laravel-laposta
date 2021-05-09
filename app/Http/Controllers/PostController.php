<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Repositories\Facades\UserRepository;
use App\Services\Facades\PostService;

class PostController extends Controller
{

    public function index()
    {
        $posts = UserRepository::getPosts();
        return view('main.posts.index', compact(['posts']));
    }

    public function create()
    {
        $userMedia = UserRepository::getMedia();
        $userAccounts = UserRepository::getAccounts(['name', 'type', 'id']);
        return view('main.posts.create', compact(['userMedia', 'userAccounts']));
    }

    public function store(PostRequest $request)
    {
        PostService::store($request);
        return redirect()->route('posts.index')->with('status', 'Your Post saved successfuly');
    }

    public function edit(Post $post)
    {
        $this->authorize('edit', $post);
        $post->load(['accounts:id,name,type', 'media:id,name']);
        $userAccounts = UserRepository::getAccounts(['name', 'type', 'id']);
        $userMedia = UserRepository::getMedia();
        return view('main.posts.edit', compact(['userAccounts', 'userMedia', 'post']));
    }

    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        PostService::update($request, $post);
        return redirect()->route('posts.edit', $post->id)->with('status', 'Your Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('posts.index')->with('status', 'Your Post deleted successfully');
    }
}
