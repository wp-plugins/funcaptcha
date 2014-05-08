<script type="text/javascript">
//JS for Gravity Forms.

if (fieldSettings.FunCaptcha === undefined)
    fieldSettings["FunCaptcha"] = ".label_setting, .css_class_setting";

function funcaptcha_gf_add_field(name) {
    var nextId = GetNextFieldId();
    var field = new Field(nextId, name);
    <?php 
        $options = funcaptcha_get_settings();
        if ($options['lightbox_mode']) {
            ?>
                field.label = "<?php _e('', 'gravityforms'); ?>";
            <?php
        } else {
            ?>
                field.label = "<?php _e('Verification', 'gravityforms'); ?>";
            <?php
        }
    ?>
    

    var mysack = new sack("<?php echo admin_url('admin-ajax.php')?>?id=" + form.id);
    mysack.execute = 1;
    mysack.method = 'POST';
    mysack.setVar( "action", "rg_add_field" );
    mysack.setVar( "rg_add_field", "<?php echo wp_create_nonce('rg_add_field') ?>" );
    mysack.setVar( "field", jQuery.toJSON(field) );
    mysack.encVar( "cookie", document.cookie, false );
    mysack.onError = function() { alert('<?php echo esc_js(__("Ajax error while adding field", "gravityforms")) ?>' )};
    mysack.runAJAX();

    return true;
}
</script>