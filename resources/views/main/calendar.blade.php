@extends('layouts.app')

@section('title', 'Calendar')
@section('stylesheet')
<link rel="stylesheet" href="/css/calendar.css" />
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div id='calendar'></div>
            <br>
        </div>
    </section>
</div>
@endsection

@section('script')
<script src="/js/calendar.js"></script>
<script>
    $(function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                        @foreach ($posts as $post)
                    {
                        title: '#{{$post->id}} Post',
                        start: '{{$post->schedule_date}}',
                        end: '',
                        url: "{{route('posts.edit',$post->id)}}",
                    },
                    @endforeach
                ],
            });
            calendar.render();
        });
</script>
@endsection