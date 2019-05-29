<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><h1 class="wp-heading-inline"><?php _e('Settings',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></h1>
<hr class="wp-header-end">
<div class="wrap">
	<form method="POST">
		<table class="form-table">
			<tr class="form-field">
				<th><?php _e('App ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></th>
				<td>
					<input type="text" name="pinterest_wcrp_app_id" value="" placeholder="<?php _e('App ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" title="<?php _e('App ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" />
				</td>
			</tr>
			<tr class="form-field">
				<th><?php _e('App Secret ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></th>
				<td>
					<input type="text" name="pinterest_wcrp_app_secret_id" value="" placeholder="<?php _e('App Secret ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" title="<?php _e('App Secret ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" />
				</td>
			</tr>
			<tr class="form-field">
				<td colspan="2">
					<input type="submit" name="pinterest_wcrp_submit" value="<?php _e('Save',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" />
				</td>
			</tr>
		</table>
	</form>
</div>