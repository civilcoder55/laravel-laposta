@extends('layouts.app')

@section('title', 'Posts')
@section('stylesheet')

@endsection
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
            <div class="row">
                @foreach ($posts as $index => $post)
                <div class="col-sm-3 mt-2 mb-2 ">
                    <div class="card card-widget h-100 w-80">
                        <div class="card-header">
                            <div class="user-block">
                                <img class="img-circle" src="/images/avatar.png" alt="User Image" />
                                <span class="username"><a>{{ $post->status }} post</a></span>
                                <span class="description">{{ $post->accounts_count }} account/s Linked</span>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="post-card-p">{{ $post->message }}</div>
                            <div class="card-body">
                                <div id="carousel_{{ $index }}" class="carousel slide" data-interval="false">
                                    <ol class="carousel-indicators">

                                        @forelse ($post->media as $i => $media )
                                        <li data-target="#carousel_{{ $index }}" data-slide-to="{{ $i }}"
                                            class="{{ $i == 0 ? 'active' : "" }}"></li>
                                        @empty
                                        <li data-target="#carousel_{{ $index }}" data-slide-to="0" class="active"></li>
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
                                        </div>
                                        @endforelse


                                    </div>
                                    <a class="carousel-control-prev" href="#carousel_{{ $index }}" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel_{{ $index }}" role="button"
                                        data-slide="next">
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
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-default btn-sm "><i class="fas fa-trash-alt"></i>
                                    Delete Post</button>
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-default btn-sm "><i
                                        class="far fa-clock"></i>
                                    Edit
                                    & Schedule</a>

                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-tools">
                {{ $posts->links('layouts.pagination') }}
            </div>
        </div>
    </section>
</div>

@endsection

@section('script')

@endsection