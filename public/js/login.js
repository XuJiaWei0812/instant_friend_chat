$(function () {
    $.ajaxSetup({
        headers: {
            'Accept': 'application/json',
            'Authorization': "Bearer " + localStorage.getItem('token'),
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

    function logout() {
        $.ajax({
            type: "GET",
            url: "/api/logout",
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success);
                    window.location.replace("/login");
                    localStorage.removeItem('token');
                }
            }
        });
    }

    function login(formData) {
        $.ajax({
            type: "POST",
            url: "/login",
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success.message);
                    window.location.replace("/");
                    localStorage.setItem('token', data.success.token)
                } else {
                    printErrorMsg(data.error);
                }
            }
        });
    }

    function register(formData) {
        $.ajax({
            type: "POST",
            url: "/register",
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success);
                    window.location.replace("/login");
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
