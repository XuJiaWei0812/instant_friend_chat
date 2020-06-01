<div class="modal fade" id="friendLsitModal{{$friendRoster->fid}}" tabindex="-1" role="dialog"
    aria-labelledby="friendLsitModalLabel{{$friendRoster->fid}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="friendLsitModalLabel{{$friendRoster->fid}}">
                    {{$friendRoster->fu_name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="/friend/chat/{{$friendRoster->fid}}" class="btn btn-success btn-block">
                    聊天
                </a>
                <button type="button" class=" btn btn-danger btn-block" data-toggle="modal"
                    data-target="#delectFriend{{$friendRoster->fid}}" data-dismiss="modal">
                    刪除
                </button>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delectFriend{{$friendRoster->fid}}" tabindex="-1" role="dialog"
    aria-labelledby="delectFriend" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delectFriend">刪除好友</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                確定要刪除好友?
            </div>
            <div class="modal-footer">
                <button type="button" onclick="deleteFriend({{$friendRoster->fid}});"
                    class="btn btn-primary mr-1">確定</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
