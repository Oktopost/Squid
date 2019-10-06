$(document).ready(function ()
{
	var INCORRECT_TAB = '        ';
	
	function fixTabs() 
	{ 
		var codeBlocks = $(this).html();
		var pos = codeBlocks.indexOf(INCORRECT_TAB);
		
		while (pos > -1)
		{
			codeBlocks = codeBlocks.replace(INCORRECT_TAB, '\t');
			pos = codeBlocks.indexOf(INCORRECT_TAB);
		}
		
		$(this).html(codeBlocks);
	}
	
	$('.code').each(fixTabs);
	$('.highlight').each(fixTabs);
});