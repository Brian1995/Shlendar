
$(init);

function init() {
	jQuery('#datetimepicker-from').datetimepicker({
		mask:true,
		format:'Y-m-d H:i'
	});
	
        jQuery('#datetimepicker-to').datetimepicker({
		mask:true,
		format:'Y-m-d H:i'
	});
        
	$(document).ready(function () { 
		updateActionItemsAlignment();
		$(window).resize(function() {
			updateActionItemsAlignment();
		});
	});
	
}

function updateActionItemsAlignment() {
	$('.action-container').each(function() {
		var containerWidth = $(this).width();
		var maxChildWidth = 0;
		$(this).find('.action span').each(function() {
			spanWidth = $(this).width();
			maxChildWidth = Math.max(maxChildWidth, spanWidth);
		});
		var padding = (containerWidth - maxChildWidth) / 2;
		$(this).find('.action span').each(function() {
			$(this).css("padding-left", padding+"px");
		});
	});
}

