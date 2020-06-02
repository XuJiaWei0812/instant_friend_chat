$.ajaxSetup({
    headers: {
        'Accept': 'application/json',
        'Authorization': "Bearer " + localStorage.getItem('token'),
        'X-Requested-With': 'XMLHttpRequest',
    }
});
function agreeFriend(fid) {
    $.ajax({
        type: "PUT",
        url: "/api/friend/apply/" + fid,
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                alert(data.success);
                window.location.replace('/friend/roster');
            }
        }
    });
}
function refuseFriend(fid) {
    $.ajax({
        type: "delete",
        url: "/api/friend/apply/" + fid,
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                alert(data.success);
                window.location.replace('/friend/apply');
            }
        }
    });
}
function deleteFriend(fid) {
    $.ajax({
        type: "delete",
        url: "/api/friend/roster/" + fid,
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                alert(data.success);
                window.location.replace('/friend/roster');
            }
        }
    });
}
$(function () {


    $("#logout").click(function (event) {
        event.preventDefault();
        logout()
    });

    $("#createFriend").submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        createFriend(formData);
    });

    $("input").mouseup(function () {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display', 'none');
    });

    function createFriend(formData) {
        $.ajax({
            type: "POST",
            url: "/api/friend/apply",
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success);
                    window.location.replace('/friend/roster');
                } else {
                    printErrorMsg(data.error);
                }
            }
        });
    }
    function printErrorMsg(msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display', 'block');
        $.each(msg, function (key, value) {
            $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
        });
    }

    function logout() {
        $.ajax({
            type: "GET",
            url: "/api/logout",
            dataType: "json",
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success);
                    window.location.replace("/");
                    localStorage.removeItem('token');
                    localStorage.removeItem('uid');
                }
            }
        });
    }
});
