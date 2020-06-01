@extends('layout/master')

@section('title',$title)

@section('content')
<section class="container-fluid" id="show">
    <div class="row mx-auto">
        <div class="col-lg-8 mx-auto py-3">
            <ul class="list-group">
                {{--好友名單start--}}
                @if (!empty($friendRosters))
                @foreach ($friendRosters as $friendRoster)
                <button type="button" class="list-group-item list-group-item-action" data-toggle="modal"
                    data-target="#friendLsitModal{{$friendRoster->fid}}">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <img src="{{asset($friendRoster->photo)}}" class="rounded-circle mr-3" alt="" width="40px"
                            height="40px">
                        <h5 class="mb-1 align-items-center flex-grow-1">
                            {{$friendRoster->fu_name}}
                        </h5>
                        <span class="badge {{$friendRoster->online == 1 ? "badge-success" : "badge-danger"}}">
                            {{$friendRoster->online == 1 ? "上線中" : "下線中"}}
                        </span>
                    </div>
                </button>
                @include('friend.friendListModal')
                @endforeach
                {{--好友名單end--}}

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
@endsection
