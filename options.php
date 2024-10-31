<div class="wrap">
	<h2>Net-Results Marketing Automation: Plugin Settings</h2>
	<form method="post" id="save" action="options.php" id="nr_client_info">
		<?php wp_nonce_field('update-options'); ?>
		<?php settings_fields('netresults'); ?>

		<?php if (get_option('nr_client_id') == '') : ?>
			<div id="nr_info" class="nr_info" style="display:<?php echo $style; ?>">
				<h4 style="margin: 0 0 7px 0;">You must authorize this WordPress Plugin to access your account
					information at Net-Results.com to sync your forms and visitor tracking data. You must already have registered a Client Id and a Client Password with us to put below. If you have not registered a client, please
					<a href="https://apps.net-results.com/app/Oauth/index" target="_blank"> Click Here </a> to register a client.

			</div>
		<?php endif; ?>
		<?php if (isset($_REQUEST['error'])) : ?>
			<div id="nr_info" class="nr_info">
				<h4 style="margin: 0 0 7px 0;"> ERROR: The Client Id or Client Secret you provided are invalid. Please try again</h4>
			</div>
		<?php endif; ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Net-Results Product ID:</th>
				<td><input type="text" id="nr_ps_id" name="nr_ps_id" value="<?php echo get_option('nr_ps_id'); ?>" /> <span style="color: #888888;">(find your Product ID via <a href="https://apps.net-results.com/data/public/User/account" target="_blank">My Account</a> in Net-Results. Look for "data-pid" in your implementation code)</span></td>
			</tr>
			<tr valign="top">
				<th scope="row">Net-Results Client ID:</th>
				<td><input type="text" id="nr_client_id" name="nr_client_id" value="<?php echo get_option('nr_client_id'); ?>" /> <span style="color: #888888;">(create your Client ID & Client Secret in your <a href="https://apps.net-results.com/app/Oauth/edit" target="_blank">Net-Results account</a>)</span></td>
			</tr>
			<tr valign="top">
				<th scope="row">Net-Results Client Secret:</th>
				<td><input type="text" id="nr_client_secret" name="nr_client_secret" value="<?php echo get_option('nr_client_secret'); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">Net-Results Access Token:</th>
				<td><input type="text" id="nr_access_token" name="nr_access_token" value="<?php echo get_option('nr_access_token'); ?>" /><input id="get_access_token" type="button" class="button-primary" style="margin-left: 10px;" value="<?php _e('Get Access Token') ?>" /></td>
			</tr>
			<input type="hidden" name="action" value="update" />
			<tr valign="top">
				<th scope="row"></th>
				<td><button type="submit" class="button-primary"><?php _e('Save Settings') ?></button></td>
			</tr>

		</table>
	</form>

	<style>
		.nr_info {
			padding: 20px;
			line-height: 22px;
			border: 1px solid #ddd;
			background-color: #fff;
			border-radius: 14px;
		}
	</style>
	<div class="nr_info">
		<h4 style="margin: 0 0 7px 0;">Net-Results is a next-generation marketing automation platform that makes it easy to leverage relevance to drive connections, conversions and revenue.</h4>
		Need access? <a href="http://www.net-results.com/app/marketing-automation-trial.php" target="_blank">Get a Trial Account</a>
		<br />
		Email us for over the top support: <a href="mailto:support@net-results.com">support@Net-Results.com</a>
		<br />
		Call us anytime: +1 303 771-2552
		<br />
		Learn more at <a href="http://www.net-results.com" target="_blank">www.Net-Results.com</a>
	</div>
</div>
<script type="text/javascript">
	$ = jQuery;

	function setAccessToken(strToken) {
		document.getElementById('nr_access_token').value = strToken;
	}

	$('#save').on('submit', function(evt) {
		/*console.debug('j evt', evt);*/
		var psId = $('#nr_ps_id'),
			clientId = $('#nr_client_id'),
			clientSecret = $('#nr_client_secret'),
			accessToken = $('#nr_access_token');
		if (psId.val() == '') {
			alert('Please enter a Product ID.');
			clientId.focus();
			evt.preventDefault();
			evt.stopImmediatePropagation();
			evt.stopPropagation();
			return false;
		}
		if (clientId.val() == '') {
			alert('Please enter a Client ID.');
			clientId.focus();
			evt.preventDefault();
			evt.stopImmediatePropagation();
			evt.stopPropagation();
			return false;
		}
		if (clientSecret.val() == '') {
			alert('Please enter a Client Secret.');
			clientSecret.focus();
			evt.preventDefault();
			evt.stopImmediatePropagation();
			evt.stopPropagation();
			return false;
		}
		if (accessToken.val() == '') {
			alert('Please enter an Access Token by Clicking "Get Access Token".');
			evt.preventDefault();
			evt.stopImmediatePropagation();
			evt.stopPropagation();
			return false;
		}
	});

	$('#get_access_token').on('click', function(evt) {
		if ($('#nr_client_id').val() == '') {
			alert('Please enter a Client ID.');
			$('#nr_client_id').focus();
			evt.preventDefault();
			evt.stopImmediatePropagation();
			evt.stopPropagation();
			return false;
		}
		if ($('#nr_client_secret').val() == '') {
			alert('Please enter a Client Secret.');
			$('#nr_client_secret').focus();
			evt.preventDefault();
			evt.stopImmediatePropagation();
			evt.stopPropagation();
			return false;
		}
		window.open('https://apps.net-results.com/app/Oauth/authorize?client_id=' + $('#nr_client_id').val() + '&response_type=token&redirect_uri=' + '<?php echo WP_PLUGIN_URL; ?>' +'/net-results-marketing-automation/authorization.php')
	});

</script>
