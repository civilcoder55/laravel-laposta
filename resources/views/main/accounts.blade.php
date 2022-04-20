@extends('layouts.app')
@section('title', 'Accounts')
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
                            <h3 class="card-title">Accounts</h3>
                            <div style="margin-left: auto">
                                <label class="btn btn-sm" style="background-color: #3b5998;color:white"
                                    onclick="location.href = '{{ route('accounts.connect', 'facebook') }}';"> <i
                                        class="nav-icon fab fa-facebook-f pr-1"></i>
                                    Add Facebook Account </label>
                                {{-- <label class="btn btn-sm"
                                        onclick="location.href = '{{ route('accounts.connect', 'instagram') }}';"
                                style="background: #f09433;
                                background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366
                                75%, #bc1888 100%);
                                background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366
                                75%,#bc1888 100%);
                                background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366
                                75%,#bc1888 100%);
                                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f09433',
                                endColorstr='#bc1888',GradientType=1 );color:white">
                                <i class="nav-icon fab fa-instagram pr-1"></i>
                                Add instagram Account </label> --}}
                                <label class="btn btn-sm" style="background-color: #00acee;color:white"
                                    onclick="location.href = '{{ route('accounts.connect', 'twitter') }}';"> <i
                                        class="nav-icon fab fa-twitter pr-1"></i>
                                    Add Twitter Account </label>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Platform</th>
                                        <th>Type</th>
                                        <th>Added</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $account)
                                    <tr>
                                        <td>{{ $account->uid }}</td>
                                        <td>{{ $account->name }}</td>
                                        <td>{{ $account->platform }}</td>
                                        <td>{{ $account->type }}</td>
                                        <td>{{ $account->created_at->diffForHumans() }}</td>
                                        <td>
                                            <form action="{{ route('accounts.destroy',$account->id) }}" method="post">
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection