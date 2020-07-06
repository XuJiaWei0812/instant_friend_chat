@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid" id="login_section">
    <div class="row">
        <div class="col-lg-5 m-auto py-3">
            <form method="POST" id="login" class="bg-light rounded border border-dark mx-auto p-3">
                <div class="form-group">
                    <h3 class="text-center">{{$title}}</h3>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                        placeholder="電子郵件" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="password">密碼:</label>
                    <input type="password" class="form-control" id="password" name="password"
                        aria-describedby="passwordHelp" placeholder="密碼">
                </div>
                <!--錯誤訊息模板-->
                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div>
                <!--錯誤訊息模板-->
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">登入</button>
                    <small class="form-text text-muted text-center pt-3">
                        <p>
                            還不是會員?
                            <button id="goToRegister" type="button" class="btn btn-link p-0 pb-1">加入會員</button>
                            還是忘記密碼?
                            <button type="button" class="btn btn-link p-0 pb-1" data-toggle="modal"
                                data-target="#exampleModal">
                                忘記密碼
                            </button>
                        </p>
                    </small>
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
