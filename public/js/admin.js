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
    }

    $("#edit_product").submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        edit_product(formData, this.name)
    });

    $("#create_product").click(function (event) {
        event.preventDefault();
        create_product()
    });

    $("#photo").change(function () {
        readURLSignUp(this);
    }); $("#logout").click(function (event) {
        event.preventDefault();
        logout()
    });

    function edit_product(formData, id) {
        $.ajax({
            type: "POST",
            url: "/api/admin/product/" + id + "/edit",
            dataType: "json",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success);
                } else {
                    printErrorMsg(data.error);
                }
            }
        });
    }
    function create_product() {
        $.ajax({
            type: "GET",
            url: "/api/admin/product/create",
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    alert(data.success)
                    window.location.href="/admin/product/" + data.product_id + "/edit";
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

    function readURLSignUp(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#photo_img").attr('src', e.target.result);
                $("#photo_img").css('width', '348px');
                $("#photo_img").css('height', '348px');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

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
