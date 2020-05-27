<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <title>@yield('title')</title>
</head>

<body>

    @if (Auth::check())
        <header class="navbar navbar-expand-lg navbar-dark bg-dark">
            <span class="navbar-brand" href="#">@yield('title')</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto" id="nav-li">
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/">前往商店</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/login">登陸</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="/register">註冊</a>
                    </li>
                </ul>
            </div>
        </header>
    @endif


    @yield('content')


    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/login.js')}}"></script>
</body>

</html>
