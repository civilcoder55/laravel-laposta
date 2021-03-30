<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/css/fontawesome.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="/css/adminlte.css" />
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    @yield('stylesheet')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('home', '/') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <form id='logout' method="post" action="{{ route('logout') }}">
                        @csrf
                    </form>
                    <a class="nav-link" href="javascript:{}"
                        onclick="document.getElementById('logout').submit();">Logout</a>
                </li>
            </ul>

            <!-- SEARCH FORM
        <form class="form-inline ml-3">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" />
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form> -->

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    @php
                        $notifications = cache()->rememberForever('notifications.' . auth()->user()->id, function () {
                            return auth()
                                ->user()
                                ->notifications()
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();
                        });
                    @endphp
                    <a class="nav-link" data-toggle="dropdown" href="#" id="notifications_bell">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge" id="notifications_num"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="height: auto;
    max-height: 500px;
    overflow-x: hidden;">
                        <span class="dropdown-item dropdown-header">Recent
                            Notifications</span>
                        <div class="dropdown-divider" id="notifications_menu_indexer"></div>
                        @foreach ($notifications as $notification)
                            @if ($notification->data['type'] == 'login')
                                <a href="{{ route('profile.index') }}#sessionsTable" class="dropdown-item">
                                    <i
                                        class="fas fa-exclamation-triangle mr-2"></i>{{ $notification->data['message'] }}
                                    <span
                                        class="float-right text-muted text-sm">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                </a>
                                <br>
                                <div class="dropdown-divider"></div>

                            @elseif($notification->data["type"] == 'post')
                                <a href="{{ route('posts.index') }}" class="dropdown-item">
                                    <i class="fas fa-envelope mr-2"></i> {{ $notification->data['message'] }}
                                    <span
                                        class="float-right text-muted text-sm">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                </a>
                                <br>
                                <div class="dropdown-divider"></div>
                            @endif

                        @endforeach
                        <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">See All
                            Notifications</a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a style="pointer-events: none; cursor: default;" class="brand-link">
                <img src="/images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: 0.8" />
                <span class="brand-text font-weight-bolder">La Posta</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="/storage/{{ auth()->user()->profile->image ?? 'profile/default.png' }} "
                            class="img-circle elevation-2" alt="User Image" />
                    </div>
                    <div class="info">
                        <a href="{{ route('profile.index') }}" class="d-block">{{ auth()->user()->name }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('home', '/') }}"
                                class="nav-link {{ request()->is('/') || Request::is('home') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('posts.create') }}"
                                class="nav-link {{ request()->is('posts/create') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-address-card"></i>
                                <p>New Post</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('posts.index') }}"
                                class="nav-link {{ request()->is('posts') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-archive"></i>
                                <p>Posts</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('media.index') }}"
                                class="nav-link {{ request()->is('media') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-photo-video"></i>
                                <p>Media</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('accounts.index') }}"
                                class="nav-link {{ request()->is('accounts') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>Accounts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('calendar.index') }}"
                                class="nav-link {{ request()->is('calendar') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Calendar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.index') }}"
                                class="nav-link {{ request()->is('profile') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        @yield('content')


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2021 <a href="{{ route('home', ['/']) }}">La Posta.io</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="/js/jquery.js"></script>
    <!-- Bootstrap -->
    <script src="/js/bootstrap.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/adminlte.js"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#notifications_bell').click(function() {
                $('#notifications_num').text("")
            })

        })
        Echo.private('users.' + {{ auth()->user()->id }})
            .notification((e) => {
                console.log(e)
                $('#notifications_num').text((parseInt($('#notifications_num').text()) || 0) + 1)
                if (e.type = 'login') {
                    $("#notifications_menu_indexer").after(`<a href="{{ route('profile.index') }}#sessionsTable" class="dropdown-item">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> ${e.message}
                                    <span class="float-right text-muted text-sm">Just Now</span>
                                </a>
                            <br>
                            <div class="dropdown-divider"></div>`)
                } else if (e.type = 'post') {
                    $("#notifications_menu_indexer").after(`<a href="#" class="dropdown-item">
                                    <i class="fas fa-envelope mr-2"></i> ${e.message}
                    <span class="float-right text-muted text-sm">Just Now</span>
                                </a>
                                <br>
                                <div class="dropdown-divider"></div>`)
                }
            })

    </script>
    @yield('script')

</body>

</html>
