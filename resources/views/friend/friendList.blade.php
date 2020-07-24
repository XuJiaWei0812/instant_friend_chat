@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid px-0">
    <div class="row mx-auto">
        <div class="col-lg-8 mx-auto px-0">
            <ul class="list-group" id="list-ul{{$id}}">
                {{--好友名單start--}}
                @if (!empty($rosters))
                @foreach ($rosters as $roster)
                <a href="#" class="list-group-item list-group-item-action" data-toggle="modal"
                    data-target="#friendLsitModal{{$roster->friend_id}}">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <img src="{{asset($roster->friend_photo)}}" class="rounded-circle mr-3" alt="" width="40px"
                            height="40px">
                        <h5 class="mb-1 align-items-center flex-grow-1">
                            {{$roster->friend_name}}
                        </h5>
                        <span id="user{{$roster->friend_userId}}"
                            class="badge {{$roster->online == "上線中" ? "badge-success" : "badge-danger"}}">
                            {{$roster->online}}
                        </span>
                    </div>
                </a>
                @include('friend.friendListModal')
                @endforeach
                {{--好友名單end--}}
                {{--聊天紀錄start--}}
                @elseif(!empty($records))
                @foreach ($records as $record)
                <a href="{{ asset('/friend/chat/'.$record->friend_id) }}"
                    class="p-2 list-group-item list-group-item-action" id="record{{$record->friend_id}}">
                    <img src="{{ asset($record->friend_photo) }}" class="rounded-circle mr-2 float-left" alt="無法顯示圖片"
                        width="62px" height="62px">
                    <div class="d-flex flex-column">
                        <div class="p-1 d-flex justify-content-between">
                            <h5 class="flex-grow-1">
                                {{ $record->friend_name }}
                            </h5>
                            <span id="time{{$record->friend_id}}">
                                {{ $record->date }} {{ $record->time }}
                            </span>
                        </div>
                        <div class="p-1 flex-fill">
                            <span id="message{{$record->friend_id}}">{{ $record->message }}</span>
                            @if ($record->unread>0)
                            <span class="badge badge-primary badge-pill float-right" id="unread{{$record->friend_id}}">
                                {{ $record->unread }}
                            </span>
                            @else
                            <span class="badge badge-pill float-right" id="unread{{$record->friend_id}}">
                            </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
                {{--聊天紀錄end--}}
                {{--好友審核start--}}
                @elseif(!empty($applys))
                @foreach ($applys as $apply)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <p class="h4 flex-grow-1">
                        {{$apply->friend_name}}
                    </p>
                    <button type="button" class="btn btn-primary mr-1"
                        onclick="agreeFriend({{$apply->friend_id}});">同意</button>
                    <button type="button" onclick="refuseFriend({{$apply->friend_id}});"
                        class="btn btn-danger mr-1">拒絕</button>
                </div>
                @endforeach
                {{--好友審核end--}}
                @endif
            </ul>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script type="text/javascript" src="{{asset('js/friendList.js')}}"></script>
<script src="{{mix('js/app.js')}}" type="text/javascript"></script>
@endsection
