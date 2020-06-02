require('./bootstrap');

window.Echo.channel('create-friendMessage')
    .listen('createFriendMessage', (e) => {
        console.log(e.message.friend_id);
        if ('/friend/chat/' + e.message.friend_id === window.location.pathname) {
            if (e.message.date != "") {
                $('#message_section').append('<div class="row mx-auto" style="opacity:0.8">' +
                    '<div class="col-8 bg-white rounded mx-auto text-center">' +
                    e.message.date +
                    '</div>' +
                    '</div>');
            }
            if (e.message.message.indexOf('images') >= 0) {
                e.message.message = '<img src="' + e.message.message + '" class="img-fluid" alt="Responsive image">';
            }
            if (e.message.uid == localStorage.getItem('uid')) {
                $('#message_section').append(
                    '<div class="row mx-auto pt-3">' +
                    '<div class="col-8 mx-auto d-flex justify-content-end">' +
                    '<div class="align-self-end mr-2">' +
                    '<span class="pl-2 font-weight-bold text-white m-0">' +
                    e.message.ready +
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
                    '</div >');
            } else {
                $('#message_section').append(
                    '<div class="row my-2 mx-auto pt-3">' +
                    '<div class="col-8 mx-auto d-flex justify-content-start">' +
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
