$(window).ready(function(){
    $('#username').focusout(function(){        
        var username = $("#username").val();
        
        $.get("../../api/checkUsernameAvailability.php?username=" + username, function( data ) {
            $json = $.parseJSON(data);
            if($json.exists===true){
                $("#username_label").html("Username (Already Exists)");
                $("#username_group").addClass("has-error");
            }else{
                $("#username_label").html("Username");
                $("#username_group").removeClass("has-error");
            }
        });
    });
    
    $('#password, #passwordRepeat').keyup(function(){
        if($("#password").val() !== $("#passwordRepeat").val()){
            $("#password_label").html("Password (Must match repeated password)");
            $(".password_group").addClass("has-error");
        }else{
            $("#password_label").html("Password");
            $(".password_group").removeClass("has-error");
        }
    });
});