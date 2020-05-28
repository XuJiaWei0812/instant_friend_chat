<div class="modal fade" id="friendLsitModal{{$Friend->id}}" tabindex="-1" role="dialog" aria-labelledby="friendLsitModalLabel{{$Friend->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="friendLsitModalLabel{{$Friend->id}}"> {{$Friend->nickname}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="/friend/{{$Friend->id}}" class="btn btn-success btn-block">
                    聊天
                </a>
                <button type="button" class=" btn btn-danger btn-block" data-toggle="modal" data-target="#delectFriend{{$Friend->id}}" data-dismiss="modal">
                    刪除
                </button>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delectFriend{{$Friend->id}}" tabindex="-1" role="dialog" aria-labelledby="delectFriend" aria-hidden="true">
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
                <form method="POST" id="deleteFriend" action="/friend/{{$Friend->id}}/delete" enctype="multipart/form-data">
                    {{method_field('DELETE ')}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">確定</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            </div>
        </form>
        </div>
    </div>
</div>
