jQuery(document).ajaxSuccess(function(evt, xhr, options)
{
	try
	{
		console.log(options.data);
		// Check that the AJAX call involves Contact-Form-7
		if (options.data.match("_wpcf7_is_ajax_call=1") &&
			// options.data.match("="))
		{
			console.log("reload FC challenge");
		}
	}
	catch(e) {}
});
