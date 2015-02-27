<?php
function ninja_forms_register_field_funcaptcha(){
	$args = array(
		'name' => __( 'FunCaptcha', 'wp-funcaptcha-nf' ),
		'edit_function' => '',
		'display_function' => 'ninja_forms_field_funcaptcha_display',
		'group' => 'standard_fields',
		'edit_label' => true,
		'edit_label_pos' => true,
		'edit_req' => false,
		'edit_custom_class' => false,
		'edit_help' => true,
		'edit_meta' => false,
		'sidebar' => 'template_fields',
		'display_label' => true,
		'edit_conditional' => false,
		'conditional' => array(
			'value' => array(
				'type' => 'text',
			),
		),
		'pre_process' => 'ninja_forms_field_funcaptcha_pre_process',
		'process_field' => false,
		'limit' => 1,
		'edit_options' => array(
		),
		'req' => true,
	);

	ninja_forms_register_field('_funcaptcha', $args);
}
function ninja_funcaptcha_setup() {
	add_action('init', 'ninja_forms_register_field_funcaptcha');    
    wp_enqueue_script( 'ninja-fc-ajax-new-fc', plugin_dir_url( __FILE__ ) . 'ajax.js', array( 'jquery' ) );
    wp_localize_script( 'ninja-fc-ajax-new-fc', 'the_ajax_script', array( 'ajaxurl' => admin_url(  'admin-ajax.php' ) ) );
    add_action( 'wp_ajax_new_fc', 'ninja_funcaptcha_ajax_new_fc' );
    add_action( 'wp_ajax_nopriv_new_fc', 'ninja_funcaptcha_ajax_new_fc' );
}

function ninja_funcaptcha_ajax_new_fc(){
	echo funcaptcha_get_fc_html();
    die();
}

function ninja_forms_field_funcaptcha_display($field_id, $data){
	echo '<div id="fc-ninja">'.funcaptcha_get_fc_html().'</div>';
}

function ninja_forms_field_funcaptcha_pre_process( $field_id, $user_value ){
	global $ninja_forms_processing;

	$options = funcaptcha_get_settings();
	$funcaptcha = funcaptcha_API();

	$funcaptcha_error = __(htmlentities($options['error_message']),'wp-funcaptcha-nf');

	$field_row = ninja_forms_get_field_by_id($field_id);
	$field_data = $field_row['data'];
	$form_row = ninja_forms_get_form_by_field_id($field_id);
	$form_id = $form_row['id'];
	
	
	if ( $ninja_forms_processing->get_action() != 'save' && $ninja_forms_processing->get_action() != 'mp_save' && ! $funcaptcha->checkResult($options['private_key']) ){
		$ninja_forms_processing->add_error('funcaptcha-general', $funcaptcha_error, 'general');
		$ninja_forms_processing->add_error('funcaptcha-'.$field_id, $funcaptcha_error, $field_id);				
	}
}