<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!isset($board_records) || empty($board_records) || !is_array($board_records)){
	$board_records = array();
}

?><div class="wrap">
	<h1 class="wp-heading-inline"><?php _e('API Settings',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></h1>
	<hr class="wp-header-end">
	<!-- Wrapper for the error class --><?php
	if(!empty($error_message)){
		?><div class="error-wrap"><?php
			echo $error_message;
		?></div><?php
	}
	?><!-- Wrapper for the API details -->
	<form method="POST">
		<table class="form-table">
			<tr class="form-field">
				<th><label><?php _e('App ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="pinterest_wcrp_app_id" value="<?php echo $appId; ?>" placeholder="<?php _e('App ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" title="<?php _e('App ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" />
				</td>
			</tr>
			<tr class="form-field">
				<th><label><?php _e('App Secret ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="pinterest_wcrp_app_secret_id" value="<?php echo $appSecKey; ?>" placeholder="<?php _e('App Secret ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" title="<?php _e('App Secret ID',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" />
				</td>
			</tr>
		</table>
		<p>
			<input type="submit" class="button button-primary" name="pinterest_wcrp_submit" value="Save" />
			<span class="description danger-text"><?php _e('Everytime you save the API details, you will need verify the APP.',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></span>
		</p><?php
		if(!$isValid){
			?><p class="danger-text"><strong><?php _e('It seems that your App token is expired. Please save API details once to generate new token.',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></strong></p><?php
		}
	?></form><?php
	if($isValid){
		?><div class="pd10-T">
			<h2 class="wp-heading-inline"><?php _e('General Settings',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></h2>
			<hr class="wp-header-end">
			<div class="wrap">
				<form method="POST">
					<table class="form-table">
						<tr class="form-field">
							<th><label><?php _e('Manage Log',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label></th>
							<td>
								<select name="pinterest_wcrp_manage_log" title="Manage Log">
									<option value="yes" <?php if($manage_log == "yes"){ echo 'selected="selected"'; } ?> ><?php _e('Yes',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
									<option value="no" <?php if($manage_log == "no"){ echo 'selected="selected"'; } ?> ><?php _e('No',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
								</select>
							</td>
						</tr>
						<tr class="form-field">
							<th><label><?php _e('Gap between 2 requests (Minutes)',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label></th>
							<td>
								<input type="number" min="1" max="3" name="pinterest_wcrp_time_gap" value="<?php echo $time_gap; ?>" placeholder="<?php _e('Gap between 2 requests',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" title="<?php _e('Time between 2 requests',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" />
								<p class="description"><?php _e('It must be in between 1-3',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></p>
							</td>
						</tr>
						<!-- Meta tags selection -->
						<tr class="form-field">
							<th><label><?php _e('Meta Tags',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label></th>
							<td>
								<select name="pinterest_wcrp_manage_metatags" title="Meta Tags">
									<option value="seo_tags" <?php if($manage_metatags == "seo_tags"){ echo 'selected="selected"'; } ?> ><?php _e('Use SEO Tags',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
									<option value="custom_tags"> <?php if($manage_metatags == "custom_tags"){ echo 'selected="selected"'; } ?> <?php _e('Use Custom Tags',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
								</select>
							</td>
						</tr>
						<!-- Boards display here from the API -->
						<tr class="form-field">
							<th><label><?php _e('Select Board',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></label></th>
							<td>
								<select name="pinterest_wcrp_manage_board" title="Boards">
									<option value=''><?php _e('Select Board',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></option>
									<?php if(!empty($board_records)) {
											foreach ($board_records as $boards) {
												if(empty($boards['id'])){
													continue;
												}
												?>
												<option value="<?php echo $boards['id']; ?>"
														<?php if($manage_boards == $boards['id']){ echo 'selected="selected"'; } ?> >
														<?php echo $boards['name']; ?>
												</option>

										<?php	} // End of foreach loop
											} //End of if statement
										?>
								</select>

								<!-- Note if no relevant boards found for the account -->
								<?php if(empty($board_records)) { ?>
									<p class="description"><?php _e('Kindly log in to your account add boards and refresh the page.',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></p>
								<?php } ?>
							</td>
						</tr>
					</table>
					<p>
						<input type="submit" class="button button-primary" name="pinterest_wcrp_general_setting_submit" value="<?php _e('Save',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>" />
					</p>
				</form>
			</div>
		</div><?php
	}
	?><div class="pinterest_rich_pin_notice notice-outer">
		<p class="note"><?php _e('Note:',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?></p>
		<ul class="notice-list">
			<li>
				<i class="fa fa-hand-o-right"></i>
				<?php _e('You can add one app credentials to one website only.',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>
			</li>
			<li>
				<i class="fa fa-hand-o-right"></i>
				<?php _e('Same app will not work for more than 1 websites.',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>
			</li>
			<li>
				<i class="fa fa-hand-o-right"></i>
				<?php _e('You need to create multiple app to work with multiple websites.',PINTEREST_RICH_PINS_TEXT_DOMAIN); ?>
			</li>
		</ul>
	</div>
</div>