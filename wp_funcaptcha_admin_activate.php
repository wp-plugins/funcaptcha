<?
	//default text to display if nothing is entered.
	$funcaptcha_Default = "Verification incomplete. Please solve the puzzle before you continue. The puzzle verifies that you are an actual user, not a spammer.";
?>
<style type="text/css">.nav-btn{background-color:white;cursor:hand;cursor:pointer}.nav-btn:hover{color:#d54e21}</style>
<script type="text/javascript">
function openpopup(popurl){
	if (!window.location.origin) window.location.origin = window.location.protocol+"//"+window.location.host;
	//window.location.origin;
	var userURL = encodeURIComponent(window.location.origin)
	if (userURL.indexOf("localhost")) {
		userURL = "";
	}
	var url = "https://www.funcaptcha.co/wp-fc-register/?url=" + userURL;
	var winpops=window.open(url,"","width=400,height=450")
}
</script>
<h2 class="nav-tab-wrapper">
	<form class="form-css" action='<?php echo(htmlentities($_SERVER['PHP_SELF']).'?'.htmlentities($_SERVER['QUERY_STRING'])); ?>' method='POST' id='funcaptcha-page'>
		<input type='submit' class="nav-btn nav-tab <?php echo ( $_POST['funcaptcha-page'] == 'Settings' ) ? 'nav-tab-active' : ''; ?>" name='funcaptcha-page' value='Settings' />
		<input type='submit' class="nav-btn nav-tab <?php echo ( $_POST['funcaptcha-page'] == 'Activate' ) ? 'nav-tab-active' : ''; ?>" name='funcaptcha-page' value='Activate' />
	</form>
</h2>
<div class='form-css'>
	<div class='funcaptcha-box'>
		<div class='inside'>
			<h2 class="settings-title">Activate</h2>
			<form class="form-css" action='<?php echo(htmlentities($_SERVER['PHP_SELF']).'?'.htmlentities($_SERVER['QUERY_STRING'])); ?>' method='POST' id='funcaptcha-settings'>
				<?php if (funcaptcha_is_key_missing()) { ?>
					<p style="color:red">To enable this plugin, you need to supply access keys. <a href="http://www.funcaptcha.co/register" target="_blank">Create your account at our website</a>. Note: If you have already set up FunCaptcha on another WordPress site, you already have an account, and can easily add this site to that account. <a href="https://funcaptcha.co/faqs/#multWordpress" target='_blank'>Learn how to add more sites.</a></p>
					<a href="javascript:openpopup()"><button type='button' class='button-primary'>Register at FunCaptcha.co</button></a>
				<?php } else { ?>
					<p>You have already activated this plugin, unless it doesn't work, you do not need to change these keys again. You can login at <a href="http://www.funcaptcha.co/" target='_blank'>FunCaptcha</a> to add new sites or update your settings.</p>
				<?php }
					check_for_jetpack();
				?>
				<fieldset>
					<?php
						if (strlen($funcaptcha_options['public_key']) != 36 && strlen($funcaptcha_options['public_key']) > 0) {
							?>
								<p style="color:red; font-weight:bold;">This key isn't the correct length. Make sure you have correctly copied it from your account at <a href="http://www.funcaptcha.co" target="_blank">FunCaptcha.co</a>.</p>
							<?php
						}
					?>
					<label>Public Key:</label>
					<input type='text' name='funcaptcha[public_key]' placeholder="Register at funcaptcha.co to get your key" value='<?php echo htmlentities($funcaptcha_options['public_key']); ?>'/>
				</fieldset>
				<fieldset>
					<?php
						if (strlen($funcaptcha_options['private_key']) != 36 && strlen($funcaptcha_options['public_key']) > 0) {
							?>
								<p style="color:red; font-weight:bold;">This key isn't the correct length. Make sure you have correctly copied it from your account at <a href="http://www.funcaptcha.co" target="_blank">FunCaptcha.co</a>.</p>
							<?php
						}
					?>
					<label>Private Key:</label>
					<input type='text' name='funcaptcha[private_key]' placeholder="Register at funcaptcha.co to get your key" value='<?php echo htmlentities($funcaptcha_options['private_key']); ?>'/>
				</fieldset>
				<fieldset>
					<input type='hidden' name='funcaptcha[action]' value='<?php echo $action; ?>' />
					<input type='hidden' name='funcaptcha[type]' value='Activate' />
					<?php wp_nonce_field( 'fc_nonce','fc-nonce' ); ?>
					<button type='submit' class='button-primary'>Save my keys</button>
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