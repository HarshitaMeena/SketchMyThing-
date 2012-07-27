var isMouseDown;
var readEndPoint = 0;
var mouseData;

var isMouseDown;
var oldMouseX;
var oldMouseY;

var lineWid;

var simulationSpeed = 15;

function readStateFile()
{
    $.ajax({
        type: "POST",
        data: "FROM=" + readEndPoint.toString(),
        dataType: 'json',
        url: "SMT_Internal/getCanvasInfo.php",
        success: function(data) {
            readEndPoint = parseInt(data.POS);
            mouseData += data.DATA;
            setTimeout(readStateFile, 500);
        }
    });
}

function simulateMouse()
{
    if(mouseData.length < 1)
    {
        setTimeout(simulateMouse, simulationSpeed);
        return;
    }

    var Token = mouseData.substring(0, mouseData.indexOf(" "));
    mouseData = mouseData.substr(mouseData.indexOf(" ")+1);

    var Action = Token.charAt(0);

    if(Action == 'U')
        isMouseDown = false;
    else if(Action == 'L')
    {
        canvasContext.clearRect(0, 0, mainCanvas.width(), mainCanvas.height());
        canvasContext.fillStyle = 'rgb(0, 0, 0)';
        canvasContext.strokeStyle = 'rgb(0, 0, 0)';
        lineWid = 2;
        canvasContext.lineWidth = 2;
    }
    else if(Action == 'C')
    {
        var ColorX = Token.substr(Token.indexOf('-')+1);
        canvasContext.fillStyle = ColorX;
        canvasContext.strokeStyle = ColorX;
    }
    else if(Action == 'S')
    {
        var SizeX = parseInt(Token.substr(Token.indexOf('-')+1));
        lineWid = SizeX;
        canvasContext.lineWidth = SizeX;
    }
    else if(Action == 'M')
    {
        var CoordX = parseInt(Token.substring(Token.indexOf('-')+1, Token.lastIndexOf('-')));
        var CoordY = parseInt(Token.substr(Token.lastIndexOf('-')+1));

        oldMouseX = CoordX;
        oldMouseY = CoordY;
        canvasContext.lineTo(oldMouseX, oldMouseY);
        canvasContext.stroke();
    }
    else
    {
        var CoordX = parseInt(Token.substring(Token.indexOf('-')+1, Token.lastIndexOf('-')));
        var CoordY = parseInt(Token.substr(Token.lastIndexOf('-')+1));
        isMouseDown = true;
        oldMouseX = CoordX;
        oldMouseY = CoordY;
        canvasContext.beginPath();
        canvasContext.moveTo(oldMouseX, oldMouseY);
        canvasContext.fillRect(oldMouseX, oldMouseY, lineWid, lineWid);
    }

    setTimeout(simulateMouse, simulationSpeed);
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

        setTimeout(readStateFile, 500);
        simulateMouse();
});
