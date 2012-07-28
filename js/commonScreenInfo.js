var canvasOffsetX;
var canvasOffsetY;
var mainCanvas;
var canvasContext;
var nextReadShoutsFrom = 0;
var nextReadMatchShoutsFrom = 0;

var getSMTInfo = function() {
    $.ajax({
        type: "POST",
        data: "NoPlayers=1&shoutFrom=" + nextReadShoutsFrom + "&matchShoutFrom=" + nextReadMatchShoutsFrom,
        dataType: "json",
        url: "SMT_Internal/getMatchesAndBoxInfo.php",
        error: function(xhr, textStatus, errorThrown){
            getSMTInfo();
        },
        success: function(data) {
            if(nextReadShoutsFrom != data.SHOUT.POS) {
                $("#shoutBoxDiv").html($("#shoutBoxDiv").html() + data.SHOUT.DATA);
                $("#shoutBoxDiv").animate({ scrollTop: $("#shoutBoxDiv").prop("scrollHeight") }, 1000);
                nextReadShoutsFrom = parseInt(data.SHOUT.POS);
            }
            if(nextReadMatchShoutsFrom != data.SHOUT.MPOS) {
                $("#chatBoxDiv").html($("#chatBoxDiv").html() + data.SHOUT.MDATA);
                $("#chatBoxDiv").animate({ scrollTop: $("#chatBoxDiv").prop("scrollHeight") }, 1000);
                nextReadMatchShoutsFrom = parseInt(data.SHOUT.MPOS);
            }
            $("#timeLeft").html(parseInt(data.TIMELEFT / 60) + ":" + (data.TIMELEFT % 60));
            if(!($("#sketchOverlayTools")))
                if(typeof data.WORD !== "undefined")
                    $("#THEWORD").html(data.WORD);
            if(data.UNSTABLE)
                window.location = "./";
            setTimeout(getSMTInfo, 800);
        }
    });
};

$(function(){
    $('#slider').anythingSlider({
        buildStartStop: false,
        buildArrows: false,

        startPanel: 1,
        easing: "easeOutBounce",
        animationTime: 800,
        navigationFormatter: function(index, panel) {
            return ['GAME', "POINTS", 'SHOUT !!'][index - 1];
          },
    });
});

function offsetOf (elem)
{
    var curleft = curtop = 0;
    if (elem.offsetParent)
    {
        do
        {
            curleft += elem.offsetLeft;
            curtop += elem.offsetTop;
        } while (elem = elem.offsetParent);
    }
    return [curleft,curtop];
}

$(document).ready(
    function() {
        mainCanvas = $("#sketchArea");
        canvasContext = mainCanvas[0].getContext("2d");
        var canvasOffset = offsetOf(mainCanvas[0]);
        canvasOffsetX = canvasOffset[0];
        canvasOffsetY = canvasOffset[1];

        getSMTInfo();

        $(".sketchOverlayMsg").select(
            function () {
                return false;
        });
        $(".sketchOverlayTools").select(
            function () {
                return false;
        });

        /*$("#LeaveMatchBtn").click(
            function () {
                $.ajax({
                    type: "POST",
                    data: "Leaving=1",
                    url: "SMT_Internal/leaveCurrentMatch.php",
                    success: function () {
                        window.location = "./";
                    }
                });
        });*/

        $("#shoutBtn").click(
            function () {
                $.ajax({
                    type: "POST",
                    data: "Shout=" + $("#shoutMsgDiv").val(),
                    url: "SMT_Internal/setShoutBoxMessage.php",
                });
                $("#shoutMsgDiv").val("");
        });

        $("#shoutMsgDiv").keypress(
            function (e) {
                if(e.which == 13)
                {
                    $("#shoutBtn").click();
                    return false;
                }
        });

        $("#chatMsgDiv").keypress(
            function (e) {
                if(e.which == 13)
                {
                    $.ajax({
                        type: "POST",
                        data: "Shout=" + $("#chatMsgDiv").val(),
                        url: "SMT_Internal/setPrivateMessage.php",
                    });
                    $("#chatMsgDiv").val("");
                    return false;
                }
        });
});
