@extends('layout/master_login')

@section('title',$title)

@section('content')
<section class="container-fluid" id="show">
    <div class="row mx-auto">
        <div class="col-lg-7 mx-auto py-3">
            <form method="POST" id="register" class="bg-light rounded border border-dark mx-auto p-3">
                <div class="form-group">
                    <h1 class="text-center">{{$title}}</h1>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="form-group">
                    <label for="name">暱稱:</label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp"
                        placeholder="暱稱" value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                        placeholder="Email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="password">密碼:</label>
                    <input type="password" class="form-control" id="password" name="password"
                        aria-describedby="emailHelp" placeholder="密碼">
                </div>

                <div class="form-group">
                    <label for="password_confirmatiom">確認密碼:</label>
                    <input type="password" class="form-control" id="c_password" name="c_password" placeholder="確認密碼">
                </div>

                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="type01" value="0" checked>
                        <label class="form-check-label" for="type01">一般會員</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="type02" value="1">
                        <label class="form-check-label" for="type02">管理者</label>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">送出</button>
                </div>
                @include('layout.validationErrorMessag')
            </form>
        </div>
    </div>
</section>
@endsection