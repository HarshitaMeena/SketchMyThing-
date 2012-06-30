var nextReadShoutsFrom = 0;

var tryJoiningMatch = function (matchID) {
	$.ajax({
		type: "POST",
		data: "matchID=" + matchID,
		dataType: "json",
		url: "SMT_Internal/joinAMatch.php",
		success: function(data) {
			$.fancybox.close();
			if(data[0])
				window.location = "./";
			else {
				$.fancybox({
			      'width': '40%',
			      'height': '40%',
			      'autoScale': true,
			      'transitionIn': 'fade',
			      'transitionOut': 'fade',
			      'content': '<br>'+data[1] +'<br><br>',
			      'title': 'FAILED !',
				});
			}
		}
	});
};

var getSMTInfo = function() {
	$.ajax({
		type: "POST",
		data: "shoutFrom=" + nextReadShoutsFrom,
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
			$("#mainGameTable tr").remove();
			for(i = 0; i < data.MATCH[0].length; ++i)
				$('#mainGameTable > tbody:last').append("<tr><td class='GameID'>"+data.MATCH[0][i].TEAM+"</td><td class='Players'>"+data.MATCH[0][i].PLAYERS+"</td><td class='JoinGame'><button onclick=\"tryJoiningMatch('"+data.MATCH[0][i].TEAM+"');\">JOIN</button></td></tr>");
			for(i = 0; i < data.MATCH[1].length; ++i)
				$('#mainGameTable > tbody:last').append("<tr><td class='GameID'>"+data.MATCH[1][i].TEAM+"</td><td class='Players'>"+data.MATCH[1][i].PLAYERS+"</td><td class='JoinGame'><s>&nbsp;JOIN&nbsp;&nbsp;</s></td></tr>");
			setTimeout(getSMTInfo, 800);
		}
	});
};

$(function(){
	$('#slider').anythingSlider({
		buildStartStop: false,
		buildArrows: false,
		
		startPanel: 3,
		easing: "easeOutBounce",
		animationTime: 800,
		navigationFormatter: function(index, panel) {
    		return ['Create A Match', 'Join A Match', 'SHOUT !!'][index - 1];
  		},
	});
});

$(document).ready(
	function() {
		getSMTInfo();
		
		$("#SMTSettingsDockIcon").fancybox(fancyboxOptions);
		$("#SMTProfileDockIcon").fancybox(fancyboxOptions);
		
		$("#shoutBtn").click(
			function () {
				$.ajax({
					type: "POST",
					data: "Shout=" + $("#shoutMsgDiv").val(),
					url: "SMT_Internal/setShoutBoxMessage.php",
				});
				$("#shoutMsgDiv").val("");
		});
		
		$("#createNewMatchBtn").click(
			function () {
				$.ajax({
					type: "POST",
					data: "create=1",
					url: "SMT_Internal/createNewMatch.php",
					success: function () {
						window.location = "./";
					}
				});
		});
		
		$("#shoutMsgDiv").keypress(
			function (e) {
				if(e.which == 13)
				{
					$("#shoutBtn").click();
					return false;
				}
		});
	}
);