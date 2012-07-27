var fancyboxOptions = {
	'transitionIn'	:	'elastic',
	'transitionOut':	'elastic',
	'speedIn'		:	600,
	'speedOut'		:	200 
};

$(document).ready(
	function()
	{
		<!--dock menu JS options -->
		$('#dock2').Fisheye(
			{
				maxWidth: 48,
				items: 'a',
				itemsText: 'span',
				container: '.dock-container2',
				itemWidth: 42,
				proximity: 48,
				alignment : 'left',
				valign: 'bottom',
				halign : 'center'
			}
		);
		
		$("#SMT_HiddenFancyMsg").fancybox(fancyboxOptions);
		$("#SMTHelpDockIcon").fancybox(fancyboxOptions);
		$("#SMTAboutDockIcon").fancybox(fancyboxOptions);
	}
);