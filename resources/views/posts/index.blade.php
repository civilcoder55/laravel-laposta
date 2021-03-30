@extends('layouts.app')

@section('title', 'Posts')
@section('stylesheet')
    <link rel="stylesheet" href="/css/images-grid.css">
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
                        <div class="col-sm-4 mt-5 ">
                            <div class="card card-widget h-100 w-80">
                                <div class="card-header">
                                    <div class="user-block">
                                        <img class="img-circle" src="/images/avatar.png" alt="User Image" />
                                        <span class="username"><a>{{ $post->success && !is_null($post->success) ? 'Successfully' : '' }}
                                                {{ !$post->success && !is_null($post->success) ? 'Failed' : '' }}
                                                {{ $post->is_draft ? 'Draft' : 'Scheduled' }}
                                                Post</a></span>
                                        <span
                                            class="description">{{ $post->is_draft ? 'Draft' : $post->schedule_date }}</span>
                                    </div>

                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">

                                    <div class="post-card-p">{{ $post->message }}</div>
                                    <div class="card-body">
                                        <div id="gallery_{{ $index }}" data-images=@json(array_map(function ($media)
                                            { return route('media.show.original', $media['name'] );},$post->
                                            media->toArray()))>
                                        </div>

                                    </div>



                                    <br>
                                    <br>
                                </div>
                                <div class="card-footer d-flex align-items-baseline">
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-default btn-sm "><i
                                                class="fas fa-trash-alt"></i>
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

            </div>
        </section>
    </div>

@endsection

@section('script')
    <script src="/js/images-grid.js"></script>
    <script>
        $('[id^="gallery_"]').each(function() {
            $(`#${this.id}`).imagesGrid({
                images: $(this).data('images'),
                align: false,
                cells: 4,
            });
        });

    </script>
@endsection
