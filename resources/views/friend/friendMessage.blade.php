@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid" id="show">
    <div class="row mx-auto">
        <div class="col-8 mx-auto text-center text-light">
            <h5>與 {{$fu_name}} 對話中</h5>
        </div>
    </div>
    @foreach ($friendMessages as $friendMessage)
    {{--日期判斷start--}}
    @if ($friendMessage->date != "")
    <div class="row mx-auto" style="opacity:0.8">
        <div class="col-8 bg-white rounded mx-auto text-center">
            {{$friendMessage->date}}
        </div>
    </div>
    @endif
    {{--日期判斷end--}}
    {{--使用者訊息start--}}
    @if (auth('web')->user()->id==$friendMessage->uid)
    <div class="row mx-auto pt-3">
        <div class="col-8 mx-auto d-flex justify-content-end">
            <div class="align-self-end mr-2">
                <span class="pl-2 font-weight-bold text-white m-0">
                    {{$friendMessage->ready}}
                </span>
                <br>
                <span class="font-weight-bold text-white m-0">
                    {{$friendMessage->time}}
                </span>
            </div>
            <div class="bg-white rounded align-self-center p-2">
                @if (strpos($friendMessage->message,'images') !== false)
                <img src="{{$friendMessage->message}}" class="img-fluid" alt="Responsive image">
                @else
                {{$friendMessage->message}}
                @endif
            </div>
        </div>
    </div>
    @endif
    {{--使用者訊息end--}}
    {{--好友的訊息start--}}
    @if (auth('web')->user()->id!=$friendMessage->uid)
    <div class="row my-2 mx-auto pt-3">
        <div class="col-8 mx-auto d-flex justify-content-start">
            <img src="{{$friendMessage->photo}}" class="rounded-circle mr-2" width="48px" height="48px" alt="圖片無法顯示">
            <div class="rounded bg-white align-self-center p-2">
                @if (strpos($friendMessage->message,'images') !== false)
                <img src="{{$friendMessage->message}}" class="img-fluid" alt="Responsive image">
                @else
                {{$friendMessage->message}}
                @endif
            </div>
            <div class="align-self-end ml-2">
                <span class="text-left text-nowrap font-weight-bold text-white m-0">
                    {{$friendMessage->time}}
                </span>
            </div>
        </div>
    </div>
    @endif
    {{--好友的訊息end--}}
    @endforeach
</section>

<footer class="fixed-bottom bg-dark">
    <form method="POST" class="p-3" id="createFriendMessage" enctype="multipart/form-data">
        <div class="form-group ">
            <div class="input-group">
                <div class="input-group-prepend">
                    <label class="input-group-text btn btn-info">
                        <i class="fas fa-upload">
                            <input type="file" class="form-control-file" id="file_upload" style="display:none;">
                        </i>
                    </label>
                </div>
                <textarea id="message" class="form-control" aria-label="With textarea"></textarea>
            </div>
        </div>
    </form>
</footer>
@endsection

@section('javascript')
<script type="text/javascript" src="{{asset('js/friendMessage.js')}}"></script>
<script src="{{mix('js/app.js')}}" type="text/javascript"></script>
@endsection
