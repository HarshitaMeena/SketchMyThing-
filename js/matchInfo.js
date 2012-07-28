var playerColumns = 3;
var nextReadShoutsFrom = 0;
var nextReadMatchShoutsFrom = 0;

var getSMTInfo = function() {
	$.ajax({
		type: "POST",
		data: "onlyMyMatch=1&shoutFrom=" + nextReadShoutsFrom + "&matchShoutFrom=" + nextReadMatchShoutsFrom,
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
			$(".column").html("");
			for(i = 0; i < data.PLAYERS.length; ++i)
				$(".column").eq(i % playerColumns).html($(".column").eq(i % playerColumns).html() + "<br>" + data.PLAYERS[i]);
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
    		return ['Match Info ..', 'SHOUT !!'][index - 1];
  		},
	});
});

$(document).ready(
	function() {
		getSMTInfo();
		
		$("#LeaveMatchBtn").click(
			function () {
				$.ajax({
					type: "POST",
					data: "Leaving=1",
					url: "SMT_Internal/leaveCurrentMatch.php",
					success: function () {
						window.location = "./";
					}
				});
		});

		$("#StartMatchBtn").click(
			function () {
    		    if($(".column").eq(1).html() == "")
    		        return;
				$.ajax({
					type: "POST",
					data: "Start=1",
					url: "SMT_Internal/startCurrentMatch.php",
					success: function () {
						window.location = "./";
					}
				});
		});
				
		$("#shoutBtn").click(
			function () {
			    if($("#shoutMsgDiv").val() == "")
			        return;
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
	
		$('#matchPlayersList').columnize({ columns: playerColumns });
});
