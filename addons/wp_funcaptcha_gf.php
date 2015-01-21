<?php
/**
 * Registers all the actions and filters necessary to integrate FunCaptcha with GF.
 */
function funcaptcha_register_gf_actions() {
	add_filter('gform_add_field_buttons', 'funcaptcha_gf_add_button');
	add_filter('gform_field_type_title', 'funcaptcha_gf_add_field_title');
	add_filter('gform_field_validation', 'funcaptcha_gf_validate', 10, 4);
	add_filter("gform_field_input", "funcaptcha_gf_field", 10, 5);
}

/**
 * Shows the FunCaptcha
 */
function funcaptcha_gf_field($input, $field, $value, $lead_id, $form_id) {

	if ($field['type'] == 'FunCaptcha'){
		if (!is_admin()) {
			$input .= funcaptcha_get_fc_html();
		} else {
			$input = $input . '<img src=' . plugins_url() . '/funcaptcha/images/funcaptcha_gf.png />';
		}
	}
	
	return $input;
}

/**
 * Adds the FunCaptcha advance field to GF
 */
function funcaptcha_gf_add_button($field_groups) {
	
	require_once("wp_funcaptcha_gf_js.php");
	
	$field_groups[1]["fields"][] = array("class"=>"button", "value" => GFCommon::get_field_type_title("funcaptcha"), "onclick" => "funcaptcha_gf_add_field('FunCaptcha')");
	
	return $field_groups;
}

/**
 * Changes the field title to FunCaptcha
 */
function funcaptcha_gf_add_field_title($type) {
	
	if ($type == 'funcaptcha') {
		return 'FunCaptcha';
	} else {
		return $type;
	}
}

/**
 * Validates FunCaptcha
 */
function funcaptcha_gf_validate($result, $value, $form, $field) {
	if ($field['type'] == 'FunCaptcha') {
		$options = funcaptcha_get_settings();
		$funcaptcha = funcaptcha_API();
		if($result["is_valid"] && $funcaptcha->checkResult($options['private_key'])){
			
		} else {
			$result["is_valid"] = false;
			$result["message"] = $options['error_message'];
		}
	}
    return $result;
}

?>