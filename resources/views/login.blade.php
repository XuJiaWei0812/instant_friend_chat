@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid" id="login_section">
    <div class="row">
        <div class="col-lg-5 m-auto py-3">
            <form method="POST" id="login" class="bg-light rounded border border-dark mx-auto p-3">
                <div class="form-group">
                    <h1 class="text-center">{{$title}}</h1>
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

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">登入</button>
                    <small id="emailHelp" class="form-text text-muted text-center pt-3">
                        還不是會員?<a href="/register">加入會員</a>
                    </small>
                </div>
                @include('layout.validationErrorMessag')
            </form>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script src="{{asset('js/user.js')}}"></script>
@endsection
