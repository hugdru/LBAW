$(window).ready(function () {
    $('#username').keyup(function () {
        var username = $("#username").val();

        $.get("../../api/checkUsernameAvailability.php?username=" + username, function (data) {
            $json = $.parseJSON(data);
            if ($json.exists === true) {
                $("#username_label").html("Username (Already Exists)");
                $("#username_group").addClass("has-error");
            } else {
                $("#username_label").html("Username");
                $("#username_group").removeClass("has-error");
            }
        });
    });

    $('#email').keyup(function () {
        var email = $("#email").val();

        $.get("../../api/checkEmailAvailability.php?email=" + email, function (data) {
            $json = $.parseJSON(data);
            if ($json.exists === true) {
                $("#email_label").html("Email Address (Already Exists)");
                $("#email_group").addClass("has-error");
            } else {
                $("#email_label").html("Email Address");
                $("#email_group").removeClass("has-error");
            }
        });
    });

    $('#password, #passwordRepeat').keyup(function () {
        var error = null;
        if ($("#password").val() !== $("#passwordRepeat").val()) {
            error = "Password (Must match repeated password)";
        } else {
            $("#password_label").html("Password");
            $(".password_group").removeClass("has-error");
        }

        var passwordLength = $("#password").val().length;
        if ( passwordLength < 8 || passwordLength > 100) {
            if (error == null) {
               error = "Password between 8-100";
            } else {
                error += " Password between 8-100";
            }
        }
        if (error !== null) {
            $("#password_label").html(error);
            $(".password_group").addClass("has-error");
        }
    });
});