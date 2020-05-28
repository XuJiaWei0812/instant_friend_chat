@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid" id="show">
    <div class="row mx-auto">
        <div class="col-lg-7 mx-auto py-3">

        </div>
    </div>
</section>
@endsection

@section('javascript')
<script src="{{asset('js/friendList.js')}}"></script>
@endsection
