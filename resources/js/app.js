require('./bootstrap');

window.Echo.channel('create-friendMessage')
    .listen('createFriendMessage', (e) => {
        if ('/friend/chat/' + e.message.friend_id + '' === window.location.pathname) {
            $.ajax({
                type: "put",
                url: "/api/friend/chat/" + e.message.friend_id,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data.success);
                }
            });
            console.log($("div[name='dateRow']").text().indexOf("今天"));
            if ($("div[name='dateRow']").text().indexOf("今天")==-1) {
                $('#message_section').append('<div class="row mx-auto" style="opacity:0.8">' +
                    '<div class="col-10 bg-white rounded mx-auto text-center" name="dateRow">' +
                    '今天' +
                    '</div>' +
                    '</div>');
            }
            if (e.message.message.indexOf('images') >= 0) {
                e.message.message = '<img src="' + e.message.message + '" class="img-fluid" alt="Responsive image">';
            }
            if (e.message.uid == localStorage.getItem('uid')) {
                $('#message_section').append(
                    '<div class="row mx-auto py-3">' +
                    '<div class="col-10 mx-auto d-flex justify-content-end">' +
                    '<div class="align-self-end mr-2">' +
                    '<span class="pl-2 font-weight-bold text-white m-0" id="ready' + e.message.id + '">' +
                    '</span>' +
                    '<br>' +
                    '<span class="font-weight-bold text-white m-0">' +
                    e.message.time +
                    '</span>' +
                    '</div >' +
                    '<div class="bg-white rounded align-self-center p-2">' +
                    e.message.message +
                    '</div>' +
                    '</div>' +
                    '</div>');
            } else {
                $('#message_section').append(
                    '<div class="row mx-auto py-3">' +
                    '<div class="col-10 mx-auto d-flex justify-content-start">' +
                    '<img src="' + e.message.photo + '" class="rounded-circle mr-2" width="48px" height="48px" alt="圖片無法顯示">' +
                    '<div class="rounded bg-white align-self-center p-2">' +
                    e.message.message +
                    '</div>' +
                    '<div class="align-self-end ml-2">' +
                    '<span class="text-left text-nowrap font-weight-bold text-white m-0">' +
                    e.message.time +
                    '</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
            }
        }
    });
window.Echo.channel('get-readyMessage')
    .listen('getReadyMessage', (e) => {
        if ('/friend/chat/' + e.fid + '' === window.location.pathname) {
            console.log(e);
            $.each(e.message, function (index, val) {
                $("#ready" + val.id).text("已讀");
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

window.Echo.channel('get-recordMessage')
    .listen('getRecordMessage', (e) => {
        if ('/friend/record' === window.location.pathname) {
            $.each(e.record, function (index, val) {
                if ($("#list-ul" + val.uid).length > 0) {
                    if ($("#message" + val.fid).length > 0) {
                        if (val.unread > 0) {
                            if ($("#unread" + val.fid).length == 0) {
                                $("#message-unread" + val.fid).append(
                                    '<span class="badge badge-primary badge-pill float-right" id="unread' + val.fid + '">' +
                                    val.unread +
                                    '</span>');
                            } else {
                                $("#unread" + val.fid).text(val.unread);
                            }
                            $("#time" + val.fid).text(val.time);
                            $("#message" + val.fid).text(val.message);
                        } else {
                            $("#time" + val.fid).text(val.time);
                            $("#message" + val.fid).text(val.message);
                            $("#unread" + val.fid).remove()
                        }
                    } else {
                        if (val.unread > 0) {
                            $unread = '<span class="badge badge-primary badge-pill float-right" id="unread' + val.fid + '">' + val.unread + '</span>';
                        }else{
                            $unread ="";
                        }
                        $("#list-ul" + val.uid).append('<a href="' + e.rosterUrl +"/"+ val.fid + '"' +
                            'class="p-2 list-group-item list-group-item-action">' +
                            '<img src="' + val.photo + '" class="rounded-circle mr-2 float-left" alt="無法顯示圖片"' +
                            'width="62px" height="62px">' +
                            '<div class="d-flex flex-column">' +
                            '<div class="p-1 d-flex justify-content-between">' +
                            '<h5 class="flex-grow-1">' +
                            val.name +
                            '</h5 >' +
                            '<span id="time' + val.fid + '">' +
                            val.time +
                            '</span>' +
                            '</div>' +
                            '<div class="p-1 flex-fill">' +
                            '<span id="message' + val.fid + '">' + val.message + '</span>' +
                            $unread+
                            '</div>' +
                            '</div>' +
                            '</a>');
                    }
                }
            });
        }
    });
