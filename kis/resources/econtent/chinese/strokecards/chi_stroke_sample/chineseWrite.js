/*
=================================================
================= running flow ==================
=================================================
1:	input coordinates and create component
2:	draw component base
3:	wait for user click
	a)	click wrong, show error
	b)	click right on the start point of the first component
		i)	fill a circle at start point
		START LOOPING
		iii)UpdateCurPt() : find and move to the next empty point (inside the component, and not fill yet)
			1)	GetValidArr
			2)	GetAverageDegree
			3)	Calculate fill radius enlarge
		IF next empty point FOUND
			1)	DrawStep() : fillCircle() : fill green circle
		IF next empty point NOT FOUND
			1)	set fill radius = the whole canvas
			2)	fill the whole canvas
		END LOOPING
4:	finish a component, switch to next component
5:	when all component finish filling
END
*/




var param = {
	width: scaleX(1000),	/* screen width */
	height: scaleX(1000),	/* screen height */
	timer: 80,				/* control drawing speed, smaller = faster */
	originColor: "#CCCCCC",	/* value of original grey color, #CCCCCC = 204 204 204 255 */
	fillR: 80,				/* fill color (red) */
	fillG: 80,				/* fill color (green) */
	fillB: 80,				/* fill color (blue) */
	yShift: scaleX(100),	/* additional Y coordinate for each pixel */
	fullRadius: scaleX(70),	/* radius distance to detect next point */
	drawRadius: 0.9,		/* radius distance to fill circle */
	degInterval: [10, 5, 3],/* degree interval to detect next point */
	radiusPlusAccuracy: 0.1,/* when filling circle, if found many direction blank, fill circle will be enlarge. This value reduct the enlarge extent */
	radiusPlusRange: scaleX(70) * 0.9,	/* control largest radius for filling enlarge */
	detectDist: scaleX(100),	/* radius to detect if mouse click inside start point area */
	crossX: 30,
	crossY: 0,
	crossW: 127,
	crossH: 118
};

// draw data
var data = {
	noOfComp: 0,	/* total number of components */
	comp: [],		/* store each component's info, find "createCompData" for details */
	curComp: 0		/* marking current component to draw */
};
var store;
// timer holder
var tmp;
// alert control
var debug = "";
var showDebug = false;

// function to control coordinate scale
function scaleX (x) {
	return Math.round(x * 0.45);
}

window.onload = function(){
	store = QueryString;
	
	Initialize();
	datarize();
	
	for (var i = 0; i < data.noOfComp; i++) {
		DrawBase(data.comp[i]);
	}
	
	StartApp();
	//DrawStep(data.comp[data.curComp]);
	//tmp = setTimeout(StartDrawAnimate, param.timer);
}

function Initialize() {
}

// An animation loop to fill a component
function StartDrawAnimate() {
	var drawFinish = UpdateCurPt(data.comp[data.curComp]);
	DrawStep(data.comp[data.curComp]);
	if (drawFinish) {
		FillEnd();
	} else {
		tmp = setTimeout(StartDrawAnimate, param.timer);
	}
} function FillEnd () {
	tmp = null;
	data.curComp++;
	if (showDebug) {
		alert(debug);
	}
	debug = "";
}
// Calling start fill current component
function DrawNext () {
	if (data.curComp < data.noOfComp) {
		DrawStep(data.comp[data.curComp]);
		tmp = setTimeout(StartDrawAnimate, param.timer);
	}
}



// inside StartDrawAnimate()
// finding next center point to fill circle
function UpdateCurPt (comp) {
	var drawFinish = false;
	var canvas = comp.cv;
    var ctx = canvas.getContext("2d");
	var curRadius = param.fullRadius + (comp.fullRadiusPlus / param.drawRadius);
	comp.fullRadiusPlus = 0;
	var center = comp.curPt;
	var validArr = [];
	var checkLv = 0;
	do {
		GetValidArr(checkLv, validArr, ctx, center, curRadius);
		checkLv++;
	} while (checkLv < param.degInterval.length  &&  validArr.length == 0);
	
	if (validArr.length > 0) {
		var nextPt = GetPointByDegree(GetAverageDegree(validArr), curRadius, center);
		comp.curPt = nextPt;
		
		// Calculate fill radius enlarge
		comp.curRadiusPlus = 0;
		var totalInterval = 360 / param.degInterval[checkLv - 1];		/* totalInterval = 36/72/120 */
		var percentOfDirection = validArr.length / totalInterval;		/* percentOfDirection = 0 ~ 1 */
		var plusFactor = percentOfDirection - param.radiusPlusAccuracy;	/* plusFactor = 0.0 ~ 0.9 */
		if (plusFactor < 0) { plusFactor = 0; }
		comp.curRadiusPlus = plusFactor * param.radiusPlusRange;		/* curRadiusPlus = (MIN)0 ~ (MAX)70*0.9 */
	} else {
		comp.curRadiusPlus = param.width;
		checkLv = 100;
		drawFinish = true;
	}
	/*
	if (checkLv > 1) {
		alert("validArr : " + validArr
		+ "\nNEW : " + comp.curPt.x + ", " + comp.curPt.y + " --- " + comp.curRadiusPlus + ", LV = " + checkLv
		+ "\nCompNo : " + data.curComp);
	}*/
		debug += "[" + comp.curPt.x + ", " + comp.curPt.y + "] LV: " + checkLv + " / radiusPlus: " + comp.curRadiusPlus + "\n";
	return drawFinish;
} function GetValidArr (checkLV, validArr, ctx, center, curRadius) {
	// Checking from 1~360 degree, distance from a radius,
	// which degrees are empty(inside the component, and not fill yet)
	// storing valid degrees into "validArr"
	var calR = (curRadius * (1 - checkLV * 0.01));
	for (var i = param.degInterval[checkLV]; i <= 360; i+= param.degInterval[checkLV]) {
		var newPt = GetPointByDegree(i, calR, center);
		if (CheckPointUnfill(ctx, newPt.x, newPt.y, false)) {
			validArr.push(i);
		}
		if (checkLV == 3) {
			CheckPointUnfill(ctx, newPt.x, newPt.y, true);
		}
	}
} function CheckPointUnfill(ctx, x, y, dump) {
	if (x < 0  ||  y < 0  ||  x > param.width  || y > param.height) {
		return false;
	}
	var pixel = ctx.getImageData(x, y, 1, 1);
	if (dump == true) {
		if (pixel.data[0] != 0) {
			//alert(pixel.data[0] + "; " + pixel.data[1] + "; " + pixel.data[2]);
		}
	}
	//if (pixel.data[0] == param.originR) {
	if (pixel.data[0] != param.fillR  &&  pixel.data[1] != param.fillG  &&  pixel.data[3] == 255) {
		//alert(pixel.data[0] + "; " + pixel.data[1] + "; " + pixel.data[2]);
		return true;
	}
	return false;
}
function GetPointByDegree (i, curRadius, center) {
	var xMinor = 1;		var yMinor = 1;		var degrees = i;
	if (i <= 90) {
		yMinor = -1;
	} else if (i <= 180) {
		degrees = 180 - degrees;
	} else if (i <= 270) {
		degrees = degrees - 180;
		xMinor = -1;
	} else if (i <= 360) {
		degrees = 360 - degrees;
		xMinor = -1;
		yMinor = -1;
	}
	var angle = degrees * Math.PI / 180;	// radians form
	var cx = Math.floor(center.x + curRadius * Math.sin(angle) * xMinor);
	var cy = Math.floor(center.y + curRadius * Math.cos(angle) * yMinor);
	return {x: cx, y: cy};
} function GetAverageDegree(arr) {
	var avg = 0;
	for (var j = 0; j < arr.length; j++) {
		avg += arr[j];
	}
	avg = Math.floor(avg / arr.length);
	return avg;
}





// function to draw component outline and fill the components
function DrawBase (comp) {
	var canvas = comp.cv;
    var ctx = canvas.getContext("2d");
	drawLine(ctx, comp.dataArr);
} function DrawStep (comp) {
	var canvas = comp.cv;
    var ctx = canvas.getContext("2d");
	drawColorFill(ctx, comp);
} function drawLine (ctx, comp) {
	var debug = "";
	ctx.fillStyle = param.originColor;
	ctx.beginPath();
	
	ctx.moveTo(comp[0].x, comp[0].y);
	
	for (var i=1; i < comp.length-1; i++) {
		if (comp[i].a == 1) {
			ctx.lineTo(comp[i].x, comp[i].y);
			//debug += "lineTo" + "\n";
		} else if (comp[i].a == 0) {
			if (comp[i+1].a == 1) {
				//debug += "quadraticCurveTo" + "\n";
				ctx.quadraticCurveTo(comp[i].x, comp[i].y, comp[i+1].x, comp[i+1].y);
				i++;
			} else if (comp[i+1].a == 0) {
				var xc = (comp[i].x + comp[i+1].x) / 2;
				var yc = (comp[i].y + comp[i+1].y) / 2;
				ctx.quadraticCurveTo(comp[i].x, comp[i].y, xc, yc);
				//debug += "add: " + comp[i].x + ", " + comp[i].y + "; " + xc + ", " + yc + "\n";
			}
		}
	}
	if (debug != "") {
		alert(debug);
	}
	/*
	ctx.strokeStyle = "#009900";
	ctx.lineWidth = 5;
	ctx.stroke();
	*/
	ctx.closePath();
	ctx.fill();
} function drawColorFill(ctx, comp) {
	var radius = (param.fullRadius * param.drawRadius) + comp.curRadiusPlus;
	comp.fullRadiusPlus = comp.curRadiusPlus;
	comp.curRadiusPlus = 0;
	//alert("drawColorFill : " + radius);
	fillCircle(ctx, radius, comp.curPt.x, comp.curPt.y);
}

// fill circle by changing each pixel's color
function fillCircle(ctx, r, cx, cy) {
	var Left = Math.floor(cx - r);
	var Right = Math.floor(Left + r * 2);
	var Top = Math.floor(cy - r);
	var Bottom = Math.floor(Top + r * 2);
	
	if (Left < 0) { Left = 0; }
	if (Top < 0) { Top = 0; }
	if (Right > param.width) { Right = param.width; }
	if (Bottom > param.height) { Bottom = param.height; }
	
	var img = ctx.getImageData(0, 0, param.width, param.height);
	
	for (var nowY = Top; nowY <= Bottom; ++nowY) {
		for (var nowX = Left; nowX <= Right; ++nowX) {
			var dist = Math.pow(cx - nowX, 2.0) + Math.pow(cy - nowY, 2.0);
			if (dist <= Math.pow(r, 2)) {
/*
The CanvasPixelArray contains height x width x 4 bytes of data, with index values ranging
from 0 to (height x width x 4)-1.
For example, to read the blue component's value from the pixel at column 200,
row 50 in the image, you would do the following:

	blueComponent = imageData.data[((50*(imageData.width*4)) + (200*4)) + 2];
*/
				var redIndex = (nowY*param.width*4) + (nowX*4);
				if (img.data[redIndex+3] == 255) {
				//if (img.data[redIndex] != param.fillR  &&  img.data[redIndex+1] != param.fillG  &&  img.data[redIndex+3] == 255) {
					img.data[redIndex] = param.fillR;
					img.data[redIndex+1] = param.fillG;	// green
					img.data[redIndex+2] = param.fillB;
					img.data[redIndex+3] = 255;
				}
			}
		}
	}
	ctx.putImageData(img, 0, 0);
}


function createCompData() {
	var compdata = {
		dataArr: [],		/* storing coordinate by array [x, y, a] */
		cv: null,			/* canvas holding this component graphics */
		curPt: null,		/* store start point, then each fill point when filling circle animation */
		curRadiusPlus: 0,	/* when filling animation, store each step filling enlarge radius */
		fullRadiusPlus: 0,	/* store the previous step enlarge radius, increase next step searching radius */
		addData: function (px, py, type) {
			var child = {x: scaleX(px), y: scaleX(py) + param.yShift, a: type};
			compdata.dataArr.push(child);
		},
		setCurPt: function (px, py) {
			compdata.curPt = {x: scaleX(px), y: scaleX(py) + param.yShift};
		}
	};
	return compdata;
} function AddCanvas (comp, compId) {
	comp.cv = document.createElement("canvas");
	comp.cv.setAttribute("id", "canvas_" + compId);
	comp.cv.setAttribute("width", param.width);
	comp.cv.setAttribute("height", param.height);
	comp.cv.setAttribute("class", "penCanvas");
	comp.cv.style.zIndex = String(20 - compId);
	document.getElementById("container").appendChild(comp.cv);
} function AddMouseEvent (canvas) {
	canvas.addEventListener("click", function (evt) {
		if (tmp == null) {
			if (data.curComp < data.noOfComp) {
				var mousePos = getMousePos(canvas, evt);
				// check hit current component
				if (HitComponent(data.comp[data.curComp], mousePos) == true) {
					// check hit start point
					if (HitStartPoint(mousePos)) {
						HideWrongMark();
						DrawNext();
					} else {
						ShowWrongMark(mousePos);
					}
				} else {
					// check hit another component
					for (var i = 0; i < data.noOfComp; i++) {
						if (i != data.curComp) {
							if (HitComponent(data.comp[i], mousePos) == true) {
								ShowWrongComp(i);
								break;
							}
						}
					}
					//ShowWrongMark(mousePos);
				}
			}
		}
	}, false);
} function getMousePos (canvas, evt) {
	var rect = canvas.getBoundingClientRect();
	return { x: evt.clientX - rect.left, y: evt.clientY - rect.top };
} function HitComponent (comp, mousePos) {
	var ctx = comp.cv.getContext("2d");
	var pixel = ctx.getImageData(mousePos.x, mousePos.y, 1, 1);
	if (pixel.data[3] == 255) {
		return true;
	}
	return false;
} function HitStartPoint (mousePos) {
	var startPt = data.comp[data.curComp].curPt;
	var slope = Math.sqrt( Math.pow((startPt.x - mousePos.x),2) + Math.pow((startPt.y - mousePos.y),2) );
	if (slope < param.detectDist) {
		return true;
	}
	return false;
} function ShowWrongMark (mousePos) {
	document.getElementById("wrongMark").style.display = "block";
	document.getElementById("wrongMark").style.left = String(mousePos.x-15) + "px";
	document.getElementById("wrongMark").style.top = String(mousePos.y-15) + "px";
	// cross position
	if ( (mousePos.x-15 + param.crossX + param.crossW) > param.width) {
		document.getElementById("crossMark").style.left = String(0 - param.crossW) + "px";
	} else {
		document.getElementById("crossMark").style.left = String(param.crossX) + "px";
	}
	if ( (mousePos.y-15 + param.crossY + param.crossH) > param.height) {
		document.getElementById("crossMark").style.top = String(30 - param.crossH) + "px";
	} else {
		document.getElementById("crossMark").style.top = String(param.crossY) + "px";
	}
	
	$("#wrongMark").clearQueue();
	// write here
	$("#wrongMark").animate({opacity:1}, 200).animate({opacity:0}, 200).animate({opacity:1}, 200).animate({opacity:1}, 500).animate({opacity:0}, 200
		, function () { document.getElementById("wrongMark").style.display = "none"; });
} function HideWrongMark () {
	document.getElementById("wrongMark").style.display = "none";
} function ShowWrongComp (compIndex) {
	$("#canvas_" + compIndex).animate({opacity:0}, 200).animate({opacity:1}, 200).animate({opacity:0}, 200).animate({opacity:1}, 200);
}


function AddComp (dataArr) {
	var comp = createCompData();
	for (var i = 1; i < dataArr.length; i++) {
		comp.addData(dataArr[i][0], dataArr[i][1], dataArr[i][2]);
	}
	data.comp[data.noOfComp] = comp;
	comp.setCurPt(dataArr[0][0], dataArr[0][1]);
	AddCanvas(comp, data.noOfComp);
	data.noOfComp++;
}

// converting raw data into component data
function datarize() {
	if (store.index == 0) {
		AddComp( [ [68, 342],
		[68, 342, 1], [836, 342, 1], [892, 290, 1], [964, 362, 1], [144, 362, 0], [104, 374, 1], [68, 342, 1] ] );
	}
	else if (store.index == 1) {
		AddComp( [ [168, 192],
		[168, 192, 1], [736, 192, 1], [792, 140, 1], [864, 212, 1], [244, 212, 0], [204, 224, 1], [168, 192, 1] ] );
		AddComp( [ [68, 592],
		[68, 592, 1], [836, 592, 1], [892, 540, 1], [964, 612, 1], [144, 612, 0], [104, 624, 1], [68, 592, 1] ] );
	}
	else if (store.index == 2) {
		AddComp( [ [218, 92],
		[218, 92, 1], [686, 92, 1], [742, 40, 1], [814, 112, 1], [294, 112, 0], [254, 124, 1], [218, 92, 1] ] );
		AddComp( [ [218, 392],
		[218, 392, 1], [686, 392, 1], [742, 340, 1], [814, 412, 1], [294, 412, 0], [254, 424, 1], [218, 392, 1] ] );
		AddComp( [ [68, 762],
		[68, 762, 1], [836, 762, 1], [892, 710, 1], [964, 782, 1], [144, 782, 0], [104, 794, 1], [68, 762, 1] ] );
	}
	else if (store.index == 3) {
		AddComp( [ [68, 392],
		[68, 392, 1], [836, 392, 1], [892, 340, 1], [964, 412, 1], [144, 412, 0], [104, 424, 1], [68, 392, 1] ] );
		
		AddComp( [ [456, 12],
		//[456, 10, 1], [516, 10, 1], [516, 404, 1], [456, 404, 1], [456, 10, 1], [456, 10, 1] ] );
		[456, -40, 1], [546, 30, 1], [516, 70, 1], [516, 884, 1], [436, 804, 1], [456, 754, 0], [456, -43, 1], [456, -43, 1] ] );
	}
	
//	AddComp( [ [100, 600],
//	[100, 600, 1], [600, 600, 0], [600, 700, 0], [100, 700, 0], [100, 595, 1] ] );
	
	//AddMouseEvent(data.comp[data.noOfComp - 1].cv);
}

function StartApp() {
	AddMouseEvent(data.comp[0].cv);
}



/*
	AddComp( [ [68, -8],
	[68, -8, 1], [836, -8, 1], [892, -60, 1], [964, 12, 1], [144, 12, 0], [104, 24, 1], [68, -8, 1] ] );
	
	AddComp( [ [456, 12],
	//[456, 10, 1], [516, 10, 1], [516, 404, 1], [456, 404, 1], [456, 10, 1], [456, 10, 1] ] );
	[456, 10, 1], [516, 10, 1], [516, 404, 1], [456, 404, 1], [456, 8, 1], [456, 8, 1] ] );
	
	AddComp( [ [516, 188],
	[516, 188, 1], [800, 188, 1], [856, 136, 1], [928, 208, 1], [516, 208, 1], [516, 188, 1] ] );
	
	AddComp( [ [164, 112],
	[164, 112, 1], [164, 228, 0], [140, 392, 1], [112, 416, 1], [180, 464, 1], [208, 424, 1],
	[824, 424, 1], [796, 704, 0], [770, 740, 1], [744, 776, 0], [688, 772, 1], [632, 768, 0],
	[540, 752, 1], [540, 772, 1], [700, 812, 0], [704, 872, 1], [788, 852, 0], [824, 782, 1],
	[860, 712, 0], [884, 436, 1], [912, 412, 1], [848, 364, 1], [816, 404, 1], [204, 404, 1],
	[228, 188, 1], [264, 156, 1], [146, 112, 1] ] );
*/