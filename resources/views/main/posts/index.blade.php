@extends('layouts.app')

@section('title', 'Posts')
@section('stylesheet')
<style>
    .action-btn {
        margin-left: 18px;
        padding: 0 0;
        color: #007bff
    }

    td,
    th {
        text-align: center !important
    }

    .message-cell {
        overflow: hidden;
        max-width: 15ch;
        text-overflow: ellipsis;
    }
</style>

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
                <div class="col mt-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-baseline">
                            <h3 class="card-title">All Posts</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Message</th>
                                        <th>Media</th>
                                        <th>Accounts</th>
                                        <th>Status</th>
                                        <th>Created_At</th>
                                        <th>To_Published_At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $index => $post)
                                    <tr>
                                        <td><a href="{{ route('posts.edit', $post->id) }}">{{ $post->id }}
                                            </a></td>
                                        <td class="message-cell">{{ $post->message }}</td>
                                        <td>{{ $post->media_count }}</td>
                                        <td>{{ $post->accounts_count}}</td>
                                        <td>{{ $post->draft ? 'draft' : $post->status }}</td>
                                        <td>{{ $post->created_at }}</td>
                                        <td>{{ $post->schedule_date }}</td>
                                        <td>
                                            <form action="{{ route('posts.destroy', $post->id) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <a href="{{ $post->locked ? route('posts.review', $post->id) : route('posts.edit', $post->id) }}"
                                                    class="btn action-btn"><i class="far fa-edit"></i>
                                                </a>
                                                <button class="btn action-btn" type="submit"><i
                                                        class="nav-icon fas fa-trash-alt"></i></button>

                                            </form>
                                        </td>

                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            {{ $posts->links('layouts.pagination') }}
                        </div>
                    </div>
                </div>
            </div>


            {{-- <div class="col-sm-3 mt-2 mb-2">
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
                <li data-target="#carousel_{{ $index }}" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : "" }}">
                </li>
                @empty
                <li data-target="#carousel_{{ $index }}" data-slide-to="0" class="active"></li>
                @endforelse
            </ol>

            <div class="carousel-inner">

                @forelse ($post->media as $i => $media )
                <div class="carousel-item {{ $i == 0 ? "active" : "" }}">
                    <img src="{{ route('media.show.original', $media->name ) }}" class="d-block w-100">
                </div>
                @empty
                <div class="carousel-item active">
                    <img src="/storage/posts/default.jpg" class="d-block w-100">
                </div>
                @endforelse


            </div>
            <a class="carousel-control-prev" href="#carousel_{{ $index }}" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel_{{ $index }}" role="button" data-slide="next">
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
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-default btn-sm "><i class="far fa-clock"></i>
            Edit
            & Schedule</a>

    </form>
</div>
</div>
</div> --}}


</div>
</section>
</div>

@endsection

@section('script')

@endsection