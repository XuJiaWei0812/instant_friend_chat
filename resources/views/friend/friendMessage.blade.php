@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid" id="message_section">
    @foreach ($messages as $message)
    {{--日期判斷start--}}
    @if ($message->date != "")
    <div class="row mx-auto" style="opacity:0.8">
        <div class="col-10 bg-white rounded mx-auto text-center mt-3" name="dateRow">
            {{$message->date}}
        </div>
    </div>
    @endif
    {{--日期判斷end--}}
    {{--使用者訊息start--}}
    @if (auth('web')->user()->id==$message->user_id)
    <div class="row mx-auto py-3">
        <div class="col-10 mx-auto d-flex justify-content-end">
            <div class="align-self-end mr-2">
                <span class="pl-2 font-weight-bold text-white m-0" name="ready">
                    {{$message->ready}}
                </span>
                <br>
                <span class="font-weight-bold text-white m-0">
                    {{$message->time}}
                </span>
            </div>
            <div class="bg-white rounded align-self-center p-2">
                @if (strpos($message->message,'images') !== false)
                <img src="{{$message->message}}" class="img-fluid" alt="Responsive image">
                @else
                {{$message->message}}
                @endif
            </div>
        </div>
    </div>
    @endif
    {{--使用者訊息end--}}
    {{--好友的訊息start--}}
    @if (auth('web')->user()->id!=$message->user_id)
    <div class="row mx-auto py-3">
        <div class="col-10 mx-auto d-flex justify-content-start">
            <img src="{{$message->photo}}" class="rounded-circle mr-2" width="48px" height="48px" alt="圖片無法顯示">
            <div class="rounded bg-white align-self-center p-2">
                @if (strpos($message->message,'images') !== false)
                <img src="{{$message->message}}" class="img-fluid" alt="Responsive image">
                @else
                {{$message->message}}
                @endif
            </div>
            <div class="align-self-end ml-2">
                <span class="text-left text-nowrap font-weight-bold text-white m-0">
                    {{$message->time}}
                </span>
            </div>
        </div>
    </div>
    @endif
    {{--好友的訊息end--}}
    @endforeach
</section>

<footer class="fixed-bottom bg-dark">
    <form method="POST" class="p-3" id="createmessage" enctype="multipart/form-data">
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
<script type="text/javascript">
</script>
@endsection
