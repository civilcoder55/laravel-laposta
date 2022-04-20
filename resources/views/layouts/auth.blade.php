<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>La Posta | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/css/fontawesome.css"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="/css/adminlte.css"/>
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/css/icheck.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('dashboard') }}"><b>LA</b>POSTA</a>
    </div>
    <!-- /.login-logo -->
    @yield('content')
</div>
<!-- /.login-box -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="/js/jquery.js"></script>
<!-- Bootstrap -->
<script src="/js/bootstrap.js"></script>
<!-- AdminLTE App -->
<script src="/js/adminlte.js"></script>

</body>

</html>
