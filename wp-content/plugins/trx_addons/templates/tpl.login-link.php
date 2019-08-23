<?php
/**
 * The template to display login link
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0.1
 */

// Display link
$args = get_query_var('trx_addons_args_login');

// If user not logged in
if ( !is_user_logged_in() ) {
	?><a href="#trx_addons_login_popup" class="trx_addons_popup_link trx_addons_login_link "><?php
		?><span class="sc_layouts_item_icon sc_layouts_login_icon trx_addons_icon-user-alt"></span><?php
		?><span class="sc_layouts_item_details sc_layouts_login_details"><?php
			echo esc_html($args['login_text']);
		?></span><?php
	?></a><?php

// Else if user logged in
} else {
	?><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="trx_addons_login_link"><?php
		?><span class="sc_layouts_item_icon sc_layouts_login_icon trx_addons_icon-user-times"></span><?php
		?><span class="sc_layouts_item_details sc_layouts_login_details"><?php
			echo !empty($args['logout_text']) ? esc_html($args['logout_text']) : esc_html__('Logout', 'trx_addons');
		?></span><?php
	?></a><?php 
}
?>