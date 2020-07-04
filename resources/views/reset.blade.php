@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid" id="login_section">
    <div class="row">
        <div class="col-lg-5 m-auto py-3">
            <form method="POST" id="resetPassword" class="bg-light rounded border border-dark mx-auto p-3">
                <div class="form-group">
                    <h1 class="text-center">{{$title}}</h1>
                </div>
                <div class="form-group">
                    <label for="captcha">輸入認證瑪:</label>
                    <input type="text" class="form-control" id="captcha" name="captcha" aria-describedby="captchaHelp"
                        placeholder="輸入驗證碼">
                </div>
                <div class="form-group">
                    <label for="password">設定新密碼:</label>
                    <input type="password" class="form-control" id="password" name="password"
                        aria-describedby="passwordHelp" placeholder="設定新密碼">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">確認新密碼:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        aria-describedby="passwordHelp" placeholder="確認新密碼">
                </div>
                @include('layout.validationErrorMessag')
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">送出</button>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- 忘記密碼 -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">忘記密碼</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="forget" class="px-3">
                <div class="modal-body">
                    <label for="email">電子郵件Email:</label>
                    <input type="email" class="form-control" id="checkEmail" name="checkEmail"
                        aria-describedby="checkEmailHelp" placeholder="電子郵件" value="{{ old('email') }}">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">送出</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 忘記密碼 -->
@endsection

@section('javascript')
<script src="{{asset('js/user.js')}}"></script>
<script src="{{mix('js/app.js')}}" type="text/javascript"></script>
@endsection
