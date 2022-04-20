@extends('layouts.app')
@section('title', 'Edit Post')
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
                <div class="col-sm-6 mt-5">
                    <div class="card">
                        <media-uploader :user-media='@json($userMedia)'
                            :already-selected-media='@json($post->media_ids)'>
                        </media-uploader>
                    </div>
                </div>
                <div class="col-sm-6 mt-5">
                    <div class="card">
                        <post-editor :user-accounts='@json($userAccounts)'
                            :selected-accounts='@json($post->accounts_ids)' :selected-media='@json($post->media_ids)'
                            :editable-post='@json($post)' :csrf-token='"{{ csrf_token() }}"'>
                        </post-editor>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bullhorn"></i>
                                Logs
                            </h3>
                        </div>
                        <div class="card-body overflow-auto" style="height: 300px">
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