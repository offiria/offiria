//var colHeight = Math.max($('#main-content').height(), $('#sidebar').height());
//$('#main-content, #sidebar').height(colHeight);

function equalHeight(group) {
	var tallest = 0;
	group.each(function() {
		var thisHeight = $(this).height();
		if(thisHeight > tallest) {
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}