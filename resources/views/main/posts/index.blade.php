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
                            <table class="table text-nowrap">
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
                                        <td><a href="{{ route('posts.show', $post->id) }}">{{ $post->id }}
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
                                                @method('DELETE')
                                                @if (!$post->locked)
                                                <a href="{{ route('posts.edit', $post->id) }}" class="btn action-btn"><i
                                                        class="far fa-edit"></i>
                                                </a>
                                                @endif
                                                <button class="btn action-btn" type="submit"><i
                                                        class="nav-icon fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($posts->hasPages())
                        <div class="card-footer clearfix">
                            {{ $posts->links('layouts.pagination') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection