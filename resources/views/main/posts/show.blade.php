@extends('layouts.app')
@section('title', 'Post Preview')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            @if (session('status'))
            <div class="row">
                <div class="col mt-3">
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
            @endif
            @if ($errors->any())
            <div class="row">
                <div class="col mt-3">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-sm-6 col-lg-4 col-md-4 mt-5">
                    <div class="card card-widget">
                        <div class="card-header">
                            <div class="user-block">
                                <img class="img-circle" src="/images/avatar.png" alt="User Image" />
                                <span class="username"><a>Status : {{ $post->status }}</a></span>
                                <span class="description">publish_date :
                                    {{$post->schedule_date}}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="post-card-p">{{ $post->message }}</div>
                            <div class="card-body">
                                <div id="carousel" class="carousel slide" data-interval="false">
                                    <ol class="carousel-indicators">
                                        @forelse ($post->media as $i => $media )
                                        <li data-target="#carousel" data-slide-to="{{ $i }}"
                                            class="{{ $i == 0 ? 'active' : "" }}">
                                        </li>
                                        @empty
                                        <li data-target="#carousel" data-slide-to="0" class="active"></li>
                                        @endforelse
                                    </ol>

                                    <div class="carousel-inner">

                                        @forelse ($post->media as $i => $media )
                                        <div class="carousel-item {{ $i == 0 ? "active" : "" }}">
                                            <img src="{{ route('media.show.original', $media->name ) }}"
                                                class="d-block w-100">
                                        </div>
                                        @empty
                                        <div class="carousel-item active">
                                            <img src="/storage/posts/default.jpg" class="d-block w-100">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5 style="color: black">No media or just deleted by you
                                                </h5>
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                    <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                            <br>
                            <br>
                        </div>
                        <div class="card-footer d-flex align-items-baseline">
                            <form action="{{ route('posts.destroy', $post->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-default btn-sm "><i class="fas fa-trash-alt"></i>
                                    Delete Post
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mt-5">
                    <div class="card card-widget">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bullhorn"></i>
                                Logs
                            </h3>
                        </div>
                        <div class="card-body overflow-auto">
                            @foreach ($post->logs as $log)
                            <div class="callout callout-{{ $log['status'] }}">
                                <h5>{{ $log['status'] }}</h5>
                                <p>{{ $log['message'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection