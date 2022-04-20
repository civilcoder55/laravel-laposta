@extends('layouts.app')
@section('title', 'Notifications')
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
            @if (session('error'))
            <div class="row">
                <div class="col mt-3">
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
            @endif
            <div class="row justify-content-center">
                <div class="col mt-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-baseline">
                            <h3 class="card-title">Notifications</h3>
                            <div style="margin-left: auto">
                                <form id="delAllForm" action="{{ route('notifications.destroy.all') }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <label class="btn btn-sm btn-primary" style="color:white"
                                    onclick="document.getElementById('delAllForm').submit()"> <i
                                        class="nav-icon fas fa-trash-alt"></i>
                                    Delete All </label>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Message</th>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allNotifications as $notification)
                                    @if ($notification->data['type'] == 'login')
                                    <tr>
                                        <td>Login</td>
                                        <td>
                                            {{ $notification->data['message'] }}</td>
                                        @elseif($notification->data["type"] == 'post')
                                    <tr>
                                        <td>Post</td>
                                        <td>{{ $notification->data['message'] }}</td>
                                        @endif
                                        <td>{{ $notification->created_at}}
                                        </td>
                                        <td>
                                            <form action="{{ route('notifications.destroy', [$notification->id]) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn" type="submit"
                                                    style="margin-left: 18px ;padding:0 0;color:#007bff"><i
                                                        class="nav-icon fas fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($allNotifications->hasPages())
                        <div class="card-footer clearfix">
                            {{ $allNotifications->links('layouts.pagination') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection