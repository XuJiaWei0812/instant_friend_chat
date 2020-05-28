$(function () {
    $.ajaxSetup({
        headers: {
            // 'Accept': 'application/json',
            // 'Authorization': "Bearer " + $('meta[name="laravel_token"]').attr('content'),
            // 'XSRF-TOKEN': $('meta[name="XSRF-TOKEN"]').attr('content'),
            'Authorization': "Bearer " + localStorage.getItem('token'),
            'X-Requested-With': 'XMLHttpRequest',
        }
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
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success);
                    window.location.replace("/");
                    localStorage.removeItem('token');
                }
            }
        });
    }
});
