(function()
{
	var lastRequest = 0;
	var lastRef = '';
	
	
	function processHTML(data)
	{
		data = $(data);
		
		data.find('.last').removeClass('last').addClass('last');
		
		$('nav [role="navigation"]').html(data.find('nav [role="navigation"]').html());
		$('div.wy-nav-content').html(data.find('div.wy-nav-content').html());
	}
	
	function sendRequest(href)
	{
		lastRequest++;
		lastRef = href;
		
		var currRequest = lastRequest;
		
		$.get(href, function(data) 
		{
			if (currRequest !== lastRequest)
				return;
			
			history.pushState({}, '', lastRef);
			processHTML(data); 
		});
	}
	
	
	$('body').on('click', 'a', function(e)
	{
		var ref = $(e.target).attr('href') || '';
		
		if (window.location.origin === 'file://')
		{
			return true;
		}
		
		if (ref.length > 0 && ref[0] === '#')
		{
			return true;
		}
		else 
		{
			sendRequest(ref);
			return false;
		}
	});
	
	setTimeout(function()
	{
		$('.last').removeClass('last').addClass('last');
	},
	0.2);
})();