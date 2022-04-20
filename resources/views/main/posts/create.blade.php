@extends('layouts.app')
@section('title', 'Create Post')
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
                        <media-uploader :user-media='@json($userMedia)' :already-selected-media='@json(old("media"))'>
                        </media-uploader>
                    </div>
                </div>
                <div class="col-sm-6 mt-5">
                    <div class="card">
                        <post-creator :user-accounts='@json($userAccounts)' :old-accounts='@json(old("accounts"))'
                            :old-media='@json(old("media"))' :old-message='"{{ old("message") }}"'
                            :old-date='"{{ old("schedule_date") }}"' :old-draft='"{{ old("draft") }}"'
                            :csrf-token='"{{ csrf_token() }}"'>
                        </post-creator>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection