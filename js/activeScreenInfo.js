var isMouseDown;
var oldMouseData;
var mouseData;

var isMouseDown;
var oldMouseX;
var oldMouseY;

var lineWid;

function saveStateFile()
{	
	if((mouseData.length < 1) || (oldMouseData.length))
	{
		setTimeout(saveStateFile, 200);
		return;
	}
	
	oldMouseData = mouseData;
	mouseData = "";
	$.ajax({
		type: "POST",
		data: "STATE=" + oldMouseData,
		url: "SMT_Internal/saveCanvasInfo.php",
		error: function(xhr, textStatus, errorThrown){
			mouseData = oldMouseData + mouseData;
			oldMouseData = "";
			setTimeout(saveStateFile, 200);
		},
		success: function() {
			oldMouseData = "";
			setTimeout(saveStateFile, 200);
		}
	});
}

var clearDrawingArea = function() {
	canvasContext.clearRect(0, 0, mainCanvas.width(), mainCanvas.height());
	canvasContext.fillStyle = 'rgb(0, 0, 0)';
	canvasContext.strokeStyle = 'rgb(0, 0, 0)';
	canvasContext.lineJoin = 'round';
	
	lineWid = 2;
	canvasContext.lineWidth = 2;
	mouseData += "L ";
}

function setDMTColor(r,g,b)
{
	canvasContext.fillStyle = 'rgb(' + r.toString() + ',' + g.toString() +',' + b.toString() + '0) ';
	canvasContext.strokeStyle = 'rgb(' + r.toString() + ',' + g.toString() +',' + b.toString() + '0) ';
	mouseData += "C-" + 'rgb(' + r.toString() + ',' + g.toString() +',' + b.toString() + '0) ';
}

function setDMTSize(s)
{
	lineWid = s;
	canvasContext.lineWidth = s;
	mouseData += "S-" + s.toString() + " ";
}

$(document).ready(
	function() {
		canvasContext.fillStyle = 'rgb(0, 0, 0)';
		canvasContext.strokeStyle = 'rgb(0, 0, 0)';
		canvasContext.lineJoin = 'round';
		canvasContext.lineWidth = 2;
	
		isMouseDown = false;
		lineWid = 2;
		mouseData = "";
		oldMouseData = "";
		
		mainCanvas
			.mouseup(
				function(evt) {
					if(evt.button != 0)
						return;
					mouseData += "U-" + oldMouseX.toString() + "-" + oldMouseY.toString() + " ";
					isMouseDown = false;
			})
			.mousemove(
				function(evt) {
					if(!isMouseDown)
						return;
					oldMouseX = evt.pageX - canvasOffsetX;
					oldMouseY = evt.pageY - canvasOffsetY;
					mouseData += "M-" + oldMouseX.toString() + "-" + oldMouseY.toString() + " ";
					canvasContext.lineTo(oldMouseX, oldMouseY);
					canvasContext.stroke();
			})
			.mousedown(
				function(evt) {
					isMouseDown = true;
					canvasContext.beginPath();
					oldMouseX = evt.pageX - canvasOffsetX;
					oldMouseY = evt.pageY - canvasOffsetY;
					canvasContext.moveTo(oldMouseX, oldMouseY);
					mouseData += "D-" + oldMouseX.toString() + "-" + oldMouseY.toString() + " ";
					canvasContext.fillRect(oldMouseX, oldMouseY, lineWid, lineWid);
			});

		$(".colorTool").click(
			function() {
				$(".colorTool").removeClass("selectedTool");
				$(this).addClass("selectedTool");
		});

		$(".sizeTool").click(
			function() {
				$(".sizeTool").removeClass("selectedTool");
				$(this).addClass("selectedTool");
		});

		clearDrawingArea();
		
		setTimeout(saveStateFile, 400);
});