<?php
/*
Plugin Name: Coming Soon and Maintenance Mode
Plugin URI: https://codecanyon.net/item/coming-soon-and-maintenance-mode/5756382?ref=halfdata
Description: Turn your website into maintenace mode or show "Coming Soon" page.
Author: Halfdata, Inc.
Author URI: https://codecanyon.net/user/halfdata/portfolio?ref=halfdata
Version: 2.41
*/
define('CSMM_VERSION', 2.41);
define('CSMM_RECORDS_PER_PAGE', 50);
define('CSMM_AWEBER_APPID', '3ffa1b64');
register_activation_hook(__FILE__, array("csmm_class", "install"));

class csmm_class {
	var $options, $error, $info;
	var $social_icons = array('fivehundredpx' => '&#xe200;', 'aboutme' => '&#xe201;', 'addme' => '&#xe202;', 'amazon' => '&#xe203;', 'aol' => '&#xe204;', 'appstorealt' => '&#xe205;', 'appstore' => '&#xe206;', 'apple' => '&#xe207;', 'bebo' => '&#xe208;', 'behance' => '&#xe209;', 'bing' => '&#xe210;', 'blip' => '&#xe211;', 'blogger' => '&#xe212;', 'coroflot' => '&#xe213;', 'daytum' => '&#xe214;', 'delicious' => '&#xe215;', 'designbump' => '&#xe216;', 'designfloat' => '&#xe217;', 'deviantart' => '&#xe218;', 'diggalt' => '&#xe219;', 'digg' => '&#xe220;', 'dribble' => '&#xe221;', 'drupal' => '&#xe222;', 'ebay' => '&#xe223;', 'email' => '&#xe224;', 'emberapp' => '&#xe225;', 'etsy' => '&#xe226;', 'facebook' => '&#xe227;', 'feedburner' => '&#xe228;', 'flickr' => '&#xe229;', 'foodspotting' => '&#xe230;', 'forrst' => '&#xe231;', 'foursquare' => '&#xe232;', 'friendsfeed' => '&#xe233;', 'friendstar' => '&#xe234;', 'gdgt' => '&#xe235;', 'github' => '&#xe236;', 'githubalt' => '&#xe237;', 'googlebuzz' => '&#xe238;', 'googleplus' => '&#xe239;', 'googletalk' => '&#xe240;', 'gowallapin' => '&#xe241;', 'gowalla' => '&#xe242;', 'grooveshark' => '&#xe243;', 'heart' => '&#xe244;', 'hyves' => '&#xe245;', 'icondock' => '&#xe246;', 'icq' => '&#xe247;', 'identica' => '&#xe248;', 'imessage' => '&#xe249;', 'itunes' => '&#xe250;', 'lastfm' => '&#xe251;', 'linkedin' => '&#xe252;', 'meetup' => '&#xe253;', 'metacafe' => '&#xe254;', 'mixx' => '&#xe255;', 'mobileme' => '&#xe256;', 'mrwong' => '&#xe257;', 'msn' => '&#xe258;', 'myspace' => '&#xe259;', 'newsvine' => '&#xe260;', 'paypal' => '&#xe261;', 'photobucket' => '&#xe262;', 'picasa' => '&#xe263;', 'pinterest' => '&#xe264;', 'podcast' => '&#xe265;', 'posterous' => '&#xe266;', 'qik' => '&#xe267;', 'quora' => '&#xe268;', 'reddit' => '&#xe269;', 'retweet' => '&#xe270;', 'rss' => '&#xe271;', 'scribd' => '&#xe272;', 'sharethis' => '&#xe273;', 'skype' => '&#xe274;', 'slashdot' => '&#xe275;', 'slideshare' => '&#xe276;', 'smugmug' => '&#xe277;', 'soundcloud' => '&#xe278;', 'spotify' => '&#xe279;', 'squidoo' => '&#xe280;', 'stackoverflow' => '&#xe281;', 'star' => '&#xe282;', 'stumbleupon' => '&#xe283;', 'technorati' => '&#xe284;', 'tumblr' => '&#xe285;', 'twitterbird' => '&#xe286;', 'twitter' => '&#xe287;', 'viddler' => '&#xe288;', 'vimeo' => '&#xe289;', 'virb' => '&#xe290;', 'www' => '&#xe291;', 'wikipedia' => '&#xe292;', 'windows' => '&#xe293;', 'wordpress' => '&#xe294;', 'xing' => '&#xe295;', 'yahoobuzz' => '&#xe296;', 'yahoo' => '&#xe297;', 'yelp' => '&#xe298;', 'youtube' => '&#xe299;', 'instagram' => '&#xe300;');
	function __construct() {
		if (function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('csmm', false, dirname(plugin_basename(__FILE__)).'/languages/');
		}
		$this->options = array (
			"version" => CSMM_VERSION,
			"active" => "off",
			"send_503" => "on",
			"logo" => "",
			"logo_width" => "320",
			"title" => __('Coming Soon', 'csmm'),
			"title_font_size" => 48,
			"subtitle" => __('Expect something fresh and interesting.', 'csmm'),
			"subtitle_font_size" => 24,
			"copyright" => get_bloginfo('name').' &copy; '.date('Y'),
			"copyright_font_size" => 14,
			"text_color" => "#FFFFFF",
			"text_shadow_color" => "#000000",
			"background_color" => "#333333",
			"background_urls" => array(plugins_url('images/bg01.jpg', __FILE__)),
			"background_duration" => "6",
			"expiration_enable" => "on",
			"expiration_date" => date('Y-m-d', time()+24*3600*90),
			"expiration_time_hours" => date('G', time()+24*3600*90),
			"expiration_time_minutes" => date('i', time()+24*3600*90),
			"digit_background_color" => "#80C0FF",
			"digit_background_opacity" => 0.3,
			"digit_text_color" => "#FFFFFF",
			"digit_text_shadow_color" => "#000000",
			"digit_font_size" => 36,
			"timer_days_label" => 'days',
			"timer_hours_label" => 'hours',
			"timer_minutes_label" => 'minutes',
			"timer_seconds_label" => 'seconds',
			"social_icons" => array(),
			"social_links" => array(),
			"social_color" => "#A0A0A8",
			"social_hover_color" => "#304050",
			"social_shadow_color" => "#000000",
			"social_font_size" => 40,
			"optin_enable" => "on",
			"input_placeholder" => __('Your e-mail address...', 'csmm'),
			"input_background_color" => "#80C0FF",
			"input_background_opacity" => 0.3,
			"input_text_color" => "#CCCCCC",
			"input_text_shadow_color" => "#000000",
			"optin_font_size" => 18,
			"optin_message_font_size" => 14,
			"button_label" => __('Subscribe', 'csmm'),
			"button_background_color" => "#80C0FF",
			"button_background_opacity" => 0.5,
			"button_text_color" => "#CCCCCC",
			"button_text_shadow_color" => "#000000",
			"gdpr_enable" => "on",
			"gdpr_title" => __('I agree with the {Terms & Conditions}', 'csmm'),
			"gdpr_terms" => __('Drop your Terms & Conditions here.', 'csmm'),
			"optin_confirmation" => __('Thank you. We will let you know when we are ready.', 'csmm'),
			"csv_separator" => ";",
			"white_ip" => $_SERVER['REMOTE_ADDR'],
			"white_roles" => array(),
			"mailchimp_enable" => "off",
			"mailchimp_api_key" => "",
			"mailchimp_list_id" => "",
			"mailchimp_double" => "off",
			"mailchimp_welcome" => "off",
			"icontact_enable" => "off",
			"icontact_appid" => "",
			"icontact_apiusername" => "",
			"icontact_apipassword" => "",
			"icontact_listid" => "",
			"campaignmonitor_enable" => "off",
			"campaignmonitor_api_key" => '',
			"campaignmonitor_list_id" => '',
			"getresponse_enable" => "off",
			"getresponse_api_key" => '',
			"getresponse_campaign_id" => '',
			'aweber_enable' => "off",
			'aweber_consumer_key' => "",
			'aweber_consumer_secret' => "",
			'aweber_access_key' => "",
			'aweber_access_secret' => "",
			'aweber_listid' => "",
			'mymail_enable' => "off",
			'mymail_listid' => "",
			'mymail_double' => "off",
			"from_name" => get_bloginfo("name"),
			"from_email" => "noreply@".str_replace("www.", "", $_SERVER["SERVER_NAME"]),
			"thanksgiving_enable" => "off",
			"thanksgiving_email_subject" => __('Thank you!', 'csmm'),
			"thanksgiving_email_body" => __('Dear visitor,', 'csmm').PHP_EOL.PHP_EOL.__('Thank you for subscription.', 'csmm').PHP_EOL.PHP_EOL.__('Thanks,', 'csmm').PHP_EOL.get_bloginfo("name"),
			"mail_enable" => "off",
			"mail_email" => "",
			"mail_subject" => __('New subscriber', 'csmm'),
			"mail_message" => __('Dear Administrator,', 'csmm').PHP_EOL.PHP_EOL.__('This is a notification about new subscriber.', 'csmm').PHP_EOL.PHP_EOL.'<strong>E-mail:</strong> {email}'.PHP_EOL.'<strong>IP:</strong> {ip}'.PHP_EOL.'<strong>Source:</strong> {source}'.PHP_EOL.PHP_EOL.__('Thanks,', 'csmm').PHP_EOL.get_bloginfo("name"),
			"mail_from" => "off"
		);

		if (!empty($_COOKIE["scmm_error"])) {
			$this->error = stripslashes($_COOKIE["scmm_error"]);
			setcookie("scmm_error", "", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
		}
		if (!empty($_COOKIE["scmm_info"])) {
			$this->info = stripslashes($_COOKIE["scmm_info"]);
			setcookie("scmm_info", "", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
		}

		//if (defined('WP_ALLOW_MULTISITE')) $this->install();
		$this->get_options();
		
		if (is_admin()) {
			add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
			add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('admin_head', array(&$this, 'admin_head'));
			add_action('init', array(&$this, 'admin_request_handler'));
			add_action('wp_ajax_csmm_submit', array(&$this, 'submit'));
			add_action('wp_ajax_nopriv_csmm_submit', array(&$this, 'submit'));
			add_action('wp_ajax_csmm-activate', array(&$this, "activate"));
			add_action('wp_ajax_csmm-aweber-connect', array(&$this, "aweber_connect"));
			add_action('wp_ajax_csmm-aweber-disconnect', array(&$this, "aweber_disconnect"));
			add_action('wp_ajax_csmm-save-settings', array(&$this, "admin_save_settings"));
			add_filter('wp_privacy_personal_data_exporters', array(&$this, 'personal_data_exporters'), 2);
			add_filter('wp_privacy_personal_data_erasers', array(&$this, 'personal_data_erasers'), 2);
		} else {
			add_action('wp', array(&$this, 'front_wp'));
			add_action('wp_footer', array(&$this, 'front_footer'));
		}
	}

	static function install () {
		global $wpdb;
		$table_name = $wpdb->prefix . "csmm_users";
		if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
				id int(11) NOT NULL auto_increment,
				email varchar(255) collate utf8_unicode_ci NOT NULL,
				registered int(11) NOT NULL,
				deleted int(11) NOT NULL default '0',
				UNIQUE KEY  id (id)
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	function get_options() {
		$exists = get_option('csmm_version');
		if ($exists) {
			foreach ($this->options as $key => $value) {
				$this->options[$key] = get_option('csmm_'.$key, $this->options[$key]);
			}
		}
	}

	function update_options() {
		if (current_user_can('manage_options')) {
			foreach ($this->options as $key => $value) {
				update_option('csmm_'.$key, $value);
			}
		}
	}

	function admin_head() {
		echo '<script>var csmm_ajax_handler = "'.admin_url('admin-ajax.php').'";</script>';
	}

	function admin_menu() {
		add_menu_page(
			__('Coming Soon', 'csmm')
			, __('Coming Soon', 'csmm')
			, 'manage_options'
			, 'csmm'
			, array(&$this, 'admin_settings')
		);
		add_submenu_page(
			'csmm'
			, __('Settings', 'csmm')
			, __('Settings', 'csmm')
			, 'manage_options'
			, 'csmm'
			, array(&$this, 'admin_settings')
		);
		add_submenu_page(
			'csmm'
			, __('Log', 'csmm')
			, __('Log', 'csmm')
			, 'manage_options'
			, 'csmm-users'
			, array(&$this, 'admin_users')
		);
	}

	function admin_enqueue_scripts() {
		wp_enqueue_script("jquery");
		if (isset($_GET['page']) && $_GET['page'] == 'csmm') {
			wp_enqueue_script("thickbox");
			wp_enqueue_style("thickbox");
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script("jquery-ui-datepicker");
			wp_enqueue_style("jquery-ui-datepicker", plugins_url('css/ui-lightness/jquery-ui-1.9.0.custom.min.css', __FILE__));
			wp_enqueue_style("mono-social-icons", plugins_url('css/monosocialicons.css', __FILE__));
			wp_enqueue_media();
		}
		wp_enqueue_style('csmm', plugins_url('css/admin.css', __FILE__), array(), CSMM_VERSION);
		wp_enqueue_script('csmm', plugins_url('/js/admin.js', __FILE__), array(), CSMM_VERSION);

	}

	function admin_settings() {
		global $wpdb;
		
		echo '
		<div class="wrap admin_csmm_wrap">
			<h2>'.__('Coming Soon - Settings', 'csmm').'</h2>
			<form class="csmm-form" enctype="multipart/form-data" method="post" style="margin: 0px" action="'.admin_url('admin.php').'">
			<div class="postbox-container" style="width: 100%;">
				<h2 id="csmm-tabs" class="nav-tab-wrapper">
					<a class="nav-tab nav-tab-active csmm-nav-tab-active" href="#csmm-general">General</a>
					<a class="nav-tab" href="#csmm-mailing">Mailing</a>
					<a class="nav-tab" href="#csmm-integrations">Integrations</a>
				</h2>
				<div id="csmm-general" class="metabox-holder">
					<div class="meta-box-sortables ui-sortable">
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('General Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Status', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<div id="csmm-status-on"'.($this->options['active'] == 'on' ? '' : ' style="display: none;"').'>
												<strong style="color: red;">'.__('Maintenace Mode Enabled', 'csmm').'</strong>
												<br /><br /><a class="csmm-button csmm-button-small" onclick="return csmm_activate(this, 0);"><i class="csmm-fa csmm-fa-ok"></i><label>Disable Maintenance Mode</label></a>
												<br /><em>'.__('Press button to disable maintenance mode.', 'csmm').'</em>
											</div>
											<div id="csmm-status-off"'.($this->options['active'] == 'on' ? ' style="display: none;"' : '').'>
												<strong style="color: green;">'.__('Maintenace Mode Disabled', 'csmm').'</strong>
												<br /><br /><a class="csmm-button csmm-button-small" onclick="return csmm_activate(this, 1);"><i class="csmm-fa csmm-fa-ok"></i><label>Enable Maintenance Mode</label></a>
												<br /><em>'.__('Press button to switch website into maintenance mode. Website will be available only for white-listed visitors.', 'csmm').'</em>
											</div>
										</td>
									</tr>
									<tr>
										<th>'.__('Return 503', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_send_503" name="csmm_send_503" '.($this->options['send_503'] == "on" ? 'checked="checked"' : '').'"> '.__('Return HTTP status "503 Service Unavailable"', 'csmm').'
											<br /><em>'.__('Return HTTP status "503 Service Unavailable" to browser.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Title and Description Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Logo URL', 'csmm').':</th>
										<td>
											<input type="text" name="csmm_logo" value="'.esc_html($this->options['logo']).'" class="widefat csmm-input-media-library"><a href="#" class="csmm-media-library" onclick="return csmm_media_library_image(this);"><i class="csmm-fa csmm-fa-link"></i></a>
											<br /><em>'.__('The logo appears at the top of the page.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Logo max width', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_logo_width" value="'.esc_html($this->options['logo_width']).'" style="width: 100px; text-align: right;"> '.__('pixels', 'csmm').'
											<br /><em>'.__('This is a logo max width.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Page title', 'csmm').':</th>
										<td>
											<input type="text" name="csmm_title" value="'.esc_html($this->options['title']).'" class="widefat">
											<br /><em>'.__('The title appears at the top of the page.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Font size of page title', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_title_font_size" value="'.esc_html($this->options['title_font_size']).'" style="width: 100px; text-align: right;"> '.__('pixels', 'csmm').'
											<br /><em>'.__('The font size of the title.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Short description', 'csmm').':</th>
										<td>
											<input type="text" name="csmm_subtitle" value="'.esc_html($this->options['subtitle']).'" class="widefat">
											<br /><em>'.__('Description appears below the title.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Font size of description', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_subtitle_font_size" value="'.esc_html($this->options['subtitle_font_size']).'" style="width: 100px; text-align: right;"> '.__('pixels', 'csmm').'
											<br /><em>'.__('The font size of the description.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Copyright message', 'csmm').':</th>
										<td>
											<input type="text" name="csmm_copyright" value="'.esc_html($this->options['copyright']).'" class="widefat">
											<br /><em>'.__('This is a small text line at the bottom of the page.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Font size of copyright message', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_copyright_font_size" value="'.esc_html($this->options['copyright_font_size']).'" style="width: 100px; text-align: right;"> '.__('pixels', 'csmm').'
											<br /><em>'.__('The font size of the copyright message.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Text color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_text_color" value="'.esc_html($this->options['text_color']).'" placeholder="">
											<em>'.__('Set text color.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Text shadow color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_text_shadow_color" value="'.esc_html($this->options['text_shadow_color']).'" placeholder="">
											<em>'.__('Set text shadow color.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Background Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Background color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_background_color" value="'.esc_html($this->options['background_color']).'" placeholder="">
											<em>'.__('Set background color.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Background images', 'csmm').':</th>
										<td style="vertical-align: top; padding-top: 0;">
											<table style="width: 100%;">';
		$i = 0;
		foreach($this->options['background_urls'] as $value) {
			echo '									
												<tr><td>
													<input type="text" name="csmm_background_urls[]" value="'.esc_html($value).'" class="widefat csmm-input-media-library"><a href="#" class="csmm-media-library" onclick="return csmm_media_library_image(this);"><i class="csmm-fa csmm-fa-link"></i></a>
													<br /><em>'.($i > 0 ? '<a href="#" onclick="return csmm_removeurl(this);">'.__('Remove URL', 'csmm').'</a>' : __('Background image URL', 'csmm')).'</em>
												</td></tr>';
			$i++;
		}
		echo '
												<tr style="display: none;" id="background-url-row-template"><td>
													<input type="text" name="csmm_background_urls[]" value="" class="widefat csmm-input-media-library"><a href="#" class="csmm-media-library" onclick="return csmm_media_library_image(this);"><i class="csmm-fa csmm-fa-link"></i></a>
													<br /><em><a href="#" onclick="return csmm_removeurl(this);">'.__('Remove URL', 'csmm').'</a></em>
												</td></tr>
												<tr id="add-social-link-row">
													<td colspan="2">
														<a href="#" class="csmm-button csmm-button-small" onclick="return csmm_addurl(this);">'.__('Add Background Image', 'csmm').'</a>
													</a>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<th>'.__('Slide duration', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_background_duration" value="'.esc_html($this->options['background_duration']).'" style="width: 80px; text-align: right;"> '.__('seconds', 'csmm').'
											<br /><em>'.__('Slide duration.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Countdown Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Enable countdown', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_expiration_enable" name="csmm_expiration_enable" '.($this->options['expiration_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Enable countdown', 'csmm').'
											<br /><em>'.__('Tick checkbox to activate countdown.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Expiration date/time', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_expiration_date" id="csmm_expiration_date" value="'.$this->options['expiration_date'].'" style="width: 100px; text-align: right;">
											&nbsp;&nbsp;
											<select name="csmm_expiration_time_hours" id="csmm_expiration_time_hours" style="width: 80px;">';
		for ($i=0; $i<24; $i++)	{
			echo '
												<option value="'.$i.'"'.($i == $this->options['expiration_time_hours'] ? ' selected="selected"' : '').'>'.($i < 10 ? '0'.$i : $i).'</option>';
		}
		echo '
											</select> :
											<select name="csmm_expiration_time_minutes" id="csmm_expiration_time_minutes" style="width: 80px;">';
		for ($i=0; $i<60; $i++)	{
			echo '
												<option value="'.$i.'"'.($i == $this->options['expiration_time_minutes'] ? ' selected="selected"' : '').'>'.($i < 10 ? '0'.$i : $i).'</option>';
		}
		echo '
											</select>
											<br /><em>'.__('Enter expiration date. Countdown will be automatically disabled if expiration date is in the past. Important! This is server time.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Digit background color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_digit_background_color" value="'.esc_html($this->options['digit_background_color']).'" placeholder="">
											<em>'.__('Set the background color of the digit.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Digit background opacity', 'csmm').':</th>
										<td>
											<input type="text" class="ic_input_number" name="csmm_digit_background_opacity" value="'.esc_html($this->options['digit_background_opacity']).'" placeholder="">
											<br /><em>'.__('Set the background opacity of the digit.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Digit color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_digit_text_color" value="'.esc_html($this->options['digit_text_color']).'" placeholder="">
											<em>'.__('Set the color of the digit.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Digit shadow color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_digit_text_shadow_color" value="'.esc_html($this->options['digit_text_shadow_color']).'" placeholder="">
											<em>'.__('Set the shadow color of the digit.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Font size of digits', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_digit_font_size" value="'.esc_html($this->options['digit_font_size']).'" style="width: 100px; text-align: right;"> '.__('pixels', 'csmm').'
											<br /><em>'.__('The font size of digits.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Timer labels', 'csmm').':</th>
										<td>
											<table style="width: 200px;">
												<tr>
													<td style="width: 70px">'.__('Days', 'csmm').':</td>
													<td>
														<input type="text" name="csmm_timer_days_label" id="csmm_timer_days_label" value="'.esc_html($this->options['timer_days_label']).'" class="widefat" />
													</td>
												</tr>
												<tr>
													<td>'.__('Hours', 'csmm').':</td>
													<td>
														<input type="text" name="csmm_timer_hours_label" id="csmm_timer_hours_label" value="'.esc_html($this->options['timer_hours_label']).'" class="widefat" />
													</td>
												</tr>
												<tr>
													<td>'.__('Minutes', 'csmm').':</td>
													<td>
														<input type="text" name="csmm_timer_minutes_label" id="csmm_timer_minutes_label" value="'.esc_html($this->options['timer_minutes_label']).'" class="widefat" />
													</td>
												</tr>
												<tr>
													<td>'.__('Seconds', 'csmm').':</td>
													<td>
														<input type="text" name="csmm_timer_seconds_label" id="csmm_timer_seconds_label" value="'.esc_html($this->options['timer_seconds_label']).'" class="widefat" />
													</td>
												</tr>
											</table>
											<em>'.__('Please enter labels for countdown timer cells.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Social Links Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Social links', 'csmm').':</th>
										<td style="vertical-align: middle; padding-top: 0;">
											<table style="width: 100%;">';
		foreach($this->options['social_icons'] as $key => $value) {
			echo '									
												<tr>
													<td style="width: 20px; vertical-align: top; padding-top: 13px;">
														<span class="symbol csmm_selectedicon" onclick="return csmm_toggleicons(this);">'.$this->social_icons[$value].'</span>
														<input type="hidden" class="csmm_social_icon" name="csmm_social_icons[]" value="'.$value.'">
													</td>
													<td style="vertical-align: middle;">
														<input type="text" name="csmm_social_links[]" value="'.esc_html($this->options['social_links'][$key]).'" class="widefat">
														<br /><em><a href="#" onclick="return csmm_remove(this);">'.__('Remove Icon', 'csmm').'</a></em>
														<div class="social-icons">';
			foreach($this->social_icons as $key => $icon) {
				echo '
															<span class="symbol" title="'.$key.'" onclick="csmm_selecticon(this, \''.$key.'\', \''.$icon.'\');">'.$icon.'</span>';
			}
			echo '
														</div>
													</td>
												</tr>';
		}
		echo '
												<tr style="display: none;" id="social-link-row-template">
													<td style="width: 20px; vertical-align: top; padding-top: 13px;">
														<span class="symbol csmm_selectedicon" onclick="return csmm_toggleicons(this);">&#xe227;</span>
														<input type="hidden" class="csmm_social_icon" name="csmm_social_icons[]" value="facebook">
													</td>
													<td style="vertical-align: middle;">
														<input type="text" name="csmm_social_links[]" value="" class="widefat">
														<br /><em><a href="#" onclick="return csmm_remove(this);">'.__('Remove Icon', 'csmm').'</a></em>
														<div class="social-icons">';
		foreach($this->social_icons as $key => $icon) {
			echo '
															<span class="symbol" title="'.$key.'" onclick="csmm_selecticon(this, \''.$key.'\', \''.$icon.'\');">'.$icon.'</span>';
		}
		echo '
														</div>
													</td>
												</tr>
												<tr id="add-social-link-row">
													<td colspan="2">
														<a href="#" class="csmm-button csmm-button-small" onclick="return csmm_add(this);">'.__('Add Social Icon', 'csmm').'</a>
													</a>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<th>'.__('Icon color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_social_color" value="'.esc_html($this->options['social_color']).'" placeholder="">
											<em>'.__('Set social icon color.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Icon hover color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_social_hover_color" value="'.esc_html($this->options['social_hover_color']).'" placeholder="">
											<em>'.__('Set social icon hover color.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Icon shadow color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_social_shadow_color" value="'.esc_html($this->options['social_shadow_color']).'" placeholder="">
											<em>'.__('Set social icon shadow color.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Social link size', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_social_font_size" value="'.esc_html($this->options['social_font_size']).'" style="width: 100px; text-align: right;"> '.__('pixels', 'csmm').'
											<br /><em>'.__('The font size of social icons.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Opt-In Form Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Opt-in form', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_optin_enable" name="csmm_optin_enable" '.($this->options['optin_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Enable opt-in form', 'csmm').'
											<br /><em>'.__('Tick checkbox to activate opt-in form.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Input field placeholder', 'csmm').':</th>
										<td>
											<input type="text" name="csmm_input_placeholder" value="'.esc_html($this->options['input_placeholder']).'" class="widefat">
											<br /><em>'.__('Set the placeholder of the input field.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Input field color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_input_background_color" value="'.esc_html($this->options['input_background_color']).'" placeholder="">
											<em>'.__('Set the color of the input field.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Input field opacity', 'csmm').':</th>
										<td>
											<input type="text" class="ic_input_number" name="csmm_input_background_opacity" value="'.esc_html($this->options['input_background_opacity']).'" placeholder="">
											<br /><em>'.__('Set the opacity of the input field.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Input field text color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_input_text_color" value="'.esc_html($this->options['input_text_color']).'" placeholder="">
											<em>'.__('Set the text color of the input field.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Input field text shadow color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_input_text_shadow_color" value="'.esc_html($this->options['input_text_shadow_color']).'" placeholder="">
											<em>'.__('Set the text shadow color of the input field.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Button label', 'csmm').':</th>
										<td>
											<input type="text" name="csmm_button_label" value="'.esc_html($this->options['button_label']).'" class="widefat">
											<br /><em>'.__('Set the label of the button.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Button color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_button_background_color" value="'.esc_html($this->options['button_background_color']).'" placeholder="">
											<em>'.__('Set the color of the button.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Button opacity', 'csmm').':</th>
										<td>
											<input type="text" class="ic_input_number" name="csmm_button_background_opacity" value="'.esc_html($this->options['button_background_opacity']).'" placeholder="">
											<br /><em>'.__('Set the opacity of the button.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Button text color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_button_text_color" value="'.esc_html($this->options['button_text_color']).'" placeholder="">
											<em>'.__('Set the text color of the button.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Button text shadow color', 'csmm').':</th>
										<td>
											<input type="text" class="csmm-color ic_input_number" name="csmm_button_text_shadow_color" value="'.esc_html($this->options['button_text_shadow_color']).'" placeholder="">
											<em>'.__('Set the text shadow color of the button.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Font size of opt-in form', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_optin_font_size" value="'.esc_html($this->options['optin_font_size']).'" style="width: 100px; text-align: right;"> '.__('pixels', 'csmm').'
											<br /><em>'.__('The font size of the opt-in form.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('GDPR-compatibility', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_gdpr_enable" name="csmm_gdpr_enable" '.($this->options['gdpr_enable'] == "on" ? 'checked="checked"' : '').'" onclick="csmm_switch_gdpr();"> '.__('Enable checkbox to agree with the Terms & Conditions', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to add checkbox to subscription form.', 'csmm').'</em>
										</td>
									</tr>
									<tr class="csmm-gdpr-depend"'.($this->options['gdpr_enable'] == "on" ? ' style="display:table-row;"' : '').'>
										<th>'.__('Checkbox label', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_gdpr_title" name="csmm_gdpr_title" value="'.esc_html($this->options['gdpr_title']).'" class="widefat">
											<br /><em>'.__('Enter the label for GDPR checkbox. Wrap your keyword with "{" and "}" to link it with Terms & Conditions box. HTML allowed.', 'csmm').'</em>
										</td>
									</tr>
									<tr class="csmm-gdpr-depend"'.($this->options['gdpr_enable'] == "on" ? ' style="display:table-row;"' : '').'>
										<th>'.__('Terms & Conditions', 'csmm').':</th>
										<td>
											<textarea id="csmm_gdpr_terms" name="csmm_gdpr_terms" class="widefat" style="height: 120px;">'.esc_html($this->options['gdpr_terms']).'</textarea>
											<br /><em>'.__('Drop your Terms & Conditions text here.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Confirmation message', 'csmm').':</th>
										<td>
											<input type="text" name="csmm_optin_confirmation" value="'.esc_html($this->options['optin_confirmation']).'" class="widefat">
											<br /><em>'.__('This message appears after successful subscription.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Font size of message', 'csmm').':</th>
										<td style="vertical-align: middle;">
											<input type="text" name="csmm_optin_message_font_size" value="'.esc_html($this->options['optin_message_font_size']).'" style="width: 100px; text-align: right;"> '.__('pixels', 'csmm').'
											<br /><em>'.__('The font size of the opt-in form.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Miscellaneous Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('CSV column separator', 'csmm').':</th>
										<td>
											<select id="csmm_csv_separator" name="csmm_csv_separator">
												<option value=";"'.($this->options['csv_separator'] == ';' ? ' selected="selected"' : '').'>'.__('Semicolon - ";"', 'csmm').'</option>
												<option value=","'.($this->options['csv_separator'] == ',' ? ' selected="selected"' : '').'>'.__('Comma - ","', 'csmm').'</option>
												<option value="tab"'.($this->options['csv_separator'] == 'tab' ? ' selected="selected"' : '').'>'.__('Tab', 'csmm').'</option>
											</select>
											<br /><em>'.__('Select CSV column separator.', 'csmm').'</em>
										</td>
									</tr>
									<tr><td colspan="2"><hr></td></tr>
									<tr>
										<th>'.__('White IP-addresses', 'csmm').':</th>
										<td>
											<textarea name="csmm_white_ip" class="widefat" style="height: 120px;">'.esc_html($this->options['white_ip']).'</textarea>
											<br /><em>'.__('Maintenance mode will never be enabled for these IP-addresses. <strong>Important! One IP per line.</strong>', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('White user roles', 'csmm').':</th>
										<td style="line-height: 1.7;">';
		$roles = get_editable_roles();
		if (sizeof($roles) > 0) {
			echo '
											<input type="hidden" name="csmm_edit_roles" value="1">';
			foreach ($roles as $key => $value) {
				if ($key == 'administrator') continue;
				echo '
											<input type="checkbox" name="csmm_role_'.$key.'"'.(in_array($key, $this->options['white_roles']) ? ' checked="checked"' : '').'> '.$value['name'].'<br />';
			}
		}
		echo '
											<em>'.__('Maintenance mode will never be enabled for these user roles!', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="csmm-mailing" class="metabox-holder" style="display: none;">
					<div class="meta-box-sortables ui-sortable">
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('General Mailing Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Sender name', 'csmm').':</th>
										<td><input type="text" id="csmm_from_name" name="csmm_from_name" value="'.esc_html($this->options['from_name']).'" class="widefat"><br /><em>'.__('Please enter sender name. All messages are sent using this name as "FROM:" header value.', 'csmm').'</em></td>
									</tr>
									<tr>
										<th>'.__('Sender e-mail', 'csmm').':</th>
										<td><input type="text" id="csmm_from_email" name="csmm_from_email" value="'.esc_html($this->options['from_email']).'" class="widefat"><br /><em>'.__('Please enter sender e-mail. All messages are sent using this e-mail as "FROM:" header value.', 'csmm').'</em></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Welcome Message Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Enable', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_thanksgiving_enable" name="csmm_thanksgiving_enable" '.($this->options['thanksgiving_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Send welcome message', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to send welcome message to subscriber.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Subject', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_thanksgiving_email_subject" name="csmm_thanksgiving_email_subject" value="'.esc_html($this->options['thanksgiving_email_subject']).'" class="widefat">
											<br /><em>'.__('In case of successful subscription, your visitors receive e-mail message which contains download link. This is the subject field of the message.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Message', 'csmm').':</th>
										<td>
											<textarea id="csmm_thanksgiving_email_body" name="csmm_thanksgiving_email_body" class="widefat" style="height: 120px;">'.esc_html($this->options['thanksgiving_email_body']).'</textarea>
											<br /><em>'.__('This e-mail message is sent to your visitor in case of successful subscription. You can use the following keywords: {email}.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Admin Notification Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Enable', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_mail_enable" name="csmm_mail_enable" '.($this->options['mail_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Send details to admin', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to receive submitted contact details by e-mail.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Admin e-mail address', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_mail_email" name="csmm_mail_email" value="'.esc_html($this->options['mail_email']).'" class="widefat">
											<br /><em>'.__('Enter your e-mail address. Submitted contact details will be sent to this e-mail address. You can set several comma-separated e-mails.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Subject', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_mail_subject" name="csmm_mail_subject" value="'.esc_html($this->options['mail_subject']).'" class="widefat">
											<br /><em>'.__('In case of successful subscription, administrator may receive notification message. This is subject field of the message.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Message', 'csmm').':</th>
										<td>
											<textarea id="csmm_mail_message" name="csmm_mail_message" class="widefat" style="height: 120px;">'.esc_html($this->options['mail_message']).'</textarea>
											<br /><em>'.__('This notification is sent to administrator in case of successful subscription. You can use the shortcodes the following shortcodes: {email}, {ip}, {source}, {user-agent}.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('"FROM" header', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_mail_from" name="csmm_mail_from" '.($this->options['mail_from'] == "on" ? 'checked="checked"' : '').'"> '.__("Put user's e-mail into FROM header (not recommended)", 'csmm').'
											<br /><em>'.__('Please remember, many hosting providers do not allow to send e-mails with 3rd party FROM header.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="csmm-integrations" class="metabox-holder" style="display: none;">
					<div class="meta-box-sortables ui-sortable">
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('MailChimp Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Enable MailChimp', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_mailchimp_enable" name="csmm_mailchimp_enable" '.($this->options['mailchimp_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Submit contact details to MailChimp', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to submit contact details to MailChimp. <strong>CURL required!</strong>', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('MailChimp API Key:', 'csmm').'</th>
										<td>
											<input type="text" id="csmm_mailchimp_api_key" name="csmm_mailchimp_api_key" value="'.htmlspecialchars($this->options['mailchimp_api_key'], ENT_QUOTES).'" class="widefat">
											<br /><em>'.__('Enter your MailChimp API Key. You can get it <a href="https://admin.mailchimp.com/account/api-key-popup" target="_blank">here</a>.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('List ID:', 'csmm').'</th>
										<td>
											<input type="text" id="csmm_mailchimp_list_id" name="csmm_mailchimp_list_id" value="'.htmlspecialchars($this->options['mailchimp_list_id'], ENT_QUOTES).'" class="widefat">
											<br /><em>'.__('Enter your List ID. You can get it <a href="https://admin.mailchimp.com/lists/" target="_blank">here</a> (click <strong>Settings</strong>).', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Double opt-in', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_mailchimp_double" name="csmm_mailchimp_double" '.($this->options['mailchimp_double'] == "on" ? 'checked="checked"' : '').'"> '.__('Ask users to confirm their subscription', 'csmm').'
											<br /><em>'.__('Control whether a double opt-in confirmation message is sent.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Send Welcome', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_mailchimp_welcome" name="csmm_mailchimp_welcome" '.($this->options['mailchimp_welcome'] == "on" ? 'checked="checked"' : '').'"> '.__('Send Lists Welcome message', 'csmm').'
											<br /><em>'.__('If your <strong>Double opt-in</strong> is disabled and this is enabled, MailChimp will send your lists Welcome Email if this subscribe succeeds. If <strong>Double opt-in</strong> is enabled, this has no effect.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('iContact Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Enable iContact', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_icontact_enable" name="csmm_icontact_enable" '.($this->options['icontact_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Submit contact details to iContact', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to submit contact details to iContact. <strong>CURL required!</strong>', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('AppID', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_icontact_appid" name="csmm_icontact_appid" value="'.htmlspecialchars($this->options['icontact_appid'], ENT_QUOTES).'" class="widefat" onchange="icontact_handler();">
											<br /><em>'.__('Obtained when you <a href="http://developer.icontact.com/documentation/register-your-app/" target="_blank">Register the API application</a>. This identifier is used to uniquely identify your application.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('API Username', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_icontact_apiusername" name="csmm_icontact_apiusername" value="'.htmlspecialchars($this->options['icontact_apiusername'], ENT_QUOTES).'" class="widefat" onchange="icontact_handler();">
											<br /><em>'.__('The iContact username for logging into your iContact account.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('API Password', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_icontact_apipassword" name="csmm_icontact_apipassword" value="'.htmlspecialchars($this->options['icontact_apipassword'], ENT_QUOTES).'" class="widefat" onchange="icontact_handler();">
											<br /><em>'.__('The API application password set when the application was registered. This API password is used as input when your application authenticates to the API. This password is not the same as the password you use to log in to iContact.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('List ID', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_icontact_listid" name="csmm_icontact_listid" value="'.esc_html($this->options['icontact_listid']).'" class="widefat">
											<br /><em>'.__('Enter your List ID. You can get List ID from', 'csmm').' <a href="'.admin_url('admin.php').'?action=csmm-icontact-lists&appid='.esc_html($this->options['icontact_appid']).'&user='.esc_html($this->options['icontact_apiusername']).'&pass='.esc_html($this->options['icontact_apipassword']).'" class="thickbox" id="icontact_lists" title="'.__('Available Lists', 'csmm').'">'.__('this table', 'csmm').'</a>.</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('GetResponse Details', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Enable GetResponse', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_getresponse_enable" name="csmm_getresponse_enable" '.($this->options['getresponse_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Submit contact details to GetResponse', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to submit contact details to GetResponse. <strong>CURL required!</strong>', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('API Key', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_getresponse_api_key" name="csmm_getresponse_api_key" value="'.esc_html($this->options['getresponse_api_key']).'" class="widefat" onchange="getresponse_handler();">
											<br /><em>'.__('Enter your GetResponse API Key. You can get your API Key <a href="https://app.getresponse.com/my_api_key.html" target="_blank">here</a>.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Campaign ID', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_getresponse_campaign_id" name="csmm_getresponse_campaign_id" value="'.esc_html($this->options['getresponse_campaign_id']).'" class="widefat">
											<br /><em>'.__('Enter your Campaign ID. You can get Campaign ID from', 'csmm').' <a href="'.admin_url('admin.php').'?action=csmm-getresponse-campaigns&key='.esc_html($this->options['getresponse_api_key']).'" class="thickbox" id="getresponse_campaigns" title="'.__('Available Campaigns', 'csmm').'">'.__('this table', 'csmm').'</a>.</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('Campaign Monitor Details', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Enable Campaign Monitor', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_campaignmonitor_enable" name="csmm_campaignmonitor_enable" '.($this->options['campaignmonitor_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Submit contact details to Campaign Monitor', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to submit contact details to Campaign Monitor. <strong>CURL required!</strong>', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('API Key', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_campaignmonitor_api_key" name="csmm_campaignmonitor_api_key" value="'.esc_html($this->options['campaignmonitor_api_key']).'" class="widefat">
											<br /><em>'.__('Enter your Campaign Monitor API Key. You can get your API Key from the Account Settings page when logged into your Campaign Monitor account.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('List ID', 'csmm').':</th>
										<td>
											<input type="text" id="csmm_campaignmonitor_list_id" name="csmm_campaignmonitor_list_id" value="'.esc_html($this->options['campaignmonitor_list_id']).'" class="widefat">
											<br /><em>'.__('Enter your List ID. You can get List ID from the list editor page when logged into your Campaign Monitor account.', 'csmm').'</em>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('AWeber Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">
									<tr>
										<th>'.__('Enable AWeber', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_aweber_enable" name="csmm_aweber_enable" '.($this->options['aweber_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Submit contact details to AWeber', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to submit contact details to AWeber.', 'csmm').'</em>
										</td>
									</tr>';
		$account = null;
		if ($this->options['aweber_access_secret']) {
			if (!class_exists('AWeberAPI')) {
				require_once(dirname(__FILE__).'/aweber_api/aweber_api.php');
			}
			try {
				$aweber = new AWeberAPI($this->options['aweber_consumer_key'], $this->options['aweber_consumer_secret']);
				$account = $aweber->getAccount($this->options['aweber_access_key'], $this->options['aweber_access_secret']);
			} catch (AWeberException $e) {
				$account = null;
			}
		}
		if (!$account) {
			echo '
									<tbody id="csmm-aweber-group">
										<tr>
											<th>'.__('Authorization code', 'csmm').':</th>
											<td>
												<input type="text" id="csmm_aweber_oauth_id" value="" class="widefat csmm-input" placeholder="AWeber authorization code">
												<br />Get your authorization code <a target="_blank" href="https://auth.aweber.com/1.0/oauth/authorize_app/'.CSMM_AWEBER_APPID.'">'.__('here', 'csmm').'</a>.
											</td>
										</tr>
										<tr>
											<th></th>
											<td style="vertical-align: middle;">
												<a class="csmm-button csmm-button-small" onclick="return csmm_aweber_connect(this);"><i class="csmm-fa csmm-fa-ok"></i><label>Connect</label></a>
											</td>
										</tr>
									</tbody>';
		} else {
			echo '
									<tbody id="csmm-aweber-group">
										<tr>
											<th>'.__('Connected', 'csmm').':</th>
											<td>
												<a class="csmm-button csmm-button-small" onclick="return csmm_aweber_disconnect(this);"><i class="csmm-fa csmm-fa-ok"></i><label>Disconnect</label></a>
												<br /><em>'.__('Click the button to disconnect.', 'csmm').'</em>
											</td>
										</tr>
										<tr>
											<th>'.__('List ID', 'csmm').':</th>
											<td>
												<select name="csmm_aweber_listid" style="width: 40%;">
													<option value="">'.__('--- Select List ID ---', 'csmm').'</option>';
				$lists = $account->lists;
				foreach ($lists as $list) {
					echo '
													<option value="'.$list->id.'"'.($list->id == $this->options['aweber_listid'] ? ' selected="selected"' : '').'>'.$list->name.'</option>';
				}
				echo '
												</select>
												<br /><em>'.__('Select your List ID.', 'csmm').'</em>
											</td>
										</tr>
									</tbody>';
		}
		echo '
								</table>
								<div id="csmm-aweber-message"></div>
							</div>
						</div>';
		if (function_exists('mymail_subscribe') || function_exists('mymail')) {
			echo '
						<div class="postbox csmm_postbox">
							<h3 class="hndle" style="cursor: default;"><span>'.__('MyMail Settings', 'csmm').'</span></h3>
							<div class="inside">
								<table class="csmm_useroptions">';
			if (function_exists('mymail')) {
				$lists = mymail('lists')->get();
				$create_list_url = 'edit.php?post_type=newsletter&page=mymail_lists';
			} else {
				$lists = get_terms('newsletter_lists', array('hide_empty' => false));
				$create_list_url = 'edit-tags.php?taxonomy=newsletter_lists&post_type=newsletter';
			}
			if (sizeof($lists) == 0) {
				echo '
									<tr>
										<th>'.__('Enable MyMail', 'csmm').':</th>
										<td>'.__('Please', 'csmm').' <a href="'.$create_list_url.'">'.__('create', 'csmm').'</a> '.__('at least one list.', 'csmm').'</td>
									</tr>';
			} else {
				echo '
									<tr>
										<th>'.__('Enable MyMail', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_mymail_enable" name="csmm_mymail_enable" '.($this->options['mymail_enable'] == "on" ? 'checked="checked"' : '').'"> '.__('Submit contact details to MyMail', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to submit contact details to MyMail.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('List ID', 'csmm').':</th>
										<td>
											<select name="csmm_mymail_listid">';
				foreach ($lists as $list) {
					if (function_exists('mymail')) $id = $list->ID;
					else $id = $list->term_id;
					echo '
												<option value="'.$id.'"'.($id == $this->options['mymail_listid'] ? ' selected="selected"' : '').'>'.$list->name.'</option>';
				}
				echo '
											</select>
											<br /><em>'.__('Select your List ID.', 'csmm').'</em>
										</td>
									</tr>
									<tr>
										<th>'.__('Double Opt-In', 'csmm').':</th>
										<td>
											<input type="checkbox" id="csmm_mymail_double" name="csmm_mymail_double" '.($this->options['mymail_double'] == "on" ? 'checked="checked"' : '').'"> '.__('Enable Double Opt-In', 'csmm').'
											<br /><em>'.__('Please tick checkbox if you want to enable double opt-in feature.', 'csmm').'</em>
										</td>
									</tr>';
			}
			echo '
								</table>
							</div>
						</div>';
		}
		echo '
					</div>
				</div>
				<hr>
				<div class="csmm-button-container">
					<input type="hidden" name="action" value="csmm-save-settings" />
					<input type="hidden" name="csmm_version" value="'.CSMM_VERSION.'" />
					<a class="csmm-button" onclick="return csmm_save_settings(this);"><i class="csmm-fa csmm-fa-ok"></i><label>Save Settings</label></a>
				</div>
				<div class="csmm-message"></div>
			</div>
			</form>
			<div id="csmm-global-message"></div>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery(".csmm-color").wpColorPicker();
					jQuery("#csmm_expiration_date").datepicker({
						defaultDate: "+1m",
						numberOfMonths: 2,
						dateFormat: "yy-mm-dd"
					});
				});
				jQuery("#csmm-tabs a").click(function(){
					if (jQuery(this).hasClass("csmm-nav-tab-active")) {
					} else {
						var active_tab = jQuery(".csmm-nav-tab-active").attr("href");
						jQuery(".csmm-nav-tab-active").removeClass("nav-tab-active");
						jQuery(".csmm-nav-tab-active").removeClass("csmm-nav-tab-active");
						var tab = jQuery(this).attr("href");
						jQuery(this).addClass("nav-tab-active");
						jQuery(this).addClass("csmm-nav-tab-active");
						jQuery(active_tab).fadeOut(300, function(){
							jQuery(tab).fadeIn(300);
						});
					}
					return false;
				});
				function getresponse_handler() {
					jQuery("#getresponse_campaigns").attr("href", "'.admin_url('admin.php').'?action=csmm-getresponse-campaigns&key="+jQuery("#csmm_getresponse_api_key").val());
				}
				function icontact_handler() {
					jQuery("#icontact_lists").attr("href", "'.admin_url('admin.php').'?action=csmm-icontact-lists&appid="+jQuery("#csmm_icontact_appid").val()+"&user="+jQuery("#csmm_icontact_apiusername").val()+"&pass="+jQuery("#csmm_icontact_apipassword").val());
				}
			</script>			
		</div>';
	}

	function admin_save_settings() {
		global $wpdb;
		if (current_user_can('manage_options')) {
			foreach ($this->options as $key => $value) {
				if (isset($_POST['csmm_'.$key])) {
					$this->options[$key] = stripslashes($_POST['csmm_'.$key]);
				}
			}
			if (isset($_POST["csmm_thanksgiving_enable"])) $this->options['thanksgiving_enable'] = "on";
			else $this->options['thanksgiving_enable'] = "off";
			if (isset($_POST["csmm_mail_enable"])) $this->options['mail_enable'] = "on";
			else $this->options['mail_enable'] = "off";
			if (isset($_POST["csmm_mail_from"])) $this->options['mail_from'] = "on";
			else $this->options['mail_from'] = "off";
			if (isset($_POST["csmm_optin_enable"])) $this->options['optin_enable'] = "on";
			else $this->options['optin_enable'] = "off";
			if (isset($_POST["csmm_send_503"])) $this->options['send_503'] = "on";
			else $this->options['send_503'] = "off";
			if (isset($_POST["csmm_expiration_enable"])) $this->options['expiration_enable'] = "on";
			else $this->options['expiration_enable'] = "off";
			if (isset($_POST["csmm_mailchimp_double"])) $this->options['mailchimp_double'] = "on";
			else $this->options['mailchimp_double'] = "off";
			if (isset($_POST["csmm_mailchimp_welcome"])) $this->options['mailchimp_welcome'] = "on";
			else $this->options['mailchimp_welcome'] = "off";
			if (isset($_POST["csmm_mailchimp_enable"])) $this->options['mailchimp_enable'] = "on";
			else $this->options['mailchimp_enable'] = "off";
			if (isset($_POST["csmm_icontact_enable"])) $this->options['icontact_enable'] = "on";
			else $this->options['icontact_enable'] = "off";
			if (isset($_POST["csmm_campaignmonitor_enable"])) $this->options['campaignmonitor_enable'] = "on";
			else $this->options['campaignmonitor_enable'] = "off";
			if (isset($_POST["csmm_getresponse_enable"])) $this->options['getresponse_enable'] = "on";
			else $this->options['getresponse_enable'] = "off";
			if (isset($_POST["csmm_aweber_enable"])) $this->options['aweber_enable'] = "on";
			else $this->options['aweber_enable'] = "off";
			if (isset($_POST["csmm_mymail_enable"])) $this->options['mymail_enable'] = "on";
			else $this->options['mymail_enable'] = "off";
			if (isset($_POST["csmm_mymail_double"])) $this->options['mymail_double'] = "on";
			else $this->options['mymail_double'] = "off";
			if (isset($_POST["csmm_gdpr_enable"])) $this->options['gdpr_enable'] = "on";
			else $this->options['gdpr_enable'] = "off";
			if (is_array($_POST['csmm_social_links'])) {
				$social_links = array();
				$social_icons = array();
				foreach($_POST['csmm_social_links'] as $key => $value) {
					if (!empty($value)) {
						$social_icons[] = stripslashes(trim($_POST['csmm_social_icons'][$key]));
						$social_links[] = stripslashes(trim($value));
					}
				}
				$this->options['social_links'] = $social_links;
				$this->options['social_icons'] = $social_icons;
			}
			if (is_array($_POST['csmm_background_urls'])) {
				$urls = array();
				foreach($_POST['csmm_background_urls'] as $value) {
					if (!empty($value)) {
						$urls[] = stripslashes(trim($value));
					}
				}
				$this->options['background_urls'] = $urls;
			}
			$white_str = str_replace(array("\r", "\n", "\t", ","), array("", " ", " ", " "), stripslashes(trim($_POST['csmm_white_ip'])));
			$tmp_elements = explode(" ", $white_str);
			$white_ip = array();
			foreach($tmp_elements as $element) {
				$element = trim($element);
				if ($element != '') {
					$parts = explode(".", $element);
					if (sizeof($parts) == 4) {
						$ok = true;
						foreach($parts as $part) {
							if (!ctype_digit($part) || $part > 255) $ok = false;
						}
						if ($ok) $white_ip[] = $element;
					}
				}
			}
			$this->options['white_ip'] = implode(PHP_EOL, $white_ip);

			if (isset($_POST['csmm_edit_roles'])) {
				$this->options['white_roles'] = array();
				foreach ($_POST as $key => $value) {
					if (substr($key, 0, strlen('csmm_role_')) == 'csmm_role_') $this->options['white_roles'][] = substr($key, strlen('csmm_role_'));
				}
			}
			
			$errors = array();
			if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,19})$/i", $this->options['from_email']) || strlen($this->options['from_email']) == 0) $errors[] = __('Sender e-mail must be valid e-mail address.', 'csmm');
			if (strlen($this->options['from_name']) < 3) $errors[] = __('Sender name is too short.', 'csmm');
			if ($this->options['thanksgiving_enable'] == 'on') {
				if (strlen($this->options['thanksgiving_email_subject']) < 3) $errors[] = __('Thanksgiving e-mail subject must contain at least 3 characters.', 'csmm');
				else if (strlen($this->options['thanksgiving_email_subject']) > 64) $errors[] = __('Thanksgiving e-mail subject must contain maximum 64 characters.', 'csmm');
				if (strlen($this->options['thanksgiving_email_body']) < 3) $errors[] = __('Thanksgiving e-mail body must contain at least 3 characters.', 'csmm');
			}
			if ($this->options['mail_enable'] == 'on') {
				$emails = explode(',', $this->options['mail_email']);
				$emails_found = false;
				$emails_invalid = false;
				foreach ($emails as $email) {
					$email = trim($email);
					if (!empty($email)) {
						if (!preg_match("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,19})$/i", $email)) $emails_invalid = true;
						else $emails_found = true;
					}
				}
				if (!$emails_found) $errors[] = __('Admin e-mail must be valid e-mail address.', 'csmm');
				else if ($emails_invalid) $errors[] = __('Admin e-mail must be valid e-mail address.', 'csmm');
				if (strlen($this->options['mail_subject']) < 3) $errors[] = __('Notification subject must contain at least 3 characters.', 'csmm');
				else if (strlen($this->options['mail_subject']) > 64) $errors[] = __('Notification subject must contain maximum 64 characters.', 'csmm');
				if (strlen($this->options['mail_message']) < 3) $errors[] = __('Notification body must contain at least 3 characters.', 'csmm');
			}
			
			//if (strlen(trim($this->options['title'])) == 0) $errors[] = __('Page title can not be empty', 'csmm');
			//if (strlen(trim($this->options['subtitle'])) == 0) $errors[] = __('Page description can not be empty', 'csmm');
			//if (strlen(trim($this->options['copyright'])) == 0) $errors[] = __('Copyright message can not be empty', 'csmm');
			if (!empty($this->options['logo']) && !preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $this->options['logo'])){
				$errors[] = __('Logo URL must be a valid URL.', 'csmm');
			}
			if (!ctype_digit($this->options['logo_width'])) {
				$errors[] = __('Logo width must be a valid integer value.', 'csmm');
			}
			if (strlen($this->options['background_color']) == 0 || $this->get_rgb($this->options['background_color']) === false) $errors[] = __('Background color must be a valid value.', 'csmm');
			if (strlen($this->options['text_color']) == 0 || $this->get_rgb($this->options['text_color']) === false) $errors[] = __('Text color must be a valid value.', 'csmm');
			if (strlen($this->options['text_shadow_color']) != 0 && $this->get_rgb($this->options['text_shadow_color']) === false) $errors[] = __('Text shadow color must be a valid value.', 'csmm');
			if (strlen($this->options['social_color']) == 0 || $this->get_rgb($this->options['social_color']) === false) $errors[] = __('Social icons color must be a valid value.', 'csmm');
			if (strlen($this->options['social_hover_color']) == 0 || $this->get_rgb($this->options['social_hover_color']) === false) $errors[] = __('Social icons hover color must be a valid value.', 'csmm');
			if (strlen($this->options['social_shadow_color']) != 0 && $this->get_rgb($this->options['social_shadow_color']) === false) $errors[] = __('Social icons shadow color must be a valid value.', 'csmm');
			if (strlen($this->options['input_background_color']) == 0 || $this->get_rgb($this->options['input_background_color']) === false) $errors[] = __('Background color of the input field must be a valid value.', 'csmm');
			if (strlen($this->options['input_text_color']) == 0 || $this->get_rgb($this->options['input_text_color']) === false) $errors[] = __('Text color of the input field must be a valid value.', 'csmm');
			if (strlen($this->options['input_text_shadow_color']) != 0 && $this->get_rgb($this->options['input_text_shadow_color']) === false) $errors[] = __('Text shadow color of the input field must be a valid value.', 'csmm');
			if (floatval($this->options['input_background_opacity']) < 0 || floatval($this->options['input_background_opacity']) > 1) $errors[] = __('Opacity of the input field must be in a range [0...1].', 'csmm');
			if (strlen($this->options['button_background_color']) == 0 || $this->get_rgb($this->options['button_background_color']) === false) $errors[] = __('Background color of the button must be a valid value.', 'csmm');
			if (strlen($this->options['button_text_color']) == 0 || $this->get_rgb($this->options['button_text_color']) === false) $errors[] = __('Text color of the button must be a valid value.', 'csmm');
			if (strlen($this->options['button_text_shadow_color']) != 0 && $this->get_rgb($this->options['button_text_shadow_color']) === false) $errors[] = __('Text shadow color of the button must be a valid value.', 'csmm');
			if (floatval($this->options['button_background_opacity']) < 0 || floatval($this->options['button_background_opacity']) > 1) $errors[] = __('Opacity of the button must be in a range [0...1].', 'csmm');
			if (strlen($this->options['input_placeholder']) == 0) $errors[] = __('Input placeholder can not be empty.', 'csmm');
			if (strlen($this->options['button_label']) == 0) $errors[] = __('Button label can not be empty.', 'csmm');
			if (strlen($this->options['optin_confirmation']) == 0) $errors[] = __('Confirmation message can not be empty.', 'csmm');
			if (strlen($this->options['digit_background_color']) == 0 || $this->get_rgb($this->options['digit_background_color']) === false) $errors[] = __('Background color of the digit must be a valid value.', 'csmm');
			if (strlen($this->options['digit_text_color']) == 0 || $this->get_rgb($this->options['digit_text_color']) === false) $errors[] = __('Text color of the digit must be a valid value.', 'csmm');
			if (strlen($this->options['digit_text_shadow_color']) != 0 && $this->get_rgb($this->options['digit_text_shadow_color']) === false) $errors[] = __('Text shadow color of the digit must be a valid value.', 'csmm');
			if (floatval($this->options['digit_background_opacity']) < 0 || floatval($this->options['digit_background_opacity']) > 1) $errors[] = __('Opacity of the digit box must be in a range [0...1].', 'csmm');
			
			if (strlen($this->options['title_font_size']) == 0 || $this->options['title_font_size'] != preg_replace('/[^0-9]/', '', $this->options['title_font_size'])) $errors[] = __('Invalid font size of title.', 'csmm');
			if (strlen($this->options['subtitle_font_size']) == 0 || $this->options['subtitle_font_size'] != preg_replace('/[^0-9]/', '', $this->options['subtitle_font_size'])) $errors[] = __('Invalid font size of short description.', 'csmm');
			if (strlen($this->options['copyright_font_size']) == 0 || $this->options['copyright_font_size'] != preg_replace('/[^0-9]/', '', $this->options['copyright_font_size'])) $errors[] = __('Invalid font size of copyright message.', 'csmm');
			if (strlen($this->options['optin_font_size']) == 0 || $this->options['optin_font_size'] != preg_replace('/[^0-9]/', '', $this->options['optin_font_size'])) $errors[] = __('Invalid font size of opt-in form.', 'csmm');
			if (strlen($this->options['optin_message_font_size']) == 0 || $this->options['optin_message_font_size'] != preg_replace('/[^0-9]/', '', $this->options['optin_message_font_size'])) $errors[] = __('Invalid font size of opt-in form message.', 'csmm');
			if (strlen($this->options['digit_font_size']) == 0 || $this->options['digit_font_size'] != preg_replace('/[^0-9]/', '', $this->options['digit_font_size'])) $errors[] = __('Invalid font size of short description.', 'csmm');
			if (strlen($this->options['social_font_size']) == 0 || $this->options['social_font_size'] != preg_replace('/[^0-9]/', '', $this->options['social_font_size'])) $errors[] = __('Invalid social link size.', 'csmm');
			
			if (!empty($this->options['background_urls']) && is_array($this->options['background_urls'])) {
				$url_error = false;
				foreach ($this->options['background_urls'] as $url) {
					if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) $url_error = true;
				}
				if ($url_error) $errors[] = __('Background image URL must be a valid URL.', 'csmm');
			}
			if (!ctype_digit($this->options['background_duration'])) {
				$errors[] = __('Slide duration must be a valid integer value.', 'csmm');
			} else if (intval($this->options['background_duration']) < 2) {
				$errors[] = __('Slide duration must be 2 seconds or longer.', 'csmm');
			}
			$time = mktime($this->options['expiration_time_hours'], $this->options['expiration_time_minutes'], "00", substr($this->options['expiration_date'],5,2), substr($this->options['expiration_date'],8,2), substr($this->options['expiration_date'],0,4));
			if ($time == false || $time < 1) $errors[] = __('Invalid expiration date/time.', 'csmm');
			else {
				$this->options['expiration_time_hours'] = date('G', $time);
				$this->options['expiration_time_minutes'] = date('i', $time);
				$this->options['expiration_date'] = date('Y-m-d', $time);
			}
			if (!empty($this->options['social_links']) && is_array($this->options['social_links'])) {
				$url_error = false;
				foreach ($this->options['social_links'] as $url) {
					if (strtolower(substr($url, 0, strlen('mailto:'))) == 'mailto:') continue;
					else if (substr($url, 0, 1) == '#') continue;
					else if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) $url_error = true;
				}
				if ($url_error) $errors[] = __('Social link must be valid URL', 'csmm');
			}
			if ($this->options['mailchimp_enable'] == 'on') {
				if (empty($this->options['mailchimp_api_key']) || strpos($this->options['mailchimp_api_key'], '-') === false) $errors[] = __('Invalid MailChimp API Key.', 'csmm');
				if (empty($this->options['mailchimp_list_id'])) $errors[] = __('Invalid MailChimp List ID.', 'csmm');
			}
			if ($this->options['icontact_enable'] == 'on') {
				if (empty($this->options['icontact_appid'])) $errors[] = __('Invalid iContact App ID.', 'csmm');
				if (empty($this->options['icontact_apiusername'])) $errors[] = __('Invalid iContact API Username.', 'csmm');
				if (empty($this->options['icontact_apipassword'])) $errors[] = __('Invalid iContact API Password.', 'csmm');
				if (empty($this->options['icontact_listid'])) $errors[] = __('Invalid iContact List ID.', 'csmm');
			}
			if ($this->options['campaignmonitor_enable'] == 'on') {
				if (empty($this->options['campaignmonitor_api_key'])) $errors[] = __('Invalid Campaign Monitor API Key.', 'csmm');
				if (empty($this->options['campaignmonitor_list_id'])) $errors[] = __('Invalid Campaign Monitor List ID.', 'csmm');
			}
			if ($this->options['getresponse_enable'] == 'on') {
				if (empty($this->options['getresponse_api_key'])) $errors[] = __('Invalid GetResponse API Key.', 'csmm');
				if (empty($this->options['getresponse_campaign_id'])) $errors[] = __('Invalid GetResponse Campaign ID.', 'csmm');
			}
			if ($this->options['aweber_enable'] == 'on') {
				if (empty($this->options['aweber_access_secret'])) $errors[] = __('Invalid AWeber Connection.', 'csmm');
				else if (empty($this->options['aweber_listid'])) $errors[] = __('Invalid AWeber List ID.', 'csmm');
			}
			if ($this->options['mymail_enable'] == 'on') {
				if (empty($this->options['mymail_listid'])) $errors[] = __('Invalid MyMail List ID.', 'csmm');
			}
			if ($this->options['gdpr_enable'] == 'on') {
				if (empty($this->options['gdpr_title'])) $errors[] = __('GDPR checkbox label can not be empty.', 'csmm');
			}
			
			if (!empty($errors)) {
				$return_object = array();
				$return_object['status'] = 'ERROR';
				$return_object['message'] = __('Attention! Please correct errors and try again.', 'csmm').'<ul><li>'.implode('</li><li>', $errors).'</li></ul>';
				echo json_encode($return_object);
				exit;
			}
			$this->update_options();
			$return_object = array();
			$return_object['status'] = 'OK';
			$return_object['message'] = __('Settings successfully <strong>saved</strong>.', 'csmm');
			echo json_encode($return_object);
			exit;
		}
	}
	
	
	function aweber_connect() {
		global $wpdb;
		if (current_user_can('manage_options')) {
			if (!isset($_POST['csmm_aweber_oauth_id']) || empty($_POST['csmm_aweber_oauth_id'])) {
				$return_object = array();
				$return_object['status'] = 'ERROR';
				$return_object['message'] = __('Authorization Code not found.', 'csmm');
				echo json_encode($return_object);
				exit;
			}
			$code = trim(stripslashes($_POST['csmm_aweber_oauth_id']));
			if (!class_exists('AWeberAPI')) {
				require_once(dirname(__FILE__).'/aweber_api/aweber_api.php');
			}
			$account = null;
			try {
				list($consumer_key, $consumer_secret, $access_key, $access_secret) = AWeberAPI::getDataFromAweberID($code);
			} catch (AWeberAPIException $exc) {
				list($consumer_key, $consumer_secret, $access_key, $access_secret) = null;
			} catch (AWeberOAuthDataMissing $exc) {
				list($consumer_key, $consumer_secret, $access_key, $access_secret) = null;
			} catch (AWeberException $exc) {
				list($consumer_key, $consumer_secret, $access_key, $access_secret) = null;
			}
			if (!$access_secret) {
				$return_object = array();
				$return_object['status'] = 'ERROR';
				$return_object['message'] = __('Invalid Authorization Code!', 'csmm');
				echo json_encode($return_object);
				exit;
			} else {
				try {
					$aweber = new AWeberAPI($consumer_key, $consumer_secret);
					$account = $aweber->getAccount($access_key, $access_secret);
				} catch (AWeberException $e) {
					$return_object = array();
					$return_object['status'] = 'ERROR';
					$return_object['message'] = __('Can not access AWeber account!', 'csmm');
					echo json_encode($return_object);
					exit;
				}
			}
			$this->options['aweber_consumer_key'] = $consumer_key;
			$this->options['aweber_consumer_secret'] = $consumer_secret;
			$this->options['aweber_access_key'] = $access_key;
			$this->options['aweber_access_secret'] = $access_secret;
			$this->update_options();
			$return_object = array();
			$return_object['status'] = 'OK';
			$return_object['html'] = '
										<tr>
											<th>'.__('Connected', 'csmm').':</th>
											<td>
												<a class="csmm-button csmm-button-small" onclick="return csmm_aweber_disconnect(this);"><i class="csmm-fa csmm-fa-ok"></i><label>Disconnect</label></a>
												<br /><em>'.__('Click the button to disconnect.', 'csmm').'</em>
											</td>
										</tr>
										<tr>
											<th>'.__('List ID', 'csmm').':</th>
											<td>
												<select name="csmm_aweber_listid" style="width: 40%;">
													<option value="">'.__('--- Select List ID ---', 'csmm').'</option>';
				$lists = $account->lists;
				foreach ($lists as $list) {
					$return_object['html'] .= '
													<option value="'.$list->id.'"'.($list->id == $this->options['aweber_listid'] ? ' selected="selected"' : '').'>'.$list->name.'</option>';
				}
				$return_object['html'] .= '
												</select>
												<br /><em>'.__('Select your List ID.', 'csmm').'</em>
											</td>
										</tr>';
			echo json_encode($return_object);
			exit;
		}
		exit;
	}
	
	function aweber_disconnect() {
		global $wpdb;
		if (current_user_can('manage_options')) {
			$this->options['aweber_consumer_key'] = '';
			$this->options['aweber_consumer_secret'] = '';
			$this->options['aweber_access_key'] = '';
			$this->options['aweber_access_secret'] = '';
			$this->update_options();
			$return_object = array();
			$return_object['status'] = 'OK';
			$return_object['html'] = '
					<tr>
						<th>'.__('Authorization code', 'csmm').':</th>
						<td>
							<input type="text" id="csmm_aweber_oauth_id" value="" class="widefat csmm-input" placeholder="AWeber authorization code">
							<br />Get your authorization code <a target="_blank" href="https://auth.aweber.com/1.0/oauth/authorize_app/'.CSMM_AWEBER_APPID.'">'.__('here', 'csmm').'</a>.
						</td>
					</tr>
					<tr>
						<th></th>
						<td style="vertical-align: middle;">
							<a class="csmm-button csmm-button-small" onclick="return csmm_aweber_connect(this);"><i class="csmm-fa csmm-fa-ok"></i><label>Connect</label></a>
						</td>
					</tr>';
			echo json_encode($return_object);
			exit;
		}
		exit;
	}

	function activate() {
		if (current_user_can('manage_options')) {
			if ($_POST['mode'] == 1) {
				$this->options["active"] = 'on';
			} else {
				$this->options["active"] = 'off';
			}
			$this->update_options();
			$return_object = array();
			$return_object['status'] = 'OK';
			echo json_encode($return_object);
			exit;
		}
		exit;
	}

	function admin_users() {
		global $wpdb;

		if (isset($_GET["s"])) $search_query = trim(stripslashes($_GET["s"]));
		else $search_query = "";
		
		$tmp = $wpdb->get_row("SELECT COUNT(*) AS total FROM ".$wpdb->prefix."csmm_users WHERE deleted = '0'".((strlen($search_query) > 0) ? " AND email LIKE '%".addslashes($search_query)."%'" : ""), ARRAY_A);
		$total = $tmp["total"];
		$totalpages = ceil($total/CSMM_RECORDS_PER_PAGE);
		if ($totalpages == 0) $totalpages = 1;
		if (isset($_GET["p"])) $page = intval($_GET["p"]);
		else $page = 1;
		if ($page < 1 || $page > $totalpages) $page = 1;
		$switcher = $this->page_switcher(admin_url('admin.php').'?page=csmm-users'.((strlen($search_query) > 0) ? "&s=".rawurlencode($search_query) : ""), $page, $totalpages);

		$sql = "SELECT * FROM ".$wpdb->prefix."csmm_users WHERE deleted = '0'".((strlen($search_query) > 0) ? " AND email LIKE '%".addslashes($search_query)."%'" : "")." ORDER BY registered DESC LIMIT ".(($page-1)*CSMM_RECORDS_PER_PAGE).", ".CSMM_RECORDS_PER_PAGE;
		$rows = $wpdb->get_results($sql, ARRAY_A);
		
		if (!empty($this->error)) $message = "<div class='error'><p>".$this->error."</p></div>";
		else if (!empty($this->info)) $message = "<div class='updated'><p>".$this->info."</p></div>";
		else $message = "";

		echo '
			<div class="wrap admin_csmm_wrap">
				<h2>'.__('Coming Soon - Log', 'csmm').'</h2>
				'.$message.'
				<form class="csmm-filter-form" action="'.admin_url('admin.php').'" method="get" style="margin-bottom: 10px;">
				<input type="hidden" name="page" value="csmm-users" />
				'.__('Search', 'csmm').': <input type="text" name="s" value="'.htmlspecialchars($search_query, ENT_QUOTES).'">
				<input type="submit" class="button-secondary action" value="'.__('Search', 'csmm').'" />
				'.((strlen($search_query) > 0) ? '<input type="button" class="button-secondary action" value="'.__('Reset search results', 'csmm').'" onclick="window.location.href=\''.admin_url('admin.php').'?page=csmm-users\';" />' : '').'
				</form>
				<div class="csmm_buttons"><a class="button" href="'.admin_url('admin.php').'?action=csmm-csv">'.__('CSV Export', 'csmm').'</a></div>
				<div class="csmm_pageswitcher">'.$switcher.'</div>
				<table class="csmm_users">
				<tr>
					<th>'.__('E-mail', 'csmm').'</th>
					<th style="width: 120px;">'.__('Registered', 'csmm').'</th>
					<th style="width: 25px;"></th>
				</tr>';
		if (sizeof($rows) > 0) {
			foreach ($rows as $row) {
				echo '
				<tr>
					<td>'.esc_html($row['email']).'</td>
					<td>'.date("Y-m-d H:i", $row['registered']).'</td>
					<td style="text-align: center;">
						<a href="'.admin_url('admin.php').'?action=csmm-delete&id='.$row['id'].'" title="'.__('Delete record', 'csmm').'" onclick="return csmm_submitOperation();"><img src="'.plugins_url('/images/delete.png', __FILE__).'" alt="'.__('Delete record', 'csmm').'" border="0"></a>
					</td>
				</tr>';
			}
		} else {
			echo '
				<tr><td colspan="3" style="padding: 20px; text-align: center;">'.((strlen($search_query) > 0) ? __('No results found for', 'csmm').' "<strong>'.htmlspecialchars($search_query, ENT_QUOTES).'</strong>"' : __('List is empty.', 'csmm')).'</td></tr>';
		}
		echo '
				</table>
				<div class="csmm_buttons">
					<a class="button" href="'.admin_url('admin.php').'?action=csmm-deleteall" onclick="return csmm_submitOperation();">'.__('Delete All', 'csmm').'</a>
					<a class="button" href="'.admin_url('admin.php').'?action=csmm-csv">'.__('CSV Export', 'csmm').'</a>
				</div>
				<div class="csmm_pageswitcher">'.$switcher.'</div>
				<div class="csmm_legend">
				<strong>'.__('Legend:', 'csmm').'</strong>
					<p><img src="'.plugins_url('/images/delete.png', __FILE__).'" alt="'.__('Delete record', 'csmm').'" border="0"> '.__('Delete record', 'csmm').'</p>
				</div>
			</div>
			<script type="text/javascript">
				function csmm_submitOperation() {
					var answer = confirm("'.__('Do you really want to continue?', 'csmm').'")
					if (answer) return true;
					else return false;
				}
			</script>';
	}

	function admin_request_handler() {
		global $wpdb;
		if (isset($_GET['action'])) {
			switch($_GET['action']) {
				case 'csmm-delete':
					$id = intval($_GET["id"]);
					$user_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."csmm_users WHERE id = '".$id."' AND deleted = '0'", ARRAY_A);
					if (intval($user_details["id"]) == 0) {
						header('Location: '.admin_url('admin.php').'?page=csmm-users');
						die();
					}
					$sql = "UPDATE ".$wpdb->prefix."csmm_users SET deleted = '1' WHERE id = '".$id."'";
					if ($wpdb->query($sql) !== false) {
						setcookie("scmm_info", __('Record successfully <strong>deleted</strong>.', 'scmm'), time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header('Location: '.admin_url('admin.php').'?page=csmm-users');
					} else {
						setcookie("scmm_error", __('Service is <strong>not available</strong>.', 'scmm'), time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header('Location: '.admin_url('admin.php').'?page=csmm-users');
					}
					die();
					break;
				case 'csmm-csv':
					$rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."csmm_users WHERE deleted = '0' ORDER BY registered DESC", ARRAY_A);
					if (sizeof($rows) > 0) {
						if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE")) {
							header("Pragma: public");
							header("Expires: 0");
							header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
							header("Content-type: application-download");
							header("Content-Disposition: attachment; filename=\"emails.csv\"");
							header("Content-Transfer-Encoding: binary");
						} else {
							header("Content-type: application-download");
							header("Content-Disposition: attachment; filename=\"emails.csv\"");
						}
						$separator = $this->options['csv_separator'];
						if ($separator == 'tab') $separator = "\t";
						echo 'E-Mail'.$separator.'Registered'."\r\n";
						foreach ($rows as $row) {
							echo str_replace($separator, "", $row["email"]).$separator.date("Y-m-d H:i:s", $row["registered"])."\r\n";
						}
						die();
		            }
					setcookie("scmm_error", __('<strong>No records</strong> found.', 'scmm'), time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
		            header("Location: ".get_bloginfo('wpurl')."/wp-admin/admin.php?page=csmm-users");
					die();
					break;
				case 'csmm-deleteall':
					$sql = "UPDATE ".$wpdb->prefix."csmm_users SET deleted = '1'";
					if ($wpdb->query($sql) !== false) {
						setcookie("scmm_info", __('Record successfully <strong>deleted</strong>.', 'scmm'), time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header('Location: '.admin_url('admin.php').'?page=csmm-users');
					} else {
						setcookie("scmm_error", __('Service is <strong>not available</strong>.', 'scmm'), time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header('Location: '.admin_url('admin.php').'?page=csmm-users');
					}
					die();
					break;
				case 'csmm-getresponse-campaigns':
					if (isset($_GET["key"]) && !empty($_GET["key"])) {
						$key = $_GET["key"];
						$request = json_encode(
							array(
								'method' => 'get_campaigns',
								'params' => array(
									$key
								),
								'id' => ''
							)
						);

						$curl = curl_init('https://api2.getresponse.com/');
						curl_setopt($curl, CURLOPT_POST, 1);
						curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
						$header = array(
							'Content-Type: application/json',
							'Content-Length: '.strlen($request)
						);
						curl_setopt($curl, CURLOPT_PORT, 443);
						curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
						curl_setopt($curl, CURLOPT_TIMEOUT, 10);
						curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); // verify certificate
						curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // check existence of CN and verify that it matches hostname
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
						curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
						curl_setopt($curl, CURLOPT_HEADER, 0);
									
						$response = curl_exec($curl);
						
						if (curl_error($curl)) die('<div style="text-align: center; margin: 20px 0px;">'.__('API call failed.','csmm').'</div>');
						$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
						if ($httpCode != '200') die('<div style="text-align: center; margin: 20px 0px;">'.__('API call failed.','csmm').'</div>');
						curl_close($curl);
						
						$post = json_decode($response, true);
						if(!empty($post['error'])) die('<div style="text-align: center; margin: 20px 0px;">'.__('API Key failed','csmm').': '.$post['error']['message'].'</div>');
						
						if (!empty($post['result'])) {
							echo '
<html>
<head>
	<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
	<title>'.__('GetResponse Campaigns', 'csmm').'</title>
</head>
<body>
	<table style="width: 100%;">
		<tr>
			<td style="width: 170px; font-weight: bold;">'.__('Campaign ID', 'csmm').'</td>
			<td style="font-weight: bold;">'.__('Campaign Name', 'csmm').'</td>
		</tr>';
							foreach ($post['result'] as $key => $value) {
								echo '
		<tr>
			<td>'.esc_html($key).'</td>
			<td>'.esc_html(esc_html($value['name'])).'</td>
		</tr>';
							}
							echo '
	</table>						
</body>
</html>';
						} else echo '<div style="text-align: center; margin: 20px 0px;">'.__('No data found!', 'csmm').'</div>';
					} else echo '<div style="text-align: center; margin: 20px 0px;">'.__('No data found!', 'csmm').'</div>';
					die();
					break;
				case 'csmm-icontact-lists':
					if (isset($_GET["appid"]) && isset($_GET["user"]) && isset($_GET["pass"])) {
						$this->options['icontact_appid'] = $_GET["appid"];
						$this->options['icontact_apiusername'] = $_GET["user"];
						$this->options['icontact_apipassword'] = $_GET["pass"];
						
						$lists = $this->icontact_getlists();
						if (!empty($lists)) {
							echo '
<html>
<head>
	<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
	<title>'.__('GetResponse Campaigns', 'csmm').'</title>
</head>
<body>
	<table style="width: 100%;">
		<tr>
			<td style="width: 170px; font-weight: bold;">'.__('List ID', 'csmm').'</td>
			<td style="font-weight: bold;">'.__('List Title', 'csmm').'</td>
		</tr>';
							foreach ($lists as $key => $value) {
								echo '
		<tr>
			<td>'.esc_html($key).'</td>
			<td>'.esc_html(esc_html($value)).'</td>
		</tr>';
							}
							echo '
	</table>						
</body>
</html>';
						} else echo '<div style="text-align: center; margin: 20px 0px;">'.__('No data found!', 'csmm').'</div>';
					} else echo '<div style="text-align: center; margin: 20px 0px;">'.__('No data found!', 'csmm').'</div>';
					die();
					break;
				default:
					break;
			}
		}
	}

	function front_footer() {
		if ($this->options['active'] == 'on') {
			echo '
		<div style="position: fixed; z-index: 999999; left: 0; bottom: 0; line-height: 1.35; font-family: arial, verdana; font-size: 14px; color: white; background: transparent url('.plugins_url('images/45stripe_bg.png', __FILE__).') 0 0 repeat; padding: 5px 10px;">
		'.__('Website is in maintenance mode and visible only for administrators, whitelisted IPs and roles.', 'csmm').'<br />
		'.__('Others see it like that', 'csmm').': <a target="_blank" style="color: #80C0FF !important; text-decoration: none !important; " href="'.get_bloginfo('wpurl').'?csmm=true">'.get_bloginfo('wpurl').'?csmm=true</a>
		</div>';
		}
	}

	function front_wp() {
		global $current_user;
		$common_roles = array();
		if ($current_user) $common_roles = array_intersect($current_user->roles, $this->options['white_roles']);
		if ($this->options['active'] == 'on') {
			$white_str = str_replace(array("\r", "\n"), array("", " "), $this->options['white_ip']);
			$white_ip = explode(" ", $white_str);
			if ((!in_array($_SERVER['REMOTE_ADDR'], $white_ip) && !current_user_can('manage_options') && empty($common_roles)) || isset($_GET['csmm'])) {
				$time = mktime($this->options['expiration_time_hours'], $this->options['expiration_time_minutes'], "00", substr($this->options['expiration_date'],5,2), substr($this->options['expiration_date'],8,2), substr($this->options['expiration_date'],0,4));
				if ($time == false || $time < time()) $period = -1;
				$period = $time - time();
				if ($period <= 0) $this->options['expiration_enable'] = 'off';
				if ($this->options['expiration_enable'] == 'off') $period = -1;
				if ($this->options['send_503'] == 'on') {
					$protocol = "HTTP/1.0";
					if ("HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"]) $protocol = "HTTP/1.1";
					header($protocol.' 503 Service Unavailable', true, 503);
					header('Retry-After: 3600');
				}
				
				$gdpr_html = '';
				if ($this->options['gdpr_enable'] == 'on') {
					$checkbox_html = '<div class="checkbox"><input type="checkbox" id="gdpr" name="gdpr" value="off" onclick="jQuery(this).parent().removeClass(\'input-error\');"><label for="gdpr"></label></div>';
					preg_match("'{(.*?)}'si", $this->options['gdpr_title'], $match);
					$local_terms_title = '';
					if ($match) $local_terms_title = $match[1];
					if (strlen($local_terms_title) > 0) {
						$terms = esc_html($this->options['gdpr_terms']);
						$terms = str_replace("\n", "<br />", $terms);
						$terms = str_replace("\r", "", $terms);
						$gdpr_title = str_replace('{'.$local_terms_title.'}', '<a href="#" onclick="jQuery(this).parent().find(\'.terms-container\').slideToggle(300); return false;">'.$local_terms_title.'</a>', $this->options['gdpr_title']);
						$gdpr_html = '
							<tr class="gdpr-row">
								<td colspan="2">
									'.$checkbox_html.$gdpr_title.'
									<div class="terms-container hide">
										<div class="terms">'.$terms.'</div>
									</div>
								</td>
							</tr>';
					} else {
						$gdpr_html = '
							<tr class="gdpr-row">
								<td colspan="2">
								'.$checkbox_html.$this->options['gdpr_title'].'
								</td>
							</tr>';
					}
				}
				
				
				$input_rgb = $this->get_rgb($this->options['input_background_color']);
				$button_rgb = $this->get_rgb($this->options['button_background_color']);
				$digit_rgb = $this->get_rgb($this->options['digit_background_color']);
				if ($this->options['button_background_opacity'] < 0.4) $hover_opacity = $this->options['button_background_opacity'] + 0.1;
				else  $hover_opacity = $this->options['button_background_opacity'] - 0.1;
				echo '
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
	<title>'.(empty($this->options['title']) ? __('Coming Soon', 'csmm') : esc_html($this->options['title'])).'</title>
	<meta name="description" content="'.(empty($this->options['subtitle']) ? __('Coming Soon page', 'csmm') : esc_html($this->options['subtitle'])).'">
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300&subset=latin,cyrillic-ext,greek-ext,latin-ext,cyrillic,greek,vietnamese" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="'.plugins_url('css/style.css', __FILE__).'" type="text/css" />
	<link rel="stylesheet" href="'.plugins_url('css/monosocialicons.css', __FILE__).'" type="text/css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	'.(sizeof($this->options['background_urls']) > 0 ? '<script src="'.plugins_url('js/jquery.backstretch.min.js', __FILE__).'" type="text/javascript"></script>' : '').'
	<script src="'.plugins_url('js/script.js', __FILE__).'" type="text/javascript"></script>
	<style>
	body {background-color: '.$this->options['background_color'].';}
	.title h1, .title p, .copyright, .optin-box .gdpr-row, .optin-box .gdpr-row a {color: '.$this->options['text_color'].';'.(!empty($this->options['text_shadow_color']) ? ' text-shadow: '.$this->options['text_shadow_color'].' 0 1px 2px;' : '').'}
	.title h1 {font-size: '.$this->options['title_font_size'].'px;}
	.title p {font-size: '.$this->options['subtitle_font_size'].'px;}
	.copyright {font-size: '.$this->options['copyright_font_size'].'px;}
	.social-icons {font-size: '.$this->options['social_font_size'].'px;}
	.social-icons a, .social-icons a:visited {color: '.$this->options['social_color'].';'.(!empty($this->options['social_shadow_color']) ? ' text-shadow: '.$this->options['social_shadow_color'].' 0 1px 2px;' : '').'}
	.social-icons a:active, .social-icons a:hover {color: '.$this->options['social_hover_color'].';}
	.optin-box .gdpr-row {font-size: '.intval(0.9*intval($this->options['optin_font_size'])).'px;}
	.optin-box table td.input-cell i {font-size: '.$this->options['optin_font_size'].'px;  color: '.$this->options['input_text_color'].';'.(!empty($this->options['input_text_shadow_color']) ? ' text-shadow: '.$this->options['input_text_shadow_color'].' 0 1px 1px;' : '').'}
	.optin-box table td input, .checkbox {font-size: '.$this->options['optin_font_size'].'px; background-color: '.$this->options['input_background_color'].'; background-color: rgba('.$input_rgb['r'].','.$input_rgb['g'].','.$input_rgb['b'].','.$this->options['input_background_opacity'].'); color: '.$this->options['input_text_color'].';'.(!empty($this->options['input_text_shadow_color']) ? ' text-shadow: '.$this->options['input_text_shadow_color'].' 0 1px 1px;' : '').'}
	.checkbox label {background-color: '.$this->options['input_background_color'].'; background-color: rgba('.$input_rgb['r'].','.$input_rgb['g'].','.$input_rgb['b'].','.$this->options['input_background_opacity'].');}
	.optin-box table td a.button {font-size: '.$this->options['optin_font_size'].'px; background: '.$this->options['button_background_color'].'; background: rgba('.$button_rgb['r'].','.$button_rgb['g'].','.$button_rgb['b'].','.$this->options['button_background_opacity'].'); color: '.$this->options['button_text_color'].';'.(!empty($this->options['button_text_shadow_color']) ? ' text-shadow: '.$this->options['button_text_shadow_color'].' 0 1px 1px;' : '').'}
	.optin-box table td a.button:hover, .optin-box table td a.button:active {background: rgba('.$button_rgb['r'].','.$button_rgb['g'].','.$button_rgb['b'].','.$hover_opacity.');}
	.optin-box .message {font-size: '.$this->options['optin_message_font_size'].'px;'.(!empty($this->options['text_shadow_color']) ? ' text-shadow: '.$this->options['text_shadow_color'].' 0 1px 2px;' : '').'}
	.countdown-box {line-height: '.($this->options['digit_font_size']+22).'px;}
	.digit-box {font-size: '.$this->options['digit_font_size'].'px; background: '.$this->options['digit_background_color'].'; background: rgba('.$digit_rgb['r'].','.$digit_rgb['g'].','.$digit_rgb['b'].','.$this->options['digit_background_opacity'].'); color: '.$this->options['digit_text_color'].';'.(!empty($this->options['digit_text_shadow_color']) ? ' text-shadow: '.$this->options['digit_text_shadow_color'].' 0 1px 1px;' : '').'}
	.number-box {font-size: '.$this->options['digit_font_size'].'px;}
	.number-box span {font-size: '.min(20, max(14, intval($this->options['digit_font_size']/2.25))).'px;}
	</style>
	<script>
		var period = '.$period.';
		var action = "'.admin_url('admin-ajax.php').'";
	</script>
</head>
<body>
	<div class="front-container">
		'.(sizeof($this->options['background_urls']) < 2 ? '
		<!--<div class="front-bg">
			<img class="front-image" src="'.(empty($this->options['background_urls']) ? plugins_url('images/bg01.jpg', __FILE__) : $this->options['background_urls'][0]).'">
		</div>-->' : '').'
		<div class="front-content">
			'.(!empty($this->options['logo']) ? '
			<div class="logo">
				<img src="'.$this->options['logo'].'" alt="'.esc_html($this->options['title']).'" style="max-width: '.intval($this->options['logo_width']).'px;" />
			</div>' : '').'
			<div class="title">
				'.(empty($this->options['title']) ? '' : '<h1>'.esc_html($this->options['title']).'</h1>').'
				'.(empty($this->options['subtitle']) ? '' : '<p>'.esc_html($this->options['subtitle']).'</p>').'
			</div>';
				if ($this->options['expiration_enable'] == 'on') {
					echo '
			<div class="countdown-box">
				<div class="number-box">
					<div id="days-sh" class="digit-box">9</div><div id="days-h" class="digit-box">9</div><div id="days-l" class="digit-box digit-box-last">9</div>
					<span>'.esc_html($this->options['timer_days_label']).'</span>
				</div>
				<div class="number-box">:</div>
				<div class="number-box">
					<div id="hours-h" class="digit-box">9</div><div id="hours-l" class="digit-box digit-box-last">9</div>
					<span>'.esc_html($this->options['timer_hours_label']).'</span>
				</div>
				<div class="number-box">:</div>
				<div class="number-box">
					<div id="minutes-h" class="digit-box">9</div><div id="minutes-l" class="digit-box digit-box-last">9</div>
					<span>'.esc_html($this->options['timer_minutes_label']).'</span>
				</div>
				<div class="number-box">:</div>
				<div class="number-box">
					<div id="seconds-h" class="digit-box">9</div><div id="seconds-l" class="digit-box digit-box-last">9</div>
					<span>'.esc_html($this->options['timer_seconds_label']).'</span>
				</div>
			</div>';
				}
				if (sizeof($this->options['social_icons']) > 0) {
					echo '
			<div class="social-icons">';
					foreach($this->options['social_icons'] as $key => $value) {
						echo ' <a class="symbol" href="'.($this->options['social_links'][$key]).'">'.$this->social_icons[$value].'</a>';
					}
					echo '
			</div>';
				}
				if ($this->options['optin_enable'] == 'on') {
					echo '
			<div class="optin-box">
				<table>
					<tr class="input-row">
						<td class="input-cell"><input type="email" id="email" autocomplete="off" placeholder="'.esc_html($this->options['input_placeholder']).'" tabindex="1" onfocus="jQuery(this).parent().removeClass(\'input-error\');" title="'.__('Subscribe to get notified when we are ready.', 'csmm').'"><i class="csmm-fa csmm-fa-mail-alt"></i></td>
						<td class="button-cell"><input type="hidden" name="action" value="csmm_submit"><a id="button" class="button" tabindex="2" onclick="return subscribe();"><i class="csmm-fa csmm-fa-ok"></i>'.esc_html($this->options['button_label']).'</a></td>
					</tr>
					'.$gdpr_html.'
				</table>
				<div id="loading" class="loading"></div>
				<div id="message" class="message success"></div>
			</div>';
				}
				echo '
			<div class="copyright">
				'.esc_html($this->options['copyright']).'
			</div>
		</div>
	</div>';
				if (sizeof($this->options['background_urls']) > 0) {
					echo '
	<script>
	jQuery.backstretch(["'.implode('","', $this->options['background_urls']).'"], {duration: '.(1000*$this->options['background_duration']).', fade: 750});
	</script>';
				}
				echo '
</body>
</html>';
				exit;
			}
		}
	}

	function submit() {
		global $wpdb;
		if (isset($_REQUEST['callback'])) {
			header("Content-type: text/javascript");
			$jsonp_callback = $_REQUEST['callback'];
		} else exit;
		if (isset($_REQUEST['csmm-email']) && $this->options['active'] == 'on') {
			$errors = array();
			$email = base64_decode(trim(stripslashes($_REQUEST['csmm-email'])));
			if ($email == '' || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,19})$/i", $email)) {
				$errors['email'] = 'ERROR';
			}
			if ($this->options['gdpr_enable'] == 'on') {
				$gdpr = trim(stripslashes($_REQUEST['csmm-gdpr']));
				if ($gdpr != 'on') $errors['gdpr'] = 'ERROR';
			}
			if (!empty($errors)) {
				$return_object = $errors;
				$return_object['status'] = 'ERROR';
				echo $jsonp_callback.'('.json_encode($return_object).')';
				exit;
			}
			$name = substr($email, 0, strpos($email, '@'));
/*			$tmp = $wpdb->get_row("SELECT COUNT(*) AS total FROM ".$wpdb->prefix."csmm_users WHERE deleted = '0' AND email = '".esc_sql($email)."'", ARRAY_A);
			if ($tmp["total"] > 0) {
				$sql = "UPDATE ".$wpdb->prefix."csmm_users SET
					registered = '".time()."'
					WHERE deleted = '0' AND email = '".esc_sql($email)."'";
				$wpdb->query($sql);
			} else {*/
				$sql = "INSERT INTO ".$wpdb->prefix."csmm_users (
					email, registered, deleted) VALUES (
					'".esc_sql($email)."',
					'".time()."', '0'
				)";
				$wpdb->query($sql);
/*			}*/
			if ($this->options['mailchimp_enable'] == 'on') {
				$list_id = $this->options['mailchimp_list_id'];
				$dc = "us1";
				if (strstr($this->options['mailchimp_api_key'], "-")) {
					list($key, $dc) = explode("-", $this->options['mailchimp_api_key'], 2);
					if (!$dc) $dc = "us1";
				}
				$mailchimp_url = 'https://'.$dc.'.api.mailchimp.com/1.3/?method=listSubscribe&apikey='.$this->options['mailchimp_api_key'].'&id='.$list_id.'&email_address='.urlencode($email).'&merge_vars[FNAME]='.urlencode($name).'&merge_vars[LNAME]='.urlencode($name).'&merge_vars[NAME]='.urlencode($name).'&merge_vars[OPTIN_IP]='.$_SERVER['REMOTE_ADDR'].'&output=php&double_optin='.($this->options['mailchimp_double'] == 'on' ? '1' : '0').'&send_welcome='.($this->options['mailchimp_welcome'] == 'on' ? '1' : '0');

				$ch = curl_init($mailchimp_url);
				curl_setopt($ch, CURLOPT_URL, $mailchimp_url);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_TIMEOUT, 120);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
				//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: MCAPI/1.3');
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				curl_setopt($ch, CURLOPT_AUTOREFERER, true);
				$data = curl_exec( $ch );
				curl_close( $ch );
			}
			if ($this->options['icontact_enable'] == 'on') {
				$this->icontact_addcontact($name, $email);
			}
			if ($this->options['campaignmonitor_enable'] == 'on') {
				$options['EmailAddress'] = $email;
				$options['Name'] = $name;
				$options['Resubscribe'] = 'true';
				$options['RestartSubscriptionBasedAutoresponders'] = 'true';
				$post = json_encode($options);
				
				$curl = curl_init('http://api.createsend.com/api/v3/subscribers/'.urlencode($this->options['campaignmonitor_list_id']).'.json');
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
					
				$header = array(
					'Content-Type: application/json',
					'Content-Length: '.strlen($post),
					'Authorization: Basic '.base64_encode($this->options['campaignmonitor_api_key'])
					);

				//curl_setopt($curl, CURLOPT_PORT, 443);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
				curl_setopt($curl, CURLOPT_TIMEOUT, 10);
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
				//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); // verify certificate
				//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // check existence of CN and verify that it matches hostname
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
						
				$response = curl_exec($curl);
				curl_close($curl);
			}
			if ($this->options['getresponse_enable'] == 'on') {
				$request = json_encode(
					array(
						'method' => 'add_contact',
						'params' => array(
							$this->options['getresponse_api_key'],
							array(
								'campaign' => $this->options['getresponse_campaign_id'],
								'action' => 'standard',
								'name' => $name,
								'email' => $email,
								'cycle_day' => 0,
								'ip' => $_SERVER['REMOTE_ADDR']
							)
						),
						'id' => ''
					)
				);

				$curl = curl_init('http://api2.getresponse.com/');
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
							
				$header = array(
					'Content-Type: application/json',
					'Content-Length: '.strlen($request)
				);

				//curl_setopt($curl, CURLOPT_PORT, 443);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
				curl_setopt($curl, CURLOPT_TIMEOUT, 10);
				//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); // verify certificate
				//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // check existence of CN and verify that it matches hostname
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($curl, CURLOPT_HEADER, 0);
								
				$response = curl_exec($curl);
				curl_close($curl);
			}
			if ($this->options['aweber_access_secret']) {
				if ($this->options['aweber_enable'] == 'on') {
					$account = null;
					if (!class_exists('AWeberAPI')) {
						require_once(dirname(__FILE__).'/aweber_api/aweber_api.php');
					}
					try {
						$aweber = new AWeberAPI($this->options['aweber_consumer_key'], $this->options['aweber_consumer_secret']);
						$account = $aweber->getAccount($this->options['aweber_access_key'], $this->options['aweber_access_secret']);
						$subscribers = $account->loadFromUrl('/accounts/' . $account->id . '/lists/' . $this->options['aweber_listid'] . '/subscribers');
						$subscribers->create(array(
							'email' => $email,
							'ip_address' => $_SERVER['REMOTE_ADDR'],
							'name' => $name,
							'ad_tracking' => 'Opt-In Panel',
						));
					} catch (Exception $e) {
						$account = null;
					}
				}
			}
			if (function_exists('mymail_subscribe') || function_exists('mymail')) {
				if ($this->options['mymail_enable'] == 'on') {
					if (function_exists('mymail')) {
						$list = mymail('lists')->get($this->options['mymail_listid']);
					} else {
						$list = get_term_by('id', $this->options['mymail_listid'], 'newsletter_lists');
					}
					if (!empty($list)) {
						try {
							if ($this->options['mymail_double'] == "on") $double = true;
							else $double = false;
							if (function_exists('mymail')) {
								$entry = array(
									'firstname' => $name,
									'email' => $email,
									'status' => $double ? 0 : 1,
									'ip' => $_SERVER['REMOTE_ADDR'],
									'signup_ip' => $_SERVER['REMOTE_ADDR'],
									'referer' => $_SERVER['HTTP_REFERER'],
									'signup' =>time()
								);
								$subscriber_id = mymail('subscribers')->add($entry, true);
								if (is_wp_error( $subscriber_id )) return;
								$result = mymail('subscribers')->assign_lists($subscriber_id, array($list->ID));
							} else {
								$result = mymail_subscribe($email, array('firstname' => $name), array($term->slug), $double);
							}
						} catch (Exception $e) {
						}
					}
				}
			}

			if ($this->options['thanksgiving_enable'] == 'on') {
				$tags = array("{e-mail}", "{email}");
				$vals = array($email, $email);
				$body = str_replace($tags, $vals, $this->options['thanksgiving_email_body']);
				if (strpos(strtolower($body), '<html') === false) $body = str_replace(array("\n", "\r"), array("<br />", ""), $body);
				$mail_headers = "Content-Type: text/html; charset=utf-8\r\n";
				$mail_headers .= "From: ".$this->options['from_name']." <".$this->options['from_email'].">\r\n";
				$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
				wp_mail($email, $this->options['thanksgiving_email_subject'], $body, $mail_headers);
			}

			if ($this->options['mail_enable'] == 'on') {
				$tags = array("{e-mail}", "{email}", "{ip}", "{source}", "{user-agent}");
				$vals = array($email, $email, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);
				$body = str_replace($tags, $vals, $this->options['mail_message']);
				if (strpos(strtolower($body), '<html') === false) $body = str_replace(array("\n", "\r"), array("<br />", ""), $body);
				$mail_headers = "Content-Type: text/html; charset=utf-8\r\n";
				$mail_headers .= "Reply-To: ".(empty($name) ? esc_html($email) : esc_html($name))." <".esc_html($email).">\r\n";
				if ($this->options['mail_from'] == 'on') {
					$mail_headers .= "From: ".(empty($name) ? esc_html($email) : esc_html($name))." <".esc_html($email).">\r\n";
				} else {
					$mail_headers .= "From: ".(empty($this->options['from_name']) ? esc_html($this->options['from_email']) : esc_html($this->options['from_name']))." <".esc_html($this->options['from_email']).">\r\n";
				}
				$mail_emails = explode(',', $this->options['mail_email']);
				foreach ($mail_emails as $mail_email) {
					$mail_email = trim($mail_email);
					if (!empty($mail_email)) {
						if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,19})$/i", $mail_email)) {
							wp_mail($mail_email, $this->options['mail_subject'], $body, $mail_headers);					
						}
					}
				}
			}

			$return_object = array();
			$return_object['message'] = $this->options['optin_confirmation'];
			$return_object['status'] = 'OK';
			echo $jsonp_callback.'('.json_encode($return_object).')';
			exit;
		} else {
			$return_object = array();
			$return_object['message'] = __('Subscription temporarily closed.', 'csmm');
			$return_object['status'] = 'ERROR';
			echo $jsonp_callback.'('.json_encode($return_object).')';
			exit;
		}
		exit;
	}

	function icontact_getlists() {
		$data = $this->icontact_makecall($this->options['icontact_appid'], $this->options['icontact_apiusername'], $this->options['icontact_apipassword'], '/a/', null, 'accounts');
		if (!empty($data['errors'])) return array();
		$account = $data['response'][0];
		if (empty($account) || intval($account->enabled != 1)) return;
		$data = $this->icontact_makecall($this->options['icontact_appid'], $this->options['icontact_apiusername'], $this->options['icontact_apipassword'], '/a/'.$account->accountId.'/c/', null, 'clientfolders');
		if (!empty($data['errors'])) return array();
		$client = $data['response'][0];
		if (empty($client)) return array();
		$data = $this->icontact_makecall($this->options['icontact_appid'], $this->options['icontact_apiusername'], $this->options['icontact_apipassword'], '/a/'.$account->accountId.'/c/'.$client->clientFolderId.'/lists', array(), 'lists');
		if (!empty($data['errors'])) return array();
		if (!is_array($data['response'])) return array();
		$lists = array();
		foreach ($data['response'] as $list) {
			$lists[$list->listId] = $list->name;
		}
		return $lists;
	}

	function icontact_addcontact($name, $email) {
		$data = $this->icontact_makecall($this->options['icontact_appid'], $this->options['icontact_apiusername'], $this->options['icontact_apipassword'], '/a/', null, 'accounts');
		if (!empty($data['errors'])) return;
		$account = $data['response'][0];
		if (empty($account) || intval($account->enabled != 1)) return;
		$data = $this->icontact_makecall($this->options['icontact_appid'], $this->options['icontact_apiusername'], $this->options['icontact_apipassword'], '/a/'.$account->accountId.'/c/', null, 'clientfolders');
		if (!empty($data['errors'])) return;
		$client = $data['response'][0];
		if (empty($client)) return;
		$contact['email'] = $email;
		$contact['firstName'] = $name;
		$contact['status'] = 'normal';
		$data = $this->icontact_makecall($this->options['icontact_appid'], $this->options['icontact_apiusername'], $this->options['icontact_apipassword'], '/a/'.$account->accountId.'/c/'.$client->clientFolderId.'/contacts', array($contact), 'contacts');
		if (!empty($data['errors'])) return;
		$contact = $data['response'][0];
		if (empty($contact)) return;
		$subscriber['contactId'] = $contact->contactId;
		$subscriber['listId'] = $this->options['icontact_listid'];
		$subscriber['status'] = 'normal';
		$data = $this->icontact_makecall($this->options['icontact_appid'], $this->options['icontact_apiusername'], $this->options['icontact_apipassword'], '/a/'.$account->accountId.'/c/'.$client->clientFolderId.'/subscriptions', array($subscriber), 'subscriptions');
	}

	function icontact_makecall($appid, $apiusername, $apipassword, $resource, $postdata = null, $returnkey = null) {
		$return = array();
		$url = "https://app.icontact.com/icp".$resource;
		$headers = array(
			'Except:', 
			'Accept:  application/json', 
			'Content-type:  application/json', 
			'Api-Version:  2.2',
			'Api-AppId:  '.$appid, 
			'Api-Username:  '.$apiusername, 
			'Api-Password:  '.$apipassword
		);
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		if (!empty($postdata)) {
			curl_setopt($handle, CURLOPT_POST, true);
			curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($postdata));
		}
		curl_setopt($handle, CURLOPT_URL, $url);
		if (!$response_json = curl_exec($handle)) {
			$return['errors'][] = __('Unable to execute the cURL handle.', 'csmm');
		}
		if (!$response = json_decode($response_json)) {
			$return['errors'][] = __('The iContact API did not return valid JSON.', 'csmm');
		}
		curl_close($handle);
		if (!empty($response->errors)) {
			foreach ($response->errors as $error) {
				$return['errors'][] = $error;
			}
		}
		if (!empty($return['errors'])) return $return;
		if (empty($returnkey)) {
			$return['response'] = $response;
		} else {
			$return['response'] = $response->$returnkey;
		}
		return $return;
	}

	function personal_data_exporters($_exporters) {
		$_exporters['csmm'] = array(
			'exporter_friendly_name' => __('Coming Soon', 'opd'),
			'callback' => array(&$this, 'personal_data_exporter')
		);
		return $_exporters;
	}
	
	function personal_data_exporter($_email_address, $_page = 1) {
		global $wpdb;
		if (empty($_email_address)) {
			return array(
				'data' => array(),
				'done' => true
			);
		}
		$data_to_export = array();
		$rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."csmm_users WHERE email = '".esc_sql($_email_address)."' ORDER BY registered DESC", ARRAY_A);
		foreach ($rows as $row) {
			$data = array(
				'group_id' => 'csmm-log',
				'group_label' => __('Coming Soon', 'csmm'),
				'item_id' => 'csmm-log-'.$row['id']
			);
			if (!empty($row['email'])) $data['data'][] = array('name' => __('Email', 'csmm'), 'value' => $row['email']);
			$data['data'][] = array('name' => __('Created', 'csmm'), 'value' => date("Y-m-d H:i", $row['registered']));
			if ($row['deleted'] != 0) $data['data'][] = array('name' => __('Deleted', 'csmm'), 'value' => 'yes');
			$data_to_export[] = $data;
		}
		return array(
			'data' => $data_to_export,
			'done' => true
		);
	}
	
	function personal_data_erasers($_erasers) {
		$_erasers['csmm'] = array(
			'eraser_friendly_name' => __('Coming Soon', 'csmm'),
			'callback' => array(&$this, 'personal_data_eraser')
		);
		return $_erasers;
	}

	function personal_data_eraser($_email_address, $_page = 1) {
		global $wpdb;
		if (empty($_email_address)) {
			return array(
				'items_removed'  => false,
				'items_retained' => false,
				'messages'       => array(),
				'done'           => true,
			);
		}
		$tmp = $wpdb->get_row("SELECT COUNT(*) AS total FROM ".$wpdb->prefix."csmm_users WHERE email = '".esc_sql($_email_address)."'", ARRAY_A);
		$total = $tmp["total"];
		$wpdb->query("DELETE FROM ".$wpdb->prefix."csmm_users WHERE email = '".esc_sql($_email_address)."'");
		return array(
			'items_removed'  => $total,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);
	}

	function page_switcher ($_urlbase, $_currentpage, $_totalpages) {
		$pageswitcher = "";
		if ($_totalpages > 1) {
			$pageswitcher = '<div class="tablenav bottom"><div class="tablenav-pages">'.__('Pages', 'csmm').': <span class="pagiation-links">';
			if (strpos($_urlbase,"?") !== false) $_urlbase .= "&amp;";
			else $_urlbase .= "?";
			if ($_currentpage == 1) $pageswitcher .= "<a class='page disabled'>1</a> ";
			else $pageswitcher .= " <a class='page' href='".$_urlbase."p=1'>1</a> ";

			$start = max($_currentpage-3, 2);
			$end = min(max($_currentpage+3,$start+6), $_totalpages-1);
			$start = max(min($start,$end-6), 2);
			if ($start > 2) $pageswitcher .= " <b>...</b> ";
			for ($i=$start; $i<=$end; $i++) {
				if ($_currentpage == $i) $pageswitcher .= " <a class='page disabled'>".$i."</a> ";
				else $pageswitcher .= " <a class='page' href='".$_urlbase."p=".$i."'>".$i."</a> ";
			}
			if ($end < $_totalpages-1) $pageswitcher .= " <b>...</b> ";

			if ($_currentpage == $_totalpages) $pageswitcher .= " <a class='page disabled'>".$_totalpages."</a> ";
			else $pageswitcher .= " <a class='page' href='".$_urlbase."p=".$_totalpages."'>".$_totalpages."</a> ";
			$pageswitcher .= "</span></div></div>";
		}
		return $pageswitcher;
	}
	
	function get_rgb($_color) {
		if (strlen($_color) != 7 && strlen($_color) != 4) return false;
		$color = preg_replace('/[^#a-fA-F0-9]/', '', $_color);
		if (strlen($color) != strlen($_color)) return false;
		if (strlen($color) == 7) list($r, $g, $b) = array($color[1].$color[2], $color[3].$color[4], $color[5].$color[6]);
		else list($r, $g, $b) = array($color[1].$color[1], $color[2].$color[2], $color[3].$color[3]);
		return array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
	}
}
$csmm = new csmm_class();
?>