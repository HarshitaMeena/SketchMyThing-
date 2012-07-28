var getMemberCount = function(){
    $("#MemCount").load("UsersDB/?action=get_count");
    setTimeout(getMemberCount, 5000);
};

var getOnlineCount = function(){
    $("#OLCount").load("UsersDB/?action=get_online");
    setTimeout(getOnlineCount, 5000);
};

$(document).ready(
    function() {
        getMemberCount();
        getOnlineCount();

        $('.bt_login').click(
            function () {
                $('#panelMSG').fadeOut(300);

                    setTimeout (function () {
                        $('#panelMSG').removeClass('nomsg');
                        $('#panelMSG').removeClass('badmsg');
                        $('#panelMSG').removeClass('goodmsg');

                        if($('#log').val() == "") {
                            $('#panelMSG').addClass('badmsg');
                            $('#panelMSG').html('Please specify your LDAP username.');
                        }
                        else if($('#pwd').val() == "") {
                            $('#panelMSG').addClass('badmsg');
                            $('#panelMSG').html('Please specify your SMT password.');
                        }
                        else if($('#pwd').val().length < 4) {
                            $('#panelMSG').addClass('badmsg');
                            $('#panelMSG').html('Your SMT password must be at least 4 characters long.');
                        }
                        else {
                            $('#panelMSG').addClass('nomsg');
                            $('#panelMSG').html('Trying to login in with your credentials ..');

                            var dataString = "log="+$('#log').val()+"&pwd="+$('#pwd').val();

                            $.ajax({
                                type: "POST",
                                data: dataString,
                                dataType: "json",
                                url: "UsersDB/tryToLoginUser.php",
                                error: function(xhr, textStatus, errorThrown){
                                    $('#panelMSG').addClass('badmsg');
                                $('#panelMSG').html("Error: " + errorThrown);
                            },
                                success: function(data) {
                                    $('#panelMSG').removeClass('nomsg');
                                    $('#panelMSG').removeClass('badmsg');
                                    $('#panelMSG').removeClass('goodmsg');

                                    if(data.status)
                                        window.location = "./";
                                    else
                                        $('#panelMSG').addClass('badmsg');

                                    $('#panelMSG').html(data.message);
                                }
                            });
                        }

                        $('#panelMSG').fadeIn(400);
                    }, 300);

                return false;
        });

        $('.bt_register').click(
            function() {
                $('#panelMSG').fadeOut(300);

                setTimeout (function () {
                    $('#panelMSG').removeClass('nomsg');
                    $('#panelMSG').removeClass('badmsg');
                    $('#panelMSG').removeClass('goodmsg');

                    if($('#LDAPlog').val() == "")
                    {
                        $('#panelMSG').addClass('badmsg');
                        $('#panelMSG').html('Please specify your LDAP username.');
                    }
                    else if($('#SMTpwd').val() == "")
                    {
                        $('#panelMSG').addClass('badmsg');
                        $('#panelMSG').html('Please specify your SMT password.');
                    }
                    else if($('#SMTpwd').val().length < 4)
                    {
                        $('#panelMSG').addClass('badmsg');
                        $('#panelMSG').html('Your SMT password must be at least 4 characters long.');
                    }
                    else
                    {
                        $('#panelMSG').addClass('nomsg');
                        $('#panelMSG').html('Trying to create your account on server ..');

                        var dataString = "log="+$('#LDAPlog').val()+"&pwd="+$('#SMTpwd').val();

                        $.ajax({
                            type: "POST",
                            data: dataString,
                            dataType: "json",
                            url: "UsersDB/registerNewUser.php",
                            error: function(xhr, textStatus, errorThrown){
                                $('#panelMSG').addClass('badmsg');
                            $('#panelMSG').html("Error: " + errorThrown);
                        },
                            success: function(data) {
                                $('#panelMSG').removeClass('nomsg');
                                $('#panelMSG').removeClass('badmsg');
                                $('#panelMSG').removeClass('goodmsg');

                                if(data.status)
                                    $('#panelMSG').addClass('goodmsg');
                                else
                                    $('#panelMSG').addClass('badmsg');

                                $('#panelMSG').html(data.message);
                            }
                        });
                    }

                    $('#panelMSG').fadeIn(400);
                }, 300);

            return false;
        });
    }
);
