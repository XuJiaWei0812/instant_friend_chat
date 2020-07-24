require('./bootstrap');

window.Echo.channel('create-friendMessage')
    .listen('createFriendMessage', (e) => {
        if ('/friend/chat/' + e.new_message.friend_id === window.location.pathname) {
            if ($("div[name='dateRow']").text().indexOf("今天") == -1) {
                $('#message_section').append('<div class="row mx-auto py-3" style="opacity:0.8">' +
                    '<div class="col-10 bg-white rounded mx-auto text-center" name="dateRow">' +
                    '今天' +
                    '</div>' +
                    '</div>');
            }
            if (e.new_message.message.indexOf('images') >= 0) {
                e.new_message.message = '<img src="' + e.new_message.message + '" class="img-fluid" alt="Responsive image">';
            }
            if (e.new_message.message_userId == localStorage.getItem('uid')) {
                $('#message_section').append(
                    '<div class="row mx-auto py-3">' +
                    '<div class="col-10 mx-auto d-flex justify-content-end">' +
                    '<div class="align-self-end mr-2">' +
                    '<span class="pl-2 font-weight-bold text-white m-0" name="ready">' +
                    '</span>' +
                    '<br>' +
                    '<span class="font-weight-bold text-white m-0">' +
                    e.new_message.time +
                    '</span>' +
                    '</div >' +
                    '<div class="bg-white rounded align-self-center p-2">' +
                    e.new_message.message +
                    '</div>' +
                    '</div>' +
                    '</div>');
            } else {
                $('#message_section').append(
                    '<div class="row mx-auto py-3">' +
                    '<div class="col-10 mx-auto d-flex justify-content-start">' +
                    '<img src="' + e.new_message.friend_photo + '" class="rounded-circle mr-2" width="48px" height="48px" alt="圖片無法顯示">' +
                    '<div class="rounded bg-white align-self-center p-2">' +
                    e.new_message.message +
                    '</div>' +
                    '<div class="align-self-end ml-2">' +
                    '<span class="text-left text-nowrap font-weight-bold text-white m-0">' +
                    e.new_message.time +
                    '</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
            }
            console.log("新訊息建立成功")
            if ($(window).scrollTop() + $(window).height() + 316 > $(document).height()){
                $("html,body").animate({
                    scrollTop: $(document).height()
                });
            }
            $.ajax({
                type: "put",
                url: "/api/friend/chat/" + e.new_message.friend_id,
                dataType: "json",
                data: {
                    friend_userId: e.new_message.friend_userId,
                    message_id: e.new_message.message_id,
                    message_userId: e.new_message.message_userId,
                    friend_id: e.new_message.friend_id,
                },
                success: function (data) {
                    console.log("前往確認對方是否讀取");
                }
            });
        }
    });

window.Echo.channel('get-readyMessage')
    .listen('getReadyMessage', (e) => {
        if ('/friend/chat/' + e.friend_id === window.location.pathname) {
            console.log('對方已經已讀');
            $("span[name='ready']").text("已讀");
        }
    });

window.Echo.channel('get-recordMessage')
    .listen('getRecordMessage', (e) => {
        if ('/friend/record' === window.location.pathname) {
            console.log('新的聊天紀錄取得');
            $.each(e.records, function (index, val) {
                if (val.unread > 0) {
                    $unread = '<span class="badge badge-primary badge-pill float-right" name="ready">' + val.unread + '</span>';
                    $("#unread" + val.friend_id).addClass('badge-primary');
                    $("#unread" + val.friend_id).text(val.unread);
                } else {
                    $("#unread" + val.friend_id).removeClass('badge-primary');
                    $("#unread" + val.friend_id).text(null);
                    $unread = "";
                }
                if ($("#record" + val.friend_id).length > 0) {
                    $("#time" + val.friend_id).text(val.time);
                    $("#message" + val.friend_id).text(val.message);
                } else {
                    $("#list-ul" + e.user_id).append('<a href="/friend/chat/' + val.friend_id + '"' +
                        'class="p-2 list-group-item list-group-item-action" id="record"' + val.friend_id+'>' +
                        '<img src="' + val.friend_photo + '" class="rounded-circle mr-2 float-left" alt="無法顯示圖片"' +
                        'width="62px" height="62px">' +
                        '<div class="d-flex flex-column">' +
                        '<div class="p-1 d-flex justify-content-between">' +
                        '<h5 class="flex-grow-1">' +
                        val.friend_name +
                        '</h5 >' +
                        '<span>' +
                        val.time +
                        '</span>' +
                        '</div>' +
                        '<div class="p-1 flex-fill">' +
                        '<span">' + val.message + '</span>' +
                        $unread +
                        '</div>' +
                        '</div>' +
                        '</a>');
                }
            });
        }
    });

window.Echo.channel('check-lgoin')
    .listen('checkLogin', (e) => {
        if ('/friend/roster' === window.location.pathname) {
            $.each(e.loginCheck, function (index, val) {
                $("#user" + val.id).removeClass("badge-success badge-danger");
                if (val.online == "下線中") {
                    $("#user" + val.id).addClass("badge-danger");
                    $("#user" + val.id).html(val.online);
                } else {
                    $("#user" + val.id).addClass("badge-success");
                    $("#user" + val.id).html(val.online);
                }
            });
        }
    });

