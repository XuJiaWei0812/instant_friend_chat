<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <title>@yield('title')</title>
</head>

<body class="bg-secondary">
    @if (Auth::check())
    <header class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="navbar-brand">
            <img src="{{asset($photo)}}" class="rounded-circle mr-2" width="48px" height="48px" alt="圖片無法顯示">
            <span>{{$name}}</span>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto" id="nav-li">
                <li class="nav-item">
                    <a class="nav-link text-light" href="/friend/roster">好友名單</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/friend/record">聊天紀錄</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="/friend/apply">申請審核</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto" id="nav-li">
                <li class="nav-item">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                        添加好友
                    </button>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-light " id="logout">登出</a>
                </li>
            </ul>
        </div>
    </header>
    @include('friend.createFriendModal')
    @endif

    @yield('content')

    <script type="text/javascript" src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript">
        if (!!window.performance && window.performance.navigation.type === 2) {
            //window.performance.navigation.type ===2 表示使用 back or forward
            console.log('Reloading');
            window.location.reload();
        }
        $("input").mouseup(function () {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display', 'none');
        });
    </script>
    @yield('javascript')
</body>

</html>
