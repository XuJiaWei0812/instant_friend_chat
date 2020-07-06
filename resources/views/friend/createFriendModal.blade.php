<!-- Modal -->
<div class="modal fade" id="createFriendModal" tabindex="-1" role="dialog" aria-labelledby="createFriendModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFriendModalLabel">添加好友</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="createFriend">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="account">信箱:</label>
                        <input required type="email" placeholder="請輸入信箱" class="form-control" name="email" id="email"
                            value="{{ old('email') }}">
                    </div>
                    <!--錯誤訊息模板-->
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <!--錯誤訊息模板-->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">送出</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
