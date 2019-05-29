<?php
/**
 * Users utilities
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.5
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}


// Check current user (or user with specified ID) role
// For example: if (trx_addons_users_check_role('author')) { ... }
if (!function_exists('trx_addons_users_check_role')) {
	function trx_addons_users_check_role( $role, $user_id = null ) {
		if ( is_numeric( $user_id ) )
			$user = get_userdata( $user_id );
		else
			$user = wp_get_current_user();
		if ( empty( $user ) )
			return false;
		return in_array( $role, (array) $user->roles );
	}
}



/* Login and Registration
-------------------------------------------------------------------------------- */

// Add 'Login' link to the body
if (!function_exists('trx_addons_add_login_link')) {
	add_action('trx_addons_action_login', 'trx_addons_add_login_link', 10, 2);
	function trx_addons_add_login_link($login_text='', $logout_text='') {
		if (($fdir = trx_addons_get_file_dir('templates/tpl.login-link.php')) != '') {
			global $TRX_ADDONS_STORAGE;
			$TRX_ADDONS_STORAGE['login_popup'] = !is_user_logged_in();
			set_query_var('trx_addons_args_login', array(
				'login_text' => empty($login_text) ? esc_html__('Login', 'trx_addons') : $login_text,
				'logout_text' => $logout_text
			));
			include $fdir;
		}
	}
}

// Add 'Login' popup to the body
if (!function_exists('trx_addons_add_login_popup')) {
	add_action('wp_footer', 'trx_addons_add_login_popup');
	function trx_addons_add_login_popup() {
		global $TRX_ADDONS_STORAGE;
		if (!empty($TRX_ADDONS_STORAGE['login_popup']) && ($fdir = trx_addons_get_file_dir('templates/tpl.login-popup.php')) != '') {
			if (!is_customize_preview()) {
				wp_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
				wp_enqueue_script('jquery-effects-fade', false, array('jquery','jquery-effects-core'), null, true);
			}
			include_once $fdir;
		}
	}
}
	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_users_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_users_load_scripts_front');
	function trx_addons_users_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_script( 'trx_addons-login', trx_addons_get_file_url('js/trx_addons.login.js'), array('jquery'), null, true );
		}
	}
}
	
// Add required scripts to the merged string
if ( !function_exists( 'trx_addons_users_merge_scripts' ) ) {
	add_action("trx_addons_filter_merge_scripts", 'trx_addons_users_merge_scripts');
	function trx_addons_users_merge_scripts($list) {
		$list[] = 'js/trx_addons.login.js';
		return $list;
	}
}
	
// Add vars in the localization array
if ( !function_exists( 'trx_addons_users_localize_script' ) ) {
	add_action("trx_addons_localize_script", 'trx_addons_users_localize_script');
	function trx_addons_users_localize_script($vars) {
		$vars['login_via_ajax'] 			= trx_addons_get_option('login_via_ajax');
		$vars['msg_login_empty'] 			= addslashes(esc_html__("The Login field can't be empty", 'trx_addons'));
		$vars['msg_login_long']				= addslashes(esc_html__('The Login field is too long', 'trx_addons'));
		$vars['msg_password_empty']			= addslashes(esc_html__("The password can't be empty and shorter then 4 characters", 'trx_addons'));
		$vars['msg_password_long']			= addslashes(esc_html__('The password is too long', 'trx_addons'));
		$vars['msg_login_success']			= addslashes(esc_html__('Login success! The page should be reloaded in 3 sec.', 'trx_addons'));
		$vars['msg_login_error']			= addslashes(esc_html__('Login failed!', 'trx_addons'));
		$vars['msg_not_agree']				= addslashes(esc_html__("Please, read and check 'Terms and Conditions'", 'trx_addons'));
		$vars['msg_email_long']				= addslashes(esc_html__('E-mail address is too long', 'trx_addons'));
		$vars['msg_email_not_valid']		= addslashes(esc_html__('E-mail address is invalid', 'trx_addons'));
		$vars['msg_password_not_equal']		= addslashes(esc_html__('The passwords in both fields are not equal', 'trx_addons'));
		$vars['msg_registration_success']	= addslashes(esc_html__('Registration success! Please log in!', 'trx_addons'));
		$vars['msg_registration_error']		= addslashes(esc_html__('Registration failed!', 'trx_addons'));
		return $vars;
	}
}


// AJAX: New user registration
if ( !function_exists( 'trx_addons_users_registration_user' ) ) {
	add_action('wp_ajax_trx_addons_registration_user',			'trx_addons_users_registration_user');
	add_action('wp_ajax_nopriv_trx_addons_registration_user',	'trx_addons_users_registration_user');
	function trx_addons_users_registration_user() {
	
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url('admin-ajax.php') )  || (int) get_option('users_can_register') == 0  )
			die();
	
		$user_name  = substr($_REQUEST['user_name'], 0, 60);
		$user_email = substr($_REQUEST['user_email'], 0, 60);
		$user_pwd   = substr($_REQUEST['user_pwd'], 0, 60);
	
		$response = array(
			'error' => '',
			'redirect_to' => substr($_REQUEST['redirect_to'], 0, 200)
		);
	
		$id = wp_insert_user( array ('user_login' => $user_name, 'user_pass' => $user_pwd, 'user_email' => $user_email) );
		if ( is_wp_error($id) ) {
			$response['error'] = $id->get_error_message();
		} else {
			if (($notify = trx_addons_get_option('notify_about_new_registration'))!='no' && ($admin_email = get_option('admin_email'))) {
				// Send notify to the site admin
				if (in_array($notify, array('both', 'admin'))) {
					$subj = sprintf(esc_html__('Site %s - New user registration: %s', 'trx_addons'), esc_html(get_bloginfo('site_name')), esc_html($user_name));
					$msg = "\n".esc_html__('New registration:', 'trx_addons')
						."\n".esc_html__('Name:', 'trx_addons').' '.esc_html($user_name)
						."\n".esc_html__('E-mail:', 'trx_addons').' '.esc_html($user_email)
						."\n\n............ " . esc_html(get_bloginfo('site_name')) . " (" . esc_html(esc_url(home_url('/'))) . ") ............";
					$head = "From: " . sanitize_text_field($user_email) . "\n"
						. "Reply-To: " . sanitize_text_field($user_email) . "\n";
					$rez = wp_mail($admin_email, $subj, $msg, $head);
				}
				// Send notify to the new user
				if (in_array($notify, array('both', 'user'))) {
					$subj = sprintf(esc_html__('Welcome to the "%s"', 'trx_addons'), get_bloginfo('site_name'));
					$msg = "\n".esc_html__('Your registration data:', 'trx_addons')
						."\n".esc_html__('Name:', 'trx_addons').' '.esc_html($user_name)
						."\n".esc_html__('E-mail:', 'trx_addons').' '.esc_html($user_email)
						."\n".esc_html__('Password:', 'trx_addons').' '.esc_html($user_pwd)
						."\n\n............ " . esc_html(get_bloginfo('site_name')) . " (<a href=\"" . esc_url(home_url('/')) . "\">" . esc_html(esc_url(home_url('/'))) . "</a>) ............";
					$head = "From: " . sanitize_text_field($admin_email) . "\n"
						. "Reply-To: " . sanitize_text_field($admin_email) . "\n";
					wp_mail($user_email, $subj, $msg, $head);
				}
			}
		}
		echo json_encode($response);
		die();
	}
}



// AJAX: Login user
if ( !function_exists( 'trx_addons_users_login_user' ) ) {
	add_action('wp_ajax_trx_addons_login_user',			'trx_addons_users_login_user');
	add_action('wp_ajax_nopriv_trx_addons_login_user',	'trx_addons_users_login_user');
	function trx_addons_users_login_user() {

		if (!trx_addons_get_option('login_via_ajax')) return;
	
		if ( !wp_verify_nonce( trx_addons_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$user_log = substr($_REQUEST['user_log'], 0, 60);
		$user_pwd = substr($_REQUEST['user_pwd'], 0, 60);
		$remember = substr($_REQUEST['remember'], 0, 7)=='forever';

		$response = array(
			'error' => '',
			'redirect_to' => substr($_REQUEST['redirect_to'], 0, 200)
		);

		if ( is_email( $user_log ) ) {
			$user = get_user_by('email', $user_log );
			if ( $user ) $user_log = $user->user_login;
		}

		$rez = wp_signon( array(
			'user_login' => $user_log,
			'user_password' => $user_pwd,
			'remember' => $remember
			), false );

		if ( is_wp_error($rez) ) {
			$response['error'] = $rez->get_error_message();
		}

		echo json_encode($response);
		die();
	}
}
?>