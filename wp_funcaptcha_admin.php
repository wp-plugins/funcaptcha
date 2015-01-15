<?
	//default text to display if nothing is entered.
	$funcaptcha_Default = "Verification incomplete. Please solve the puzzle before you continue. The puzzle verifies that you are an actual user, not a spammer.";
?>
<style type="text/css">.nav-btn{background-color:white;cursor:hand;cursor:pointer}.nav-btn:hover{color:#d54e21}</style>
<h2 class="nav-tab-wrapper">
	<form class="form-css" action='<?php echo(htmlentities($_SERVER['PHP_SELF']).'?'.htmlentities($_SERVER['QUERY_STRING'])); ?>' method='POST' id='funcaptcha-page'>
		<input type='submit' class="nav-btn nav-tab <?php echo ( $_POST['funcaptcha-page'] == 'Settings' ) ? 'nav-tab-active' : ''; ?>" name='funcaptcha-page' value='Settings' />
		<input type='submit' class="nav-btn nav-tab <?php echo ( $_POST['funcaptcha-page'] == 'Activate' ) ? 'nav-tab-active' : ''; ?>" name='funcaptcha-page' value='Activate' />
	</form>
</h2>
<div class='form-css'>
	<div class='funcaptcha-box'>
		<div class='inside'>
			<h2 class="settings-title">Settings</h2>
			<form class="form-css" action='<?php echo(htmlentities($_SERVER['PHP_SELF']).'?'.htmlentities($_SERVER['QUERY_STRING'])); ?>' method='POST' id='funcaptcha-settings'>
				<?php if (funcaptcha_is_key_missing()) { ?>
					<p style="color:red">To activate this plugin, you need to supply access keys. You can do that on the <a href='<?php echo "plugins.php?page=" . PLUGIN_BASENAME?>'>activate tab</a>.</red><p>
				<?php }
					check_for_jetpack();
				?>
				<fieldset>
					<label>FunCaptcha theme:</label>
					<p>This will change the appearance of FunCaptcha (see <a href="http://www.funcaptcha.co/themes/" target="_blank">here</a> for what they look like):</p>
					<select name="funcaptcha[theme]" value="theme">
						<option value="0" <?php if ($funcaptcha_options['theme'] == 0) { echo 'selected="selected"'; } else { ''; };?>>Standard</option>
						<option value="1" <?php if ($funcaptcha_options['theme'] == 1) { echo 'selected="selected"'; } else { ''; };?>>Slate Blue</option>
						<option value="3" <?php if ($funcaptcha_options['theme'] == 3) { echo 'selected="selected"'; } else { ''; };?>>White</option>
						<option value="4" <?php if ($funcaptcha_options['theme'] == 4) { echo 'selected="selected"'; } else { ''; };?>>Black</option>
					</select>
				</fieldset>
				<p>Select where you'd like the FunCaptcha to appear.</p>
				<fieldset>
					<label>Show FunCaptcha on:</label>
					<input type="hidden" name="funcaptcha[register_form]" value="0" />
					<p><input type='checkbox' name='funcaptcha[register_form]' value='1' <?php echo ( $funcaptcha_options['register_form'] ) ? 'checked' : ''; ?> /> Registration</p>
					<input type="hidden" name="funcaptcha[password_form]" value="0" />
					<p><input type='checkbox' name='funcaptcha[password_form]' value='1' <?php echo ( $funcaptcha_options['password_form'] ) ? 'checked' : ''; ?> /> Lost Password</p>
					<input type="hidden" name="funcaptcha[comment_form]" value="0" />
					<p><input type='checkbox' name='funcaptcha[comment_form]' value='1' <?php echo ( $funcaptcha_options['comment_form'] ) ? 'checked' : ''; ?> /> Comments</p>
					<input type="hidden" name="funcaptcha[login_form]" value="0" />
					<p><input type='checkbox' name='funcaptcha[login_form]' value='1' <?php echo ( $funcaptcha_options['login_form'] ) ? 'checked' : ''; ?> /> Login</p>
					<?php if (BBPRESS_INSTALLED) { ?>
						<input type="hidden" name="funcaptcha[bbpress_topic]" value="0" />
						<p><input type='checkbox' name='funcaptcha[bbpress_topic]' value='1' <?php echo ( $funcaptcha_options['bbpress_topic'] ) ? 'checked' : ''; ?> /> bbPress Topics</p>
						<input type="hidden" name="funcaptcha[bbpress_reply]" value="0" />
						<p><input type='checkbox' name='funcaptcha[bbpress_reply]' value='1' <?php echo ( $funcaptcha_options['bbpress_reply'] ) ? 'checked' : ''; ?> /> bbPress Replies</p>
					<?php } ?>
				</fieldset>
				<fieldset>
					<label>Hide from logged in users?</label>
					<input type="hidden" name="funcaptcha[hide_users]" value="0" />
					<p><input type='checkbox' name='funcaptcha[hide_users]' value='1' <?php echo ( $funcaptcha_options['hide_users'] ) ? 'checked' : ''; ?> /> Yes</p>
				</fieldset>
				<fieldset>
					<label>Hide from admins?</label>
					<input type="hidden" name="funcaptcha[hide_admins]" value="0" />
					<p><input type='checkbox' name='funcaptcha[hide_admins]' value='1' <?php echo ( $funcaptcha_options['hide_admins'] ) ? 'checked' : ''; ?> /> Yes</p>
				</fieldset>
				<?php if ($funcaptcha_options['lightbox_mode'] == 1) { ?>
					<fieldset>
						<label>Popup mode:</label>
						<p><span style="color:red;">We recommend you disable this mode as it is being depreciated.</span> Popup mode will show FunCaptcha once the user submits your form, rather than on the page. Inline mode will show FunCaptcha on your page as the user completes your form. For more information, see our <a href="http://www.funcaptcha.co/faqs/" target="_blank">FAQ</a>.</p>
						<select name="funcaptcha[lightbox_mode]" value="lightbox_mode">
							<option value="1" <?php if ($funcaptcha_options['lightbox_mode'] == 1) { echo 'selected="selected"'; } else { ''; };?>>Popup</option>
							<option value="0" <?php if ($funcaptcha_options['lightbox_mode'] == 0) { echo 'selected="selected"'; } else { ''; };?>>Inline</option>
						</select>
					</fieldset>
				<?php } ?>
				<fieldset>
					<label>Security level:</label>
					<p>If you choose Automatic, security starts at the lowest level, and rises and falls automatically, adjusted by FunCaptcha's monitoring system. The Enhanced level has more challenges to solve, but is very hard for spammer programs to get past. For more information, see our <a href="http://www.funcaptcha.co/faqs/" target="_blank">FAQ</a>.</p>
					<select name="funcaptcha[security_level]" value="security_level">
						<option value="0" <?php if ($funcaptcha_options['security_level'] == 0) { echo 'selected="selected"'; } else { ''; };?>>Automatic</option>
						<option value="20" <?php if ($funcaptcha_options['security_level'] == 20) { echo 'selected="selected"'; } else { ''; };?>>Always Enhanced</option>
					</select>
				</fieldset>
				<fieldset>
					<label>Error message:</label>
					<p>This message appears if your users do not complete the FunCaptcha correctly.</p>
					<textarea rows="4" cols="50" name='funcaptcha[error_message]' /><?php echo $funcaptcha_options['error_message'] ? htmlentities($funcaptcha_options['error_message']) : $funcaptcha_Default; ?></textarea>
				</fieldset>
				<fieldset>
					<label>FunCaptcha alignment:</label>
					<p>You can change the alignment of FunCaptcha on your page, this applies for inline versions of FunCaptcha.</p>
					<select name="funcaptcha[align]" value="align">
						<option value="left" <?php if ($funcaptcha_options['align'] == 'left') { echo 'selected="selected"'; } else { ''; };?>>Left</option>
						<option value="right" <?php if ($funcaptcha_options['align'] == 'right') { echo 'selected="selected"'; } else { ''; };?>>Right</option>
						<option value="center" <?php if ($funcaptcha_options['align'] == 'center') { echo 'selected="selected"'; } else { ''; };?>>Center</option>
					</select>
				</fieldset>		
				<fieldset>
					<input type='hidden' name='funcaptcha[action]' value='<?php echo $action; ?>' />
					<input type='hidden' name='funcaptcha[type]' value='Settings' />
					<?php wp_nonce_field( 'fc_nonce','fc-nonce' ); ?>
					<button type='submit' class='button-primary'>Save FunCaptcha settings</button>
				</fieldset>
			</form>
		</div>
	</div>	
	<div class='funcaptcha-box'>
		<div class='inside'>
			<h2 class="settings-title">Support</h2>
			<p>If you're having trouble getting FunCaptcha to work, please <a href="http://www.funcaptcha.co/contact-us" target="_blank">contact us</a> and we'll get back to you.</p>
		</div>
	</div>
</div>

 <p class="copyright">&copy; Copyright <?php echo date("Y"); ?> <a href="http://www.funcaptcha.co/">SwipeAds</a>. Version <?php echo FUNCAPTCHA_VERSION ?> </p>
