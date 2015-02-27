jQuery(document).on("submitResponse.default", function(e, response){
    jQuery("#fc-ninja").html("<p>Loading new challenge...</p>");
    jQuery.post(the_ajax_script.ajaxurl,
        { 'action': 'new_fc' },
        function(newfc_ajax){
            jQuery("#fc-ninja").html(newfc_ajax);
        }
    );
});