<?
	//default text to display if nothing is entered.
	$funcaptcha_Default = "Verification incomplete. Please solve the puzzle before you continue. The puzzle verifies that you are an actual user, not a spammer.";
?>
<div class='form-css'>
	<div class='funcaptcha-box'>
		<div class='inside'>
			<h2 class="settings-title">Settings</h2>
			<form class="form-css" action='<?=($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']); ?>' method='POST' id='funcaptcha-settings'>
				<?php if (funcaptcha_is_key_missing()) { ?>
					<p style="color:red">To enable this plugin, you need to supply access keys.</red> Create your account at <a href="https://swipeads.co/register/" target='_blank'>SwipeAds</a>, then paste your public and private keys into the spaces below</p>				<fieldset>
					<label>Public Key:</label>
					<input type='text' name='funcaptcha[public_key]' value='<?= htmlentities($funcaptcha_options['public_key']); ?>'/>
				</fieldset>
				<fieldset>
					<label>Private Key:</label>
					<input type='text' name='funcaptcha[private_key]' value='<?= htmlentities($funcaptcha_options['private_key']); ?>'/>
				</fieldset>
				<?php } ?>
				<p>Select where you'd like the FunCaptcha to appear.</p>
				<fieldset>
					<label>Show FunCaptcha on:</label>
					<p><input type='checkbox' name='funcaptcha[register_form]' value='1' <?= ( $funcaptcha_options['register_form'] ) ? 'checked' : ''; ?> /> Registration</p>
					<p><input type='checkbox' name='funcaptcha[password_form]' value='1' <?= ( $funcaptcha_options['password_form'] ) ? 'checked' : ''; ?> /> Lost Password</p>
					<p><input type='checkbox' name='funcaptcha[comment_form]' value='1' <?= ( $funcaptcha_options['comment_form'] ) ? 'checked' : ''; ?> /> Comments</p>
				</fieldset>
				<fieldset>
					<label>Hide from logged in users?</label>
					<p><input type='checkbox' name='funcaptcha[hide_users]' value='1' <?= ( $funcaptcha_options['hide_users'] ) ? 'checked' : ''; ?> /> Yes</p>
				</fieldset>
				<fieldset>
					<label>Error message:</label>
					<p>This message appears if your users do not complete the FunCaptcha correctly.</p>
					<textarea rows="4" cols="50" name='funcaptcha[error_message]' /><?= $funcaptcha_options['error_message'] ? htmlentities($funcaptcha_options['error_message']) : $funcaptcha_Default; ?></textarea>
				</fieldset>
				<? if (CF7_INSTALLED) { ?>
				<fieldset>
					<label>Contact Form 7 Support:</label>
					<p>This will enable FunCaptcha support for Contact Form 7. (Please ensure you don't display a Contact Form 7 containing a FunCaptcha on a page which already has FunCaptcha, such as a registration or comments page. See our <a href="https://swipeads.co/faqs" target="_blank">website</a> for details.)</p>
					<p><input type='checkbox' name='funcaptcha[cf7_support]' value='1' <?= ( $funcaptcha_options['cf7_support'] ) ? 'checked' : ''; ?> /> Enable</p>
				</fieldset>
				<? } ?>	
				<?php if (!funcaptcha_is_key_missing()) { ?>
				<p>You registered the following keys at <a href='https://swipeads.co/login' target='_blank'>SwipeAds</a>. If there are no problems, you don't need to change these.</p>
				<fieldset>
					<label>Public Key:</label>
					<input type='text' name='funcaptcha[public_key]' value='<?= htmlentities($funcaptcha_options['public_key']); ?>'/>
				</fieldset>
				<fieldset>
					<label>Private Key:</label>
					<input type='text' name='funcaptcha[private_key]' value='<?= htmlentities($funcaptcha_options['private_key']); ?>'/>
				</fieldset>
				<?php } ?>			
				<fieldset>
					<input type='hidden' name='funcaptcha[action]' value='<?= $action; ?>' />
					<button type='submit' class='button-primary'>Save FunCaptcha settings</button>
				</fieldset>
			</form>
		</div>
	</div>	
	<div class='funcaptcha-box'>
		<div class='inside'>
			<h2 class="settings-title">Support</h2>
			<p>If you're having trouble getting FunCaptcha to work, send us an <a href="mailto:support@swipeads.co">email</a> and we'll get back to you.</p>
		</div>
	</div>
</div>

 <p class="copyright">&copy; Copyright <?= date("Y"); ?> <a href="https://swipeads.co/">SwipeAds</a>. Version <?= FUNCAPTCHA_VERSION ?> </p>
