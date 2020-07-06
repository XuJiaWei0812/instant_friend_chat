<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">編輯資訊</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="editUser" class="p-3">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-control-file d-flex justify-content-center m-0">
                            <figure class="figure m-0" style="cursor: pointer;">
                                <img id="photoImage" src="{{asset(Auth::user()->photo)}}"
                                    class="figure-img img-fluid rounded-circle" alt="頭貼">
                                <figcaption class="figure-caption text-center">編輯個人頭貼</figcaption>
                            </figure>
                            <input type="file" style="display:none;" id="photo" name="photo">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="name">暱稱:</label>
                        <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp"
                            placeholder="暱稱" value="{{$name}}">
                    </div>
                    <!--錯誤訊息模板-->
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <!--錯誤訊息模板-->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">編輯</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                </div>
            </form>
        </div>
    </div>
</div>
