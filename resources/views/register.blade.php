@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid py-3" id="register_section" style="padding-top: 0px">
    <div class="row mx-auto">
        <div class="col-lg-7 mx-auto">
            <form method="POST" id="register" class="bg-light rounded border border-dark mx-auto p-3">
                <div class="form-group">
                    <h3 class="text-center">{{$title}}</h3>
                </div>
                <div class="form-group">
                    <label class="form-control-file d-flex justify-content-center m-0">
                        <figure class="figure m-0" style="cursor: pointer;">
                            <img id="photoImage" src="{{asset("/images/default-user.png")}}"
                                class="figure-img img-fluid rounded-circle" alt="頭貼">
                            <figcaption class="figure-caption text-center">編輯個人頭貼</figcaption>
                        </figure>
                        <input type="file" style="display:none;" id="photo" name="photo">
                    </label>
                </div>
                <div class="form-group">
                    <label for="name">暱稱:</label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp"
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
                    <label for="password_confirmation">確認密碼:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="確認密碼">
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">註冊</button>
                    <small class="form-text text-muted text-center pt-3">
                        已經有帳號了?
                        <a href="{{asset("/")}}">會員登入</a>
                    </small>
                </div>
                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script src="{{asset('js/user.js')}}"></script>
@endsection
