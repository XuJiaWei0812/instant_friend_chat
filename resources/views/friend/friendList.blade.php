@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid px-0">
    <div class="row mx-auto">
        <div class="col-lg-8 mx-auto px-0">
        <ul class="list-group" id="list-ul{{$id}}">
                {{--好友名單start--}}
                @if (!empty($friendRosters))
                @foreach ($friendRosters as $friendRoster)
                <a href="#" class="list-group-item list-group-item-action" data-toggle="modal"
                    data-target="#friendLsitModal{{$friendRoster->fid}}">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <img src="{{asset($friendRoster->photo)}}" class="rounded-circle mr-3" alt="" width="40px"
                            height="40px">
                        <h5 class="mb-1 align-items-center flex-grow-1">
                            {{$friendRoster->fu_name}}
                        </h5>
                        <span id="user{{$friendRoster->fu_id}}"
                            class="badge {{$friendRoster->online == "上線中" ? "badge-success" : "badge-danger"}}">
                            {{$friendRoster->online}}
                        </span>
                    </div>
                </a>
                @include('friend.friendListModal')
                @endforeach
                {{--好友名單end--}}
                {{--聊天紀錄start--}}
                @elseif(!empty($friendRecords))
                @foreach ($friendRecords as $friendRecord)
                <a href="{{ asset('/friend/chat/'.$friendRecord->fid) }}"
                    class="p-2 list-group-item list-group-item-action">
                    <img src="{{ asset($friendRecord->photo) }}" class="rounded-circle mr-2 float-left" alt="無法顯示圖片"
                        width="62px" height="62px">
                    <div class="d-flex flex-column">
                        <div class="p-1 d-flex justify-content-between">
                            <h5 class="flex-grow-1">
                                {{ $friendRecord->name }}
                            </h5>
                            <span id="time{{$friendRecord->fid}}">
                                {{ $friendRecord->date }} {{ $friendRecord->time }}
                            </span>
                        </div>
                        <div class="p-1 flex-fill" id="message-unread{{$friendRecord->fid}}">
                            <span id="message{{$friendRecord->fid}}">{{ $friendRecord->message }}</span>
                            @if ($friendRecord->unread>0)
                            <span class="badge badge-primary badge-pill float-right" id="unread{{$friendRecord->fid}}">
                                {{ $friendRecord->unread }}
                            </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
                {{--聊天紀錄end--}}
                {{--好友審核start--}}
                @elseif(!empty($friendApplys))
                @foreach ($friendApplys as $friendApply)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <p class="h4 flex-grow-1">
                        {{$friendApply->fu_name}}
                    </p>
                    <button type="button" class="btn btn-primary mr-1"
                        onclick="agreeFriend({{$friendApply->fid}});">同意</button>
                    <button type="button" onclick="refuseFriend({{$friendApply->fid}});"
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
