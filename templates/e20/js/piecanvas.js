/* Javascript Pie Chart, based on
 *   http://www.phpied.com/canvas-pie/
 *   Stoyan Stefanov, ssttoo at ymail
 * and Java Pie Chart http://www.multicians.org/thvv/pie.html
 *
 * THVV 2010-12-12 v1
 * THVV 2011-01-24 v2 add wedge color in col 3, optional label format
 * THVV 2011-03-18 v2.1 bug fix for MSIE 9 (must use <!DOCTYPE html>)
 *
 * Input is in a table, which will be hidden if canvas is usable.
 * Output is a pie chart with colored wedges in the order supplied.
 * Each wedge is labeled (if it is big enough).
 * The first CAPTION tag is the table title; others are drawn at the bottom.
 * args to the Javascript function are
 *  datatableid ID tag for the data table
 *  canvasid    ID tag for the canvas
 *  bgcolor     optional background color as hex, rgb(), or color name
 *  labelformat optional, 0 = label verbatim, 1 = "xx% label (val)"
*/
// Copyright 2010-2011 Tom Van Vleck
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

function piechartcanvas(datatableid, canvasid, bgcolor, labelformat) {
    var background_color = 'white';
    var label_format = 0;
    if (typeof bgcolor !== 'undefined') {
        background_color = bgcolor;
    }
    if (typeof labelformat !== 'undefined') {
        label_format = labelformat;
    }
    // source data table and canvas tag
    var data_table = document.getElementById(datatableid);
    if (typeof data_table === 'undefined') {
        return;
    }
    var canvas = document.getElementById(canvasid);
    if (typeof canvas === 'undefined') {
        return;
    }
    // exit if canvas is not supported
    if (typeof canvas.getContext === 'undefined') {
        return;
    }
    data_table.style.display = 'none'; // bug in MSIE 9, shows content of CANVAS tag
    var TD_LABEL_INDEX = 0; // column 0 TD is label
    var TD_DATA_INDEX = 1;  // column 1 TD contains the data
    var TD_COLOR_INDEX = 2; // column 2 TD contains the color
    var tds, data = [], color, colors = [], titles = [], value = 0, total = 0;
    var captions = data_table.getElementsByTagName('caption'); // all captions
    var charttitle = '', chartsubtitle = '';
    if (typeof captions === 'undefined') {
	// no caption
    } else {
	if (captions.length > 0) {
	    charttitle = captions[0].innerHTML;
	}
	for (var j=1; j < captions.length; j++) { // concat all the other captions into a footer
	    chartsubtitle += captions[j].innerHTML + " ";
	}
    }
    var trs = data_table.getElementsByTagName('tr'); // all TRs
    var COLORx = 0; // if colors not specified
    var COLORTABLE = ['rgb(109,219,255)',
		      'rgb(178,238,35)',
		      'rgb(255,234,94)',
		      'rgb(255,124,158)',
		      'rgb(233,164,255)'];
    for (var i=0; i < trs.length; i++) {
        tds = trs[i].getElementsByTagName('td'); // all TDs
        if (tds.length === 0) continue; //  no TDs in this TR, skip
        value  = parseFloat(tds[TD_DATA_INDEX].innerHTML);
        data[data.length] = value;
        total += value;
	titles[titles.length] = tds[0].innerHTML;
	// colors can be hex to, color name, or rgb()
	if ((typeof tds[TD_COLOR_INDEX] !== 'undefined') && (tds[TD_DATA_INDEX].innerHTML != '')) {
            color  = tds[TD_COLOR_INDEX].innerHTML;
	} else {
            color = COLORTABLE[COLORx++];
	    if (COLORx >= COLORTABLE.length) {COLORx = 0;}
	}
        colors[colors.length] = color;
    } // for
    if (total == 0) {
	total = 1; // avoid div by zero. will display title but no pie.
    }

    // get canvas context, determine radius and center
    var SHRINK_FACTOR = 0.85; // make pie chart smaller than canvas to leave room for title
    var context = canvas.getContext('2d');
    var canvas_size = [canvas.width, canvas.height];
    var radius = Math.min(canvas_size[0], canvas_size[1]) / 2;
    radius = radius * SHRINK_FACTOR;
    var center = [canvas_size[0]/2, canvas_size[1]/2];
    context.fillStyle = background_color;
    context.fillRect(0, 0, context.canvas.width, context.canvas.height);

    var sofar = 0;
    for (var piece in data) { // loop for wedges
        var thisvalue = data[piece] / total;
        context.beginPath();
        context.moveTo(center[0], center[1]); // center of the pie
        context.arc(  // draw next arc
            center[0],
            center[1],
            radius,
            Math.PI * (- 0.5 + 2 * sofar), // -0.5 sets set the start to be top
            Math.PI * (- 0.5 + 2 * (sofar + thisvalue)),
            false
        );
        context.lineTo(center[0], center[1]); // line back to the center
        context.closePath();
        context.fillStyle = colors[piece];    // color
        context.fill();
        sofar += thisvalue;
    } // loop for wedges

    // draw the titles on top of all the wedges
    var MIN_LABELED_WEDGE_FRAC = 0.02; // dont show less than 2%, looks junky
    var RADIUS_FRAC = 1.4; // put label inside the pie, not at the edge
    context.font = 'bold 9pt sans-serif';
    context.fillStyle = '#000'; // black text
    context.textBaseline = 'top';
    context.textAlign = 'center';
    var startArc =  Math.PI/2; // start at north
    var angle = 0;
    for (var piece in data) { // loop for labels
        var thisvalue = data[piece] / total;
    	var arc = thisvalue * 2 * Math.PI;
    	if (thisvalue > MIN_LABELED_WEDGE_FRAC) { // Don't label small wedges.
	    if (label_format == 1) { // convert label to show percentage and absolute number
		titles[piece] = Math.floor(100*thisvalue)+"% "+titles[piece]+" ("+data[piece]+")";
	    }
    	    angle = startArc - (arc/2); // clockwise
    	    var x = center[0] + (radius/RADIUS_FRAC)*Math.cos(angle);
    	    var y = center[1] - (radius/RADIUS_FRAC)*Math.sin(angle);
    	    context.fillText(titles[piece], x, y);
    	} // don't label small wedges
    	startArc -= arc; // go clockwise
    } // loop for labels
    // draw title and subtitle last
    if (charttitle != '') {
	context.font = 'bold 11pt sans-serif';
	context.fillStyle = '#000'; // black text
	context.textBaseline = 'top';
	context.textAlign = 'left';
    	context.fillText(charttitle, 0, 0);
    }
    if (chartsubtitle != '') {
	// I think this will only draw the first line, others will be out of the box.
	context.font = '10pt sans-serif';
	context.fillStyle = '#000'; // black text
	context.textBaseline = 'bottom';
	context.textAlign = 'left';
    	context.fillText(chartsubtitle, 0, canvas.height);
    }
} // piechartcanvas
