@extends('layouts.app')
@section('title', 'Media')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col mt-4">
                    <div class="card">
                        <media-uploader :user-media='{!! json_encode($userMedia) !!}'></media-uploader>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection