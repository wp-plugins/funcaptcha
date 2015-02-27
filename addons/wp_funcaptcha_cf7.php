<?php
/**
* setup actions for contact form 7 
**
* @return null
*/
function funcaptcha_register_cf7_actions() {
    // Register the funcaptcha CF7 shortcode
	wpcf7_add_shortcode('funcaptcha', 'funcaptchacf7_tag_handler', true);
	
	// Register the funcaptcha CF7 validation function
	add_filter('wpcf7_validate_funcaptcha', 'funcaptchacf7_validate', 10, 2);
	
	// Register the funcaptcha CF7 tag pane generator
	add_action('admin_init', 'funcaptchacf7_tag_generator');
}

/**
* display [funcaptcha] tag
*
* @return string outputs funcaptcha for the form
*/
function funcaptchacf7_tag_handler($tag) {
	$tag = new WPCF7_Shortcode($tag);
	if( empty($tag->name))
		return '';

	$html = funcaptcha_get_fc_html();

	$html .= '<span class="wpcf7-form-control-wrap ' . $tag->name  . '"> </span>';


	return apply_filters('wpcf7_funcaptcha_html_output', $html);
}

/**
* validate funcaptcha
*
* @return array
*/
function funcaptchacf7_validate($result, $tag) {
	$tag = new WPCF7_Shortcode( $tag );
	$name = $tag->name;

    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    
    if ( $funcaptcha->checkResult($options['private_key']) ) {
		return $result;
    } else {
		$result['valid'] = false;
		$result['reason'] = array($name => $options['error_message']);
		return $result;
    }
}

/**
* setup [funcaptcha] tag
*
* @return null
*/
function funcaptchacf7_tag_generator() {
	wpcf7_add_tag_generator('funcaptcha', 'FunCaptcha', 'funcaptchacf7-tag-pane', 'funcaptchacf7_tag_pane');
}

/**
* display funcaptcha in contact form 7 editor
*
* @return null
*/
function funcaptchacf7_tag_pane($contact_form) {
	?>
	<div id="funcaptchacf7-tag-pane" class="hidden">
		<form action="">
			<table>
			<tr>
				<td><?php _e('Name', 'funcaptcha'); ?><br /><input type="text" name="name" class="tg-name oneline" /></td>
				<td></td>
			</tr>
			</table>
			<div class="tg-tag">
				<?php _e('Copy this code and paste it into the form left.', 'funcaptcha' ); ?>
				<br />
				<input type="text" name="funcaptcha" class="tag" readonly="readonly" onfocus="this.select()" />
			</div>
		</form>
	</div>
	<?php
}
?>
