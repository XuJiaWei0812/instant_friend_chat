$(function () {
    $.ajaxSetup({
        headers: {
            'Accept': 'application/json',
            'Authorization': "Bearer " + localStorage.getItem('token'),
        }
    });

    if (localStorage.getItem("token") != null) {
        $('#nav-li').append('<li class="nav-item">' +
            '<a href="#" class="nav-link text-light " id="logout">登出</a>' +
            '</li>');
    } else {
        $('#nav-li').append('<li class="nav-item">' +
            '<a class= "nav-link text-light" href="/login">登陸</a>' +
            '</li>');
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
});
