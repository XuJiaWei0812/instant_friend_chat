<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <p>Email:</p> {{ $toEmail }}
    <p>驗證碼:</p> {{ $captcha }}
    <p><a href="{{asset('/reset')}}">前往設定密碼</a></p>
</body>

</html>
