$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    $("#register").submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        register(formData)
    });

    $("#login").submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        login(formData)
    });

    $("#logout").click(function (event) {
        event.preventDefault();
        logout()
    });

    function login(formData) {
        $.ajax({
            type: "post",
            url: "/api/login",
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success.message);
                    window.location.replace("/friend/roster");
                    localStorage.setItem('token', data.success.token);
                    localStorage.setItem('uid', data.success.token);
                } else {
                    printErrorMsg(data.error);
                }
            }
        });
    }

    function register(formData) {
        $.ajax({
            type: "POST",
            url: "/api/register",
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success);
                    window.location.replace("/");
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

});
