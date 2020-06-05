$.ajaxSetup({
    headers: {
        'Accept': 'application/json',
        'Authorization': "Bearer " + localStorage.getItem('token'),
        'X-Requested-With': 'XMLHttpRequest',
    }
});
$(function () {
    $("html,body").animate({
        //把畫面置底
        scrollTop: $(window).height() + 9999
    });
    $("#file_upload").change(function (event) {
        event.preventDefault();
        var formData = new FormData();
        formData.append("file", this.files[0]);
        fileUpload(formData);
    });
    $("textarea").keypress(function (event) {
        if (event.keyCode === 13 || event.keyCode === 10) {
            event.preventDefault();
            var formData = new FormData();
            formData.append("message", $("#message").val());
            createFriendMessage(formData);
            $("#message").val("")
        }
    });
    function fileUpload(formData) {
        $.ajax({
            type: "POST",
            url: ('/api' + window.location.pathname),
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    console.log("ok");
                } else {
                    printErrorMsg(data.error);
                }
            }
        });
    }

    function createFriendMessage(formData) {
        $.ajax({
            type: "POST",
            url: ('/api'+window.location.pathname),
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    $("#createFriendMessage")[0].reset();
                    console.log("ok");
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

    $("#logout").click(function (event) {
        event.preventDefault();
        logout()
    });
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
