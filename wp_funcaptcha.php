<?php
/**
 * @package FunCaptcha
 * @version 1.3.2
 */
/*
Plugin Name: FunCaptcha
Plugin URI:  http://wordpress.org/extend/plugins/funcaptcha/
Description: Stop spammers with a fun, fast mini-game! FunCaptcha is free, and works on every desktop and mobile device.
Author: SwipeAds
Author URI: http://funcaptcha.co/
Version: 1.3.2
*/
define('FUNCAPTCHA_VERSION', '1.3.2');
define('PLUGIN_BASENAME', plugin_basename(__FILE__));
define('FUNCAPTCHA_SETTINGS_URL', 'funcaptcha');
if ( ! defined( 'PLUGIN_PATH' ) ) {
    define( 'PLUGIN_PATH',  dirname( __FILE__ )  );
}
require_once(PLUGIN_PATH . "/funcaptcha.php");
require_once(PLUGIN_PATH . "/addons/wp_funcaptcha_cf7.php");
require_once(PLUGIN_PATH . "/addons/wp_funcaptcha_gf.php");
require_once(PLUGIN_PATH . "/addons/wp_funcaptcha_ninjaforms.php");

add_filters_actions();

/**
* Added wordpress filters for funcaptcha
*
* @return null
*/
function add_filters_actions() {
    register_deactivation_hook(__FILE__, 'funcaptcha_deactivate');
    add_action('init', 'funcaptcha_register_style');
    add_action('login_enqueue_scripts', 'funcaptcha_login_styles');
    add_action( 'admin_menu', 'funcaptcha_add_admin_menu' );
    add_filter( 'plugin_action_links_' . PLUGIN_BASENAME, 'funcaptcha_register_plugin_action_links', 10, 1);
    add_filter('plugin_row_meta', 'funcaptcha_register_plugin_meta_links', 10, 2);
    add_action('init', 'funcaptcha_init', 5);
}

/**
* Setup where to display on forms
*
* @return null
*/
function funcaptcha_init() {
    funcaptcha_addons();
    funcaptcha_display_admin();

    $funcaptcha_options = funcaptcha_get_settings();

    //if keys are added, then add hooks.
    if (!funcaptcha_is_key_missing()) {
        //if comment_form is set in the options, attach to the comment hooks
        if( $funcaptcha_options['comment_form'] ) {
            add_action('comment_form', 'funcaptcha_comment_form');
            add_filter('preprocess_comment', 'funcaptcha_comment_post', 9, 1);
        }
        
        // If register_form is set in the options, attach to the register hooks
        if( $funcaptcha_options['register_form'] ) {
            if (is_multisite()) {
                if (BP_INSTALLED) {
                    add_action('bp_before_registration_submit_buttons', 'funcaptcha_register_form_bp');
                    add_action('bp_signup_validate', 'funcaptcha_register_post_bp');
                }
                add_action( 'signup_extra_fields', 'funcaptcha_register_form_wpmu' );
                add_filter( 'wpmu_validate_user_signup', 'funcaptcha_register_post_wpmu' );
            } else {
                if (BP_INSTALLED) {
                    add_action('bp_before_registration_submit_buttons', 'funcaptcha_register_form_bp');
                    add_action('bp_signup_validate', 'funcaptcha_register_post_bp');
                }
                add_action('register_form', 'funcaptcha_register_form');
                add_action('register_post', 'funcaptcha_register_post', 9, 3);
            }
        }

        if( $funcaptcha_options['login_form'] ) {
            add_action('login_form', 'funcaptcha_login_form');
            add_filter('authenticate', 'funcaptcha_login_post', 9, 3);
        }

        // If password_form is set in the options, attach to the lost password hooks    
        if( $funcaptcha_options['password_form'] ) {
            add_action('lostpassword_form', 'funcaptcha_lost_password_form');
            add_action('lostpassword_post', 'funcaptcha_lost_password_post');
        }

        // Registers the funcaptcha CF7 Actions if plugin is activated
        if (CF7_INSTALLED) {
            funcaptcha_register_cf7_actions();
        }

        if (GF_DETECTED) {
           funcaptcha_register_gf_actions();
        }


        if(BBPRESS_INSTALLED) {
            if ($funcaptcha_options['bbpress_topic']) {
                add_action('bbp_theme_after_topic_form_content', 'funcaptcha_bbpress_form');
                add_action('bbp_new_topic_pre_extras', 'funcaptcha_bbpress_validate');
            }
            if ($funcaptcha_options['bbpress_reply']) {
                add_action('bbp_theme_after_reply_form_content', 'funcaptcha_bbpress_form');
                add_action('bbp_new_reply_pre_extras', 'funcaptcha_bbpress_validate');
            }           
        }

        if(NINJAFORMS_INSTALLED) {
            ninja_funcaptcha_setup();
        }
    }
}

/**
* Add action to display error to admin users if not finished setup
*
* @return null
*/
function funcaptcha_display_admin() {
    // Adds an admin notice to set the keys if not set
    if (funcaptcha_is_key_missing()) {
        add_action('admin_footer', 'funcaptcha_display_keys_notice');
    }
}

/**
* Adds funcaptcha to the plugins options
*
* @return null
*/
function funcaptcha_add_admin_menu() {
    add_submenu_page('plugins.php', 'FunCaptcha', 'FunCaptcha', 'manage_options', __FILE__, 'funcaptcha_show_settings');
}

/**
* check to see if other plugins are installed
*
* @return null
*/
function funcaptcha_addons() {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    define('CF7_INSTALLED', is_plugin_active('contact-form-7/wp-contact-form-7.php'));
    define('BP_INSTALLED', is_plugin_active('buddypress/bp-loader.php'));
    define('GF_DETECTED', is_plugin_active('gravityforms/gravityforms.php'));    
    define('BBPRESS_INSTALLED', is_plugin_active('bbpress/bbpress.php'));
    define('NINJAFORMS_INSTALLED', is_plugin_active('ninja-forms/ninja-forms.php'));    
}

if (function_exists('bp_is_register_page')) { 
    $is_buddypress = true;
}

/**
* setup funcaptcha settings url
*
* @return null
*/
function funcaptcha_register_plugin_action_links($links) {

    $settings_link = '<a href="plugins.php?page='.PLUGIN_BASENAME.'">' . __('Settings', 'captcha') . '</a>';
    array_unshift( $links, $settings_link );

    return $links;
}

/**
* enable meta links
*
* @return links array url links for being displayed under plugin
*/
function funcaptcha_register_plugin_meta_links($links, $file) {

    if ($file == PLUGIN_BASENAME) {

        $links[] = '<a href="plugins.php?page='.PLUGIN_BASENAME.'">' . __('Settings','captcha') . '</a>';
        $links[] = '<a href="http://www.funcaptcha.co/contact-us">' . __('Contact','captcha') . '</a>';
    }
    return $links;
}


/**
* register stylesheet for registration
*
* @return null
*/
function funcaptcha_register_style() {
    wp_register_style('funcaptchaStylesheet', plugins_url('css/min_funcaptcha_styles.css', __FILE__));
}

/**
* register stylesheet for login
*
* @return null
*/
function funcaptcha_login_styles() {
    $action = ( isset( $_GET['action'] ) ) ? $_GET['action'] : '';
    $options = funcaptcha_get_settings();

    if ( ( $action == 'lostpassword' && $options['password_form'] ) || ( $action == 'register' && $options['register_form'] ) ) {
        ?><style type="text/css">#login{width: 418px !important;}</style><?php
    }
}


/**
* Create an instance of the funcaptcha object
*
* @return object funcaptcha object
*/
function funcaptcha_API() {
    $fc = new FUNCAPTCHA();
    $options = funcaptcha_get_settings();
    $fc->setSecurityLevel($options['security_level']);
    $fc->setTheme($options['theme']);
    $fc->setLightboxMode($options['lightbox_mode']);
    return $fc;
}

/**
* Show correct settings screen
*
* @return string echo of HTML
*/
function funcaptcha_show_settings() {
    
    $funcaptcha_options = funcaptcha_get_settings();
    $action = ( isset( $_POST['funcaptcha']['action'] ) ) ? $_POST['funcaptcha']['action'] : '';
    if ($action) {
        if ( ! current_user_can('activate_plugins') ) {
            die("Unauthorized to change settings. User is not an allowed to change plugin.");
        }
    }
    switch ( $action ) {
        case 'install':
            funcaptcha_install_plugin();
            break;
        case 'upgrade':
            funcaptcha_upgrade_plugin();
            break;
        case 'settings':
            funcaptcha_install_plugin();
            break;
        default:
            $action = funcaptcha_check_for_upgrade_or_install();
            break;
    }
    
    wp_enqueue_style('funcaptchaStylesheet');

    //debug code
    if (!in_array($action, array('install', 'upgrade', 'settings'))) {
        error_log("FATAL ERROR:"
                    . print_r(debug_backtrace(), true));
        echo "Plugin error. Please contact the FunCaptcha support team.";
        return;
    }
    
    switch ($action) {
        case 'install':
            $page_opts = funcaptcha_get_install_page_options($action);
            break;
        case 'upgrade':
            $page_opts = funcaptcha_get_upgrade_page_options($action);
            break;
        case 'settings':
            $page_opts = funcaptcha_get_settings_page_options();
            break;
    }
    
    echo "<div class='wrap'>";
    echo "<h2><img src='" . plugins_url('images/fc_meta_logo.png', PLUGIN_BASENAME) . "' style='max-width:100%;' alt='FunCaptcha'></h2>";
    
    if ($page_opts['flash_message']) {
        echo $page_opts['flash_message'];
    }
        
    $funcaptcha_options = funcaptcha_get_settings();
    //if no keys, default to Activate tab
    if (funcaptcha_is_key_missing()){
        if (!$_POST) {
            $_POST['funcaptcha-page'] = "Activate";
        }
    } else if (!$_POST['funcaptcha-page']) {
        $_POST['funcaptcha-page'] = "Settings";
    }

    switch ($_POST['funcaptcha-page']) {
        case "Settings" :
            include('wp_funcaptcha_admin.php');
        break;
        case "Activate" :
            include('wp_funcaptcha_admin_activate.php');
        break;
        default: 
            $_POST['funcaptcha-page'] = "Settings";
            include('wp_funcaptcha_admin.php');
    }
    echo "</div>";
}

/**
* Show register screen
*
* @return string echo of HTML
*/
function funcaptcha_show_register() {
    
    $funcaptcha_options = funcaptcha_get_settings();
    $action = ( isset( $_POST['funcaptcha']['action'] ) ) ? $_POST['funcaptcha']['action'] : '';
    switch ( $action ) {
        case 'install':
            funcaptcha_install_plugin();
            break;
        case 'upgrade':
            funcaptcha_upgrade_plugin();
            break;
        case 'settings':
            funcaptcha_install_plugin();
            break;
        default:
            $action = funcaptcha_check_for_upgrade_or_install();
            break;
    }
    
    wp_enqueue_style('funcaptchaStylesheet');

    //debug code
    if (!in_array($action, array('install', 'upgrade', 'settings'))) {
        error_log("FATAL ERROR:"
                    . print_r(debug_backtrace(), true));
        echo "Plugin error. Please contact the FunCaptcha support team.";
        return;
    }
    
    switch ($action) {
        case 'install':
            $page_opts = funcaptcha_get_install_page_options($action);
            break;
        case 'upgrade':
            $page_opts = funcaptcha_get_upgrade_page_options($action);
            break;
        case 'settings':
            $page_opts = funcaptcha_get_settings_page_options();
            break;
    }
    
    echo "<div class='wrap'>";
    echo "<h2><img src='" . plugins_url('images/fc_meta_logo.png', PLUGIN_BASENAME) . "' style='max-width:100%;' alt='FunCaptcha'></h2>";
    
    if ($page_opts['flash_message']) {
        echo $page_opts['flash_message'];
    }
        
    $funcaptcha_options = funcaptcha_get_settings();

    include('wp_funcaptcha_admin_register.php');
    echo "</div>";
}

/**
* Show settings screen
*
* @return string echo of HTML
*/
function funcaptcha_display_keys_notice() {
    $path = plugin_basename(__FILE__);
    echo "<div class='error'><p><strong>Thanks for installing FunCaptcha.</strong>You must <a href='plugins.php?page=" . $path . "'>activate your FunCaptcha</a> for it to be displayed.</p></div>";
}

/**
* Checks if public and private keys are missing
*
* @return boolean if keys are missing
*/
function funcaptcha_is_key_missing() {
    if (array_key_exists('funcaptcha', $_POST)) {
        $options = funcaptcha_get_settings_post();
    } else {
        $options = funcaptcha_get_settings();
    }

    if ( $options['public_key'] == '' ||  $options['private_key'] == '' || strlen($options['public_key']) != 36 || strlen($options['private_key']) != 36 ) {
        return true;
    }
}

/**
* save settings
*
* @return null
*/
function funcaptcha_install_plugin() {
    $options = funcaptcha_get_settings_post();
    funcaptcha_set_options($options);   
}

/**
* set new version number
*
* @return null
*/
function funcaptcha_upgrade_plugin() {
    $options = funcaptcha_get_settings();
    $options['version'] = FUNCAPTCHA_VERSION;
    funcaptcha_set_options($options);
}

/**
* checks if should upgrade plugin, or install ne
*
* @return string mode to put the settings screen into
*/
function funcaptcha_check_for_upgrade_or_install() {

    $funcaptcha_options = funcaptcha_get_settings();

    // Check for previous installation
    if (isset($funcaptcha_options['version'])) {
    
        // If there's a previous installation, check for an upgrade
        if (funcaptcha_upto_date($funcaptcha_options['version'])) {
  
            // Version is up to date, move on to settings page
            return 'settings';
        
        } else {
            // Version is not up to date, time to upgrade
            funcaptcha_upgrade_plugin();
            return 'upgrade';
        }
    } else {
    
        return 'install';
    }
}

/**
* stores data into wp database
*
* @return array
*/
function funcaptcha_set_options($options) {
    global $wpmu;
    $options_allowed = array(   'public_key',
                                'private_key',
                                'version',
                                'register_form',
                                'password_form',
                                'comment_form',
                                'bbpress_topic',
                                'bbpress_reply',
                                'login_form',
                                'hide_users',
                                'hide_admins',
                                'security_level',
                                'lightbox_mode',
                                'theme',
                                'error_message',
                                'align');

    $new_options = array();
    foreach($options_allowed as $optal) {
        if(isset($options[$optal])) {
            $new_options[$optal] = $options[$optal];
        }
    }
    
    if ( 1 == $wpmu ) {
        update_site_option( 'funcaptcha_options', $new_options );
    } else {
        update_option( 'funcaptcha_options', $new_options );
    }
    
    return $new_options;
}

/**
* gets data from wp database
*
* @return array settings
*/
function funcaptcha_get_settings() {
    global $wpmu;

    $defaults = array(
        'version' => FUNCAPTCHA_VERSION,
        'public_key' => '',
        'private_key'   => '',
        'register_form' => true,
        'password_form' => true,
        'comment_form' => true,
        'bbpress_topic' => false,
        'bbpress_reply' => false,
        'login_form'    => false,
        'hide_users' => false,
        'hide_admins' => false,
        'security_level' => 0,
        'lightbox_mode' => 1,
        'theme' => 0,
        'error_message' => 'Verification incomplete. Please solve the puzzle before you continue. The puzzle verifies that you are an actual user, not a spammer.',
        'align' => 'left'
    );

    if ( 1 == $wpmu ){
        $funcaptcha_options = get_site_option( 'funcaptcha_options', array() ); // blog network
    } else {
        $funcaptcha_options = get_option( 'funcaptcha_options', array() ); // single site
    }

    return wp_parse_args( $funcaptcha_options, $defaults );
}

/**
* removes data from wp database
*
* @return null
*/
function funcaptcha_delete_options() {
    global $wpmu;
    
    if ( 1 == $wpmu) {
        delete_site_option('funcaptcha_options');
    } else {
        delete_option('funcaptcha_options');
    }
}

function funcaptcha_deactivate() {
       delete_option('funcaptcha_options');
}

/**
* checks version number is less then currently installed
*
* @return boolean
*/
function funcaptcha_upto_date($install_version) {
    return version_compare( $install_version, FUNCAPTCHA_VERSION, '>=' );
}

/**
* get data from settings form
*
* @return array
*/
function funcaptcha_get_settings_post() {

    if (! check_admin_referer( 'fc_nonce', 'fc-nonce' ) ) die("Security check");


    $funcaptcha_post = $_POST['funcaptcha'];
    $options = funcaptcha_get_settings();

    $options_allowed = array(   'public_key',
                                'private_key',
                                'version',
                                'register_form',
                                'password_form',
                                'comment_form',
                                'bbpress_topic',
                                'bbpress_reply',
                                'login_form',
                                'hide_users',
                                'hide_admins',
                                'security_level',
                                'lightbox_mode',
                                'theme',
                                'error_message',
                                'align');

    $new_options = array();
    foreach($options_allowed as $optal) {
        if(isset($funcaptcha_post[$optal])) {
            $new_options[$optal] = $funcaptcha_post[$optal];
        } else {
            $funcaptcha_post[$optal] = $options[$optal];
        }
    }

    $defaults = array(
        'version' => FUNCAPTCHA_VERSION,
        'public_key' => '',
        'private_key'   => '',
        'register_form' => '',
        'password_form' => '',
        'comment_form' => '',
        'bbpress_topic' => '',
        'bbpress_reply' => '',
        'hide_users' => '',
        'hide_admins' => '',
        'security_level' => 0,
        'lightbox_mode' => 1,
        'theme' => 0, 
        'error_message' => 'Verification incomplete. Please solve the puzzle before you continue. The puzzle verifies that you are an actual user, not a spammer.',
        'align' => 'left'
        );

    return wp_parse_args( $funcaptcha_post, $defaults );
}

/**
* resize funcaptcha if mobile screen is small and theme is <300 pixels wide
*
* @return string
*/
function funcaptcha_resize_mobile() {
    $options = funcaptcha_get_settings();
    if ($options['lightbox_mode']) {
        return false;
    }
     $script =   "<script type='text/javascript'>
                    var divSize = document.getElementById('funcaptcha-wrapper').offsetWidth;
                    if (divSize < 310) {
                        var css = '#FunCaptcha iframe {width: 100% !important;zoom: 0.99;-o-transform: scale(0.99);-o-transform-origin: 0 0;-webkit-transform: scale(0.99, 0.98);-moz-transform: scale(0.99, 0.98);transform: scale(0.99, 0.98);-moz-transform-origin:0 0;-webkit-transform-origin:0 0;transform-origin:0 0;z-index:90;}',
                        head = document.getElementsByTagName('head')[0],
                        style = document.createElement('style');
                        style.type = 'text/css';
                        if (style.styleSheet){
                          style.styleSheet.cssText = css;
                        } else {
                          style.appendChild(document.createTextNode(css));
                        }

                        head.appendChild(style);
                    }
                </script>";
    
    // Return the script.
    return $script;
}

function funcaptcha_rearrange_elements($button_id = 'submit') {
    if ($button_id == '') {
        $button_id = 'submit';
    }
    
    $script =   "<script type='text/javascript'>
                    var moved = false;
                    // This ensures the code is executed in the right order
                    if (!moved) {
                        rearrange_form_elements();
                    } else {
                        setTimeout('rearrange_form_elements()', 1000);
                    }
                    function rearrange_form_elements() {
                        var button = document.getElementById('" . $button_id . "');
                        if (button != null) {
                            button.parentNode.removeChild(button);
                            document.getElementById('funcaptcha-wrapper').appendChild(button);
                        }
                    }
                </script>";
    
    // Return the script.
    return $script;
}

/**
* display funcaptcha in comment
*
* @return string
*/
function funcaptcha_comment_form() {

    $options = funcaptcha_get_settings();
          
    // Do not show if the user is logged and it is not enabled for logged in users
    if ((is_user_logged_in() && 1 == $options['hide_users']) || (current_user_can('manage_options') && $options['hide_admins'] == 1)) {
        return;
    }

    $funcaptcha = funcaptcha_API();
    $html = funcaptcha_get_fc_html();
    
    switch ($options['align']) {
        case "left" :
            $style = "text-align: left;";
        break; 
        case "right" :
            $style = "text-align: right;";
        break;
        case "center" :
            $style = "text-align: center;";
        break;
    }

    echo "<div id='funcaptcha-wrapper' style='" . $style . "'>" . $html .  "</div>";
    echo funcaptcha_rearrange_elements("submit");
    echo funcaptcha_resize_mobile();

}

/**
* validates comment funcaptcha
*
* @return array
*/
function funcaptcha_comment_post($comment) {
    $options = funcaptcha_get_settings();
    // Do not show if the user is logged and it is not enabled for logged in users
    if ((is_user_logged_in() && 1 == $options['hide_users']) || (current_user_can('manage_options') && $options['hide_admins'] == 1)) {
        return $comment;
    }

    //Skip for comment replies from the admin menu
    if ( isset( $_POST['action'] ) && $_POST['action'] == 'replyto-comment' &&
                ( check_ajax_referer( 'replyto-comment', '_ajax_nonce', false ) || 
                check_ajax_referer( 'replyto-comment', '_ajax_nonce-replyto-comment', false ) ) ) {
        return $comment;
    }

    // Skip for trackback or pingback
    if ( $comment['comment_type'] != '' && $comment['comment_type'] != 'comment' ) {
        return $comment;
    }
    
    $options = funcaptcha_get_settings();
    $funcaptcha = funcaptcha_API();
    if ( $funcaptcha->checkResult($options['private_key']) ) {
        return $comment;
    } else {
        wp_die(__((htmlentities($options['error_message']) . ' <br/><input type="button" value="Go Back" onclick="history.back(-1)" />'),'funcaptcha'));
    }
}

/**
* display funcaptcha in registration form
*
* @return boolean
*/
function funcaptcha_register_form() {
     
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    
    $funcaptcha = funcaptcha_API();
    $html = funcaptcha_get_fc_html();
    switch ($options['align']) {
        case "left" :
            $style = "text-align: left;";
        break; 
        case "right" :
            $style = "text-align: right;";
        break;
        case "center" :
            $style = "text-align: center;";
        break;
    }

    echo "<div id='funcaptcha-wrapper' style='" . $style . "'>" . $html .  "</div>";
    echo funcaptcha_resize_mobile();
    return true;
}

/**
* display funcaptcha in registration form for multi-sites
*
* @return boolean
*/
function funcaptcha_register_form_wpmu($errors) {
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    $error = $errors->get_error_message('funcaptcha_incorrect');
    $html = funcaptcha_get_fc_html();
    switch ($options['align']) {
        case "left" :
            $style = "text-align: left;";
        break; 
        case "right" :
            $style = "text-align: right;";
        break;
        case "center" :
            $style = "text-align: center;";
        break;
    }
    if ($error) {
        echo '<p class="error">' . $error . '</p>';
    }
    echo "<div id='funcaptcha-wrapper' style='" . $style . "'>" . $html .  "</div>";
    echo funcaptcha_resize_mobile();
    return true;
}



/**
* display funcaptcha in registration form
*
* @return boolean
*/
function funcaptcha_register_form_bp() {
     
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    
    $funcaptcha = funcaptcha_API();
    $html = funcaptcha_get_fc_html();
    switch ($options['align']) {
        case "left" :
            $style = "text-align: left;";
        break; 
        case "right" :
            $style = "text-align: right;";
        break;
        case "center" :
            $style = "text-align: center;";
        break;
    }
    echo "<div id='funcaptcha-wrapper' style='" . $style . "'>" . $html .  "</div>";
    echo funcaptcha_resize_mobile();
    return true;
}


/**
 * Shows and generates captcha for bbPress
*/
function funcaptcha_bbpress_form()
{
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();

    // Do not show if the user is logged and it is not enabled for logged in users
    if ((current_user_can('manage_options') && $options['hide_admins'] == 1)) {
        return;
    }
    
    $funcaptcha = funcaptcha_API();
    $html = funcaptcha_get_fc_html();
    switch ($options['align']) {
        case "left" :
            $style = "text-align: left;";
        break; 
        case "right" :
            $style = "text-align: right;";
        break;
        case "center" :
            $style = "text-align: center;";
        break;
    }
    echo "<div id='funcaptcha-wrapper' style='" . $style . "'>" . $html .  "</div>";
    echo funcaptcha_resize_mobile();
}


/**
 * Validates bbpress topics and replies
*/
function funcaptcha_bbpress_validate()
{
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();

    // Do not show if the user is logged and it is not enabled for logged in users
    if ((current_user_can('manage_options') && $options['hide_admins'] == 1)) {
        return;
    }

    if ( $funcaptcha->checkResult($options['private_key']) ) {
        return;
    } else {
        bbp_add_error('funcaptcha-wrong', htmlentities($options['error_message']));
    }
}




/**
* validates registration funcaptcha for multi-site setup
*
* @return array
*/
function funcaptcha_register_post_wpmu($results) {
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();

    // Do not show if the user is logged and it is not enabled for logged in users
    if ((current_user_can('manage_options') && $options['hide_admins'] == 1)) {
        return $results;
    }
    
    if ( $funcaptcha->checkResult($options['private_key']) ) {
        return( $results );
    } else {
        $results['errors']->add('funcaptcha_incorrect', '<strong>'.__('ERROR', 'funcaptcha').'</strong>: '.__(htmlentities($options['error_message']), 'funcaptcha'));
    }
    return( $results );
}

/**
* validates registration funcaptcha
*
* @return array
*/
function funcaptcha_register_post($login, $email, $errors) {
   
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    
    if ( $funcaptcha->checkResult($options['private_key']) ) {
        return;
    } else {
        $errors->add('funcaptcha_incorrect', '<strong>'.__('ERROR', 'funcaptcha').'</strong>: '.__(htmlentities($options['error_message']), 'funcaptcha'));
    }
    return $errors;
}

// Check the response to the puzzle
function funcaptcha_register_post_bp($result) {
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();

     if ( $funcaptcha->checkResult($options['private_key']) ) {
     } else {
         wp_die(__((htmlentities($options['error_message']) . ' <br/><input type="button" value="Go Back" onclick="history.back(-1)" />'),'funcaptcha'));
    }
    return $result;
}

/**
* display funcaptcha in lost password form
*
* @return null
*/
function funcaptcha_lost_password_form() {
    funcaptcha_register_form();
}

/**
* display funcaptcha in login form
*
* @return null
*/
function funcaptcha_login_form() {
    //increase standard WP login form width to fit FC.
    echo "<style>#loginform {width: 310px;}</style>";
    funcaptcha_register_form();
}

/**
* validates login funcaptcha
*
* @return null
*/
function funcaptcha_login_post($user) {
    if ($_POST) {
        $funcaptcha = funcaptcha_API();
        $options = funcaptcha_get_settings();
        
        if ( $funcaptcha->checkResult($options['private_key']) ) {
            return;
        } else {
            if (!is_wp_error($user)) {
                    $user = new WP_Error();
                }
                $user->add('captcha_fail', __($options['error_message']), 'funcaptcha');
                remove_action('authenticate', 'wp_authenticate_username_password', 20);
                return $user;
        }
    }
}

/**
* validates lost password funcaptcha
*
* @return null
*/
function funcaptcha_lost_password_post() {
  
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    
    if ( $funcaptcha->checkResult($options['private_key']) ) {
        return;
    } else {
        wp_die(__((htmlentities($options['error_message']) . ' <br/><input type="button" value="Go Back" onclick="history.back(-1)" />'),'funcaptcha'));
    }
}

/**
* setup settings page
*
* @return array
*/
function funcaptcha_get_settings_page_options() {

    $page_opts = array();
    $page_opts['title'] = 'FunCaptcha Settings';
    $page_opts['button'] = 'Update Settings';
    $page_opts['flash_message'] = null;
    
    // If the form was posted
    if (isset($_POST['funcaptcha'])) {
        $page_opts['flash_message'] = "Changes saved.";
    }
    
    return $page_opts;
}

/**
* setup upgrade page
*
* @return array
*/
function funcaptcha_get_upgrade_page_options(&$action) {
    
    $page_opts = array();
    $page_opts['title'] = 'Upgrade';
    $page_opts['button'] = 'Complete Upgrade';
    $page_opts['flash_message'] = "Please take a moment to make sure the following is correct and complete your upgrade.";
    
    // If the form was posted
    if (isset($_POST['funcaptcha'])) {
        $action = 'settings';
        $page_opts = funcaptcha_get_settings_page_options();
        $page_opts['flash_message'] = "Your plugin has been successfully upgraded.";
    }
    
    return $page_opts;
}

/**
* setup install page
*
* @return array
*/
function funcaptcha_get_install_page_options(&$action) {

    $page_opts = array();
    $page_opts['title'] =  "Installation";
    $page_opts['button'] = "Complete Installation";
    $page_opts['flash_message'] = "<p>To complete your installation:</p><ol><li><a href='http://www.funcaptcha.co/register'>Register for an account</a> on FunCaptcha.</li><li>Copy the public and private keys into the fields below.</li></ol>";
    
    if (isset($_POST['funcaptcha'])) {
        $action = 'settings';
        $page_opts = funcaptcha_get_settings_page_options();
        $page_opts['flash_message'] = "<div class='alert valid'>The plugin has been successfully installed.</div>";
    }
    
    return $page_opts;
}

/**
* Checks if jetpack plugin is installed, if so, warn user.
*
*/
function check_for_jetpack() {
    if (is_plugin_active('jetpack/jetpack.php')) {
        if (Jetpack::is_active()) {
            $jp_active = Jetpack::get_active_modules();
            if (in_array('comments', $jp_active)) {
                ?>
                    <p style="color:red">Warning: The plugin JetPack Comments has been detected. This will prevent FunCaptcha from working. You can disable JetPack Comments from the JetPack configuration page.<p>
                <?php 
            }
        }
    }
}


/**
* get FunCaptcha HTML
*
*/
function funcaptcha_get_fc_html() {
    $funcaptcha = funcaptcha_API();
    $options = funcaptcha_get_settings();
    $fc_arr = $funcaptcha->getFunCaptcha($options['public_key']);
    return $fc_arr["html"];
}


