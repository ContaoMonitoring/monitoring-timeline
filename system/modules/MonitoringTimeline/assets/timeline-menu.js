/**
 * Move the timeline a given percentage to left or right
 * @param {Number} percentage   For example 0.1 (left) or -0.1 (right)
 */
function move (percentage)
{
	var range = timeline.getWindow();
	var interval = range.end - range.start;

	timeline.setWindow(
	{
		start: range.start.valueOf() - interval * percentage,
		end:   range.end.valueOf()   - interval * percentage
	});
}

/**
 * Zoom the timeline a given percentage in or out
 * @param {Number} percentage   For example 0.1 (zoom out) or -0.1 (zoom in)
 */
function zoom (percentage)
{
	var range = timeline.getWindow();
	var interval = range.end - range.start;

	timeline.setWindow(
	{
		start: range.start.valueOf() - interval * percentage,
		end:   range.end.valueOf()   + interval * percentage
	});
}

// attach events to the navigation buttons
document.getElementById('zoomInTimeline').onclick    = function () { zoom(-0.2); };
document.getElementById('zoomOutTimeline').onclick   = function () { zoom( 0.2); };
document.getElementById('moveLeftTimeline').onclick  = function () { move( 0.2); };
document.getElementById('moveRightTimeline').onclick = function () { move(-0.2); };