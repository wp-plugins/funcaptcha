<?php

/**
* setup actions for contact form 7 
**
* @return null
*/
function funcaptcha_register_cf7_actions() {
    // Register the funcaptcha CF7 shortcode
	funcaptchacf7_register_shortcode();
	
	// Register the funcaptcha CF7 validation function
	add_filter('wpcf7_validate_funcaptcha', 'funcaptchacf7_validate', 10, 1);
	
	// Register the funcaptcha CF7 tag pane generator
	add_action('admin_init', 'funcaptchacf7_tag_generator');
}

/**
* setup [funcaptcha] tag
*
* @return null
*/
function funcaptchacf7_register_shortcode() {
	wpcf7_add_shortcode('funcaptcha', 'funcaptchacf7_tag_handler');
}

/**
* display [funcaptcha] tag
*
* @return string outputs funcaptcha for the form
*/
function funcaptchacf7_tag_handler($atts) {
	$funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    
    return $funcaptcha->getFunCaptcha($options['public_key']);
}

/**
* validate funcaptcha
*
* @return array
*/
function funcaptchacf7_validate($errors) {

    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    
    if ( $funcaptcha->checkResult($options['private_key']) ) {
		return $errors;
    } else {
		global $CF7_ERROR_MESSAGE;
		$errors['valid'] = false;
		$errors['reason']['your-message'] = __($options['error_message'], 'funcaptcha');
		return $errors;
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
function funcaptchacf7_tag_pane(&$contact_form) {
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
