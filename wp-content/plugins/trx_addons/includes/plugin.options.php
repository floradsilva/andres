<?php
/**
 * Plugin's options
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Load current values for each customizable option
if ( !function_exists('trx_addons_load_options') ) {
	function trx_addons_load_options() {
		global $TRX_ADDONS_STORAGE;
		$options = get_option('trx_addons_options');
		if (isset($TRX_ADDONS_STORAGE['options']) && is_array($TRX_ADDONS_STORAGE['options']) && count($TRX_ADDONS_STORAGE['options']) > 0) {
			foreach ($TRX_ADDONS_STORAGE['options'] as $k=>$v) {
				if (isset($v['std'])) {
					$val = isset($_GET[$k]) 
								? $_GET[$k] 
								: (isset($options[$k])
									? $options[$k]
									: $v['std']
								);
					if (is_array($v['std'])) {
						foreach ($v['std'] as $k1=>$v1) {
							if (!isset($val[$k1])) $val[$k1] = $v1;
						}
						foreach ($val as $k1=>$v1) {
							if (!isset($v['std'][$k1])) unset($val[$k1]);
						}
					}
					$TRX_ADDONS_STORAGE['options'][$k]['val'] = $val;
				}
			}
		}
	}
}


// Return customizable option value
if (!function_exists('trx_addons_get_option')) {
	function trx_addons_get_option($name, $defa='', $strict_mode=true) {
		global $TRX_ADDONS_STORAGE;
		$rez = $defa;
		$part = '';
		if (strpos($name, '[')!==false) {
			$tmp = explode('[', $name);
			$name = $tmp[0];
			$part = substr($tmp[1], 0, -1);
		}
		if ( !isset($TRX_ADDONS_STORAGE['options'][$name]) && $strict_mode ) {
			$s = debug_backtrace();
			//array_shift($s);
			$s = array_shift($s);
			echo '<pre>';
			echo esc_html(sprintf(__('Undefined option "%s" called from:', 'trx_addons'), $name));
			if (function_exists('trx_addons_debug_dump_screen')) 
				trx_addons_debug_dump_screen($s);
			else
				print_r($s);
			echo '</pre>';
			die();
		}
		// Override option from GET
		if (isset($_GET[$name])) {
			if (empty($part))
				$rez = $_GET[$name];
			else if (isset($_GET[$name][$part]))
				$rez = $_GET[$name][$part];
		// Get saved option value
		} else if (isset($TRX_ADDONS_STORAGE['options'][$name]['val'])) {
			if (empty($part))
				$rez = $TRX_ADDONS_STORAGE['options'][$name]['val'];
			else if (isset($TRX_ADDONS_STORAGE['options'][$name]['val'][$part]))
				$rez = $TRX_ADDONS_STORAGE['options'][$name]['val'][$part];
		}
		return $rez;
	}
}

// Get dependencies list from the Plugin's Options
if ( !function_exists('trx_addons_get_options_dependencies') ) {
	function trx_addons_get_options_dependencies($options=null) {
		global $TRX_ADDONS_STORAGE;
		if (!$options) $options = $TRX_ADDONS_STORAGE['options'];
		$depends = array();
		foreach ($options as $k=>$v) {
			if (isset($v['dependency'])) 
				$depends[$k] = $v['dependency'];
		}
		return $depends;
	}
}


// -----------------------------------------------------------------
// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// -- Internal theme settings
// -----------------------------------------------------------------

if (!function_exists('trx_addons_init_options')) {
	add_action( 'after_setup_theme', 'trx_addons_init_options', 3 );
	function trx_addons_init_options() {
		global $TRX_ADDONS_STORAGE;

		$TRX_ADDONS_STORAGE['options'] = apply_filters('trx_addons_filter_options', array(
		
			// Section 'General' - main options
			'general_section' => array(
				"title" => esc_html__('General', 'trx_addons'),
				"desc" => wp_kses_data( __('General options', 'trx_addons') ),
				"type" => "section"
			),
			'general_info' => array(
				"title" => esc_html__('General Settings', 'trx_addons'),
				"desc" => wp_kses_data( __("General settings of the ThemeREX Addons", 'trx_addons') ),
				"type" => "info"
			),
			'debug_mode' => array(
				"title" => esc_html__('Debug mode', 'trx_addons'),
				"desc" => wp_kses_data( __('Enable debug functions and theme profiler output.', 'trx_addons') )
						. '<br>'
						. wp_kses_data( __('Attention! When the "Debug Mode" is on, the original .css and .js files are being used. When it is turned off, composed files that contain many scripts and styles are used! Composed files must not be edited! If you want to change scripts or styles of plugins and/or of the theme, turn the "Debug mode" on, apply the changes to the original files, check if everything works, and turn the "Debug mode" off again. Composed files are being rewritten every time you save plugin\'s and the theme\'s options.', 'trx_addons') ),
				"std" => "0",
				"type" => "checkbox"
			),
			'retina_ready' => array(
				"title" => esc_html__('Image dimensions', 'trx_addons'),
				"desc" => wp_kses_data( __('Which dimensions will be used for the uploaded images: "Original" or "Retina ready" (twice enlarged)', 'trx_addons') ),
				"std" => "1",
				"size" => "medium",
				"options" => array(
					"1" => esc_html__("Original", 'trx_addons'), 
					"2" => esc_html__("Retina", 'trx_addons')
					),
				"type" => "switch"
			),
			'images_quality' => array(
				"title" => esc_html__('Quality for cropped images', 'trx_addons'),
				"desc" => wp_kses_data( __('Quality (1-100) to save cropped images. Attention! After change the image quality, you need to regenerate all thumbnails!', 'trx_addons') ),
				"std" => 60,
				"type" => "text"
			),
			'page_preloader' => array(
				"title" => esc_html__("Show page preloader", 'trx_addons'),
				"desc" => wp_kses_data( __("Select one of predefined styles for the page preloader or upload preloader image", 'trx_addons') ),
				"std" => "none",
				"options" => array(
					'none'   => esc_html__('Hide preloader', 'trx_addons'),
					'circle' => esc_html__('Circle', 'trx_addons'),
					'square' => esc_html__('Square', 'trx_addons'),
					'custom' => esc_html__('Custom', 'trx_addons')
					),
				"type" => "select"
			),
			'page_preloader_image' => array(
				"title" => esc_html__('Page preloader image',  'trx_addons'),
				"desc" => wp_kses_data( __('Select or upload page preloader image for your site. If empty - site not using preloader',  'trx_addons') ),
				"dependency" => array(
					"page_preloader" => array('custom')
				),
				"std" => "",
				"type" => "image"
			),
			'page_preloader_bg_color' => array(
				"title" => esc_html__('Page preloader bg color',  'trx_addons'),
				"desc" => wp_kses_data( __('Select background color for the page preloader. If empty - not use background color',  'trx_addons') ),
				"std" => "#ffffff",
				"type" => "color"
			),
			'scroll_to_top' => array(
				"title" => esc_html__('Add "Scroll to Top"', 'trx_addons'),
				"desc" => wp_kses_data( __('Add "Scroll to Top" button when page is scrolled down', 'trx_addons') ),
				"std" => "1",
				"type" => "checkbox"
			),
			'animate_inner_links' => array(
				"title" => esc_html__('Animate inner links', 'trx_addons'),
				"desc" => wp_kses_data( __('Add "Smooth Scroll" to the inner page links (started with "#")', 'trx_addons') ),
				"std" => "0",
				"type" => "checkbox"
			),
			'popup_engine' => array(
				"title" => esc_html__('Popup Engine', 'trx_addons'),
				"desc" => wp_kses_data( __('Select script to show popup windows with images and any other html code', 'trx_addons') ),
				"std" => "magnific",
				"options" => array(
					"none" => esc_html__("None", 'trx_addons'),
					'magnific' => esc_html__("Magnific Popup", 'trx_addons')
				),
				"type" => "radio"
			),
			'menu_info' => array(
				"title" => esc_html__('Menu', 'trx_addons'),
				"desc" => wp_kses_data( __("Menu parameters", 'trx_addons') ),
				"type" => "info"
			),
			'menu_cache' => array(
				"title" => esc_html__('Use menu cache', 'trx_addons'),
				"desc" => wp_kses_data( __('Use cache for the menu (increase theme speed, decrease queries number). Attention! Please, save menu again after change permalink settings! Uncheck this option if you use WPML!', 'trx_addons') ),
				"std" => 0,
				"type" => "checkbox"
			),
			'breadcrumbs_max_level' => array(
				"title" => esc_html__('Breadcrumbs nestings', 'trx_addons'),
				"desc" => wp_kses_data( __('Max nesting level of the breadcrumbs. If empty or 0 - display all breadcrumbs elements!', 'trx_addons') ),
				"std" => 3,
				"type" => "text"
			),
			'login_info' => array(
				"title" => esc_html__('Login and Registration', 'trx_addons'),
				"desc" => wp_kses_data( __("Specify parameters of the User's Login and Registration", 'trx_addons') ),
				"type" => "info"
			),
			'login_via_ajax' => array(
				"title" => esc_html__('Login via AJAX', 'trx_addons'),
				"desc" => wp_kses_data( __('Login via AJAX or use direct link on the WP Login page. Uncheck it if you have problem with any login plugin.', 'trx_addons') ),
				"std" => "1",
				"type" => "checkbox"
			),
			'login_via_socials' => array(
				"title" => esc_html__('Login via social profiles',  'trx_addons'),
				"desc" => wp_kses_data( __('Specify shortcode from your Social Login Plugin or any HTML/JS code to make Social Login section',  'trx_addons') ),
				"std" => "",
				"type" => "textarea"
			),
			"notify_about_new_registration" => array(
				"title" => esc_html__('Notify about new registration', 'trx_addons'),
				"desc" => wp_kses_data( __("Send E-mail with a new registration data to the site admin e-mail and/or to the new user's e-mail", 'trx_addons') ),
				"std" => "no",
				"options" => array(
					'no'    => esc_html__('No', 'trx_addons'),
					'both'  => esc_html__('Both', 'trx_addons'),
					'admin' => esc_html__('Admin', 'trx_addons'),
					'user'  => esc_html__('User', 'trx_addons')
				),
				"type" => "select"
			),
		

			// Section 'API Keys'
			'api_section' => array(
				"title" => esc_html__('API', 'trx_addons'),
				"desc" => wp_kses_data( __("API Keys for some Web-services", 'trx_addons') ),
				"type" => "section"
			),
			'api_info' => array(
				"title" => esc_html__('Google API', 'trx_addons'),
				"desc" => wp_kses_data( __("Control loading Google API script and specify Google API Key to access Google map services", 'trx_addons') ),
				"type" => "info"
			),
			'api_google_load' => array(
				"title" => esc_html__('Load Google API script', 'trx_addons'),
				"desc" => wp_kses_data( __("Uncheck this field to disable loading Google API script if it loaded by another plugin", 'trx_addons') ),
				"std" => "1",
				"type" => "checkbox"
			),
			'api_google' => array(
				"title" => esc_html__('Google API Key', 'trx_addons'),
				"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above", 'trx_addons') ),
				"dependency" => array(
					"api_google_load" => '1'
				),
				"std" => "",
				"type" => "text"
			),
		
		
			// Section 'Socials and Share'
			'socials_section' => array(
				"title" => esc_html__('Socials', 'trx_addons'),
				"desc" => wp_kses_data( __("Links to the social profiles and post's share settings", 'trx_addons') ),
				"type" => "section"
			),
			'socials_info' => array(
				"title" => esc_html__('Links to your social profiles', 'trx_addons'),
				"desc" => wp_kses_data( __("Links to your favorites social networks", 'trx_addons') ),
				"type" => "info"
			),
			'socials_twitter' => array(
				"title" => esc_html__('Twitter', 'trx_addons'),
				"desc" => wp_kses_data( __("Link to your profile in the Twitter", 'trx_addons') ),
				"std" => "",
				"type" => "text"
			),
			'socials_facebook' => array(
				"title" => esc_html__('Facebook', 'trx_addons'),
				"desc" => wp_kses_data( __("Link to your profile in the Facebook", 'trx_addons') ),
				"std" => "",
				"type" => "text"
			),
			'socials_gplus' => array(
				"title" => esc_html__('Google+', 'trx_addons'),
				"desc" => wp_kses_data( __("Link to your profile in the Google+", 'trx_addons') ),
				"std" => "",
				"type" => "text"
			),
			'socials_tumblr' => array(
				"title" => esc_html__('Tumblr', 'trx_addons'),
				"desc" => wp_kses_data( __("Link to your profile in the Tumblr", 'trx_addons') ),
				"std" => "",
				"type" => "text"
			),
			'socials_instagram' => array(
				"title" => esc_html__('Instagram', 'trx_addons'),
				"desc" => wp_kses_data( __("Link to your profile in the Instagram", 'trx_addons') ),
				"std" => "",
				"type" => "text"
			),
			'socials_dribbble' => array(
				"title" => esc_html__('Dribbble', 'trx_addons'),
				"desc" => wp_kses_data( __("Link to your profile in the Dribbble", 'trx_addons') ),
				"std" => "",
				"type" => "text"
			),

			'share_info' => array(
				"title" => esc_html__('URL to share posts', 'trx_addons'),
				"desc" => wp_kses_post( __("Specify URLs to share your posts in the social networks. If empty - no share post in this social network.<br>You can use next macros to include post's parts into the URL:<br><br>{link} - post's URL,<br>{title} - title of the post,<br>{descr} - excerpt of the post,<br>{image} - post's featured image URL,<br>{id} - post's ID", 'trx_addons') ),
				"type" => "info"
			),
			'share_twitter' => array(
				"title" => esc_html__('Twitter', 'trx_addons'),
				"desc" => wp_kses_data( __("URL to share your posts in the Twitter", 'trx_addons') ),
				"std" => trx_addons_get_share_url('twitter'),
				"type" => "text"
			),
			'share_facebook' => array(
				"title" => esc_html__('Facebook', 'trx_addons'),
				"desc" => wp_kses_data( __("URL to share your posts in the Facebook", 'trx_addons') ),
				"std" => trx_addons_get_share_url('facebook'),
				"type" => "text"
			),
			'share_gplus' => array(
				"title" => esc_html__('Google+', 'trx_addons'),
				"desc" => wp_kses_data( __("URL to share your posts in the Google+", 'trx_addons') ),
				"std" => trx_addons_get_share_url('gplus'),
				"type" => "text"
			),
			'share_tumblr' => array(
				"title" => esc_html__('Tumblr', 'trx_addons'),
				"desc" => wp_kses_data( __("URL to share your posts in the Tumblr", 'trx_addons') ),
				"std" => trx_addons_get_share_url('tumblr'),
				"type" => "text"
			),
			'share_mail' => array(
				"title" => esc_html__('E-mail', 'trx_addons'),
				"desc" => wp_kses_data( __("URL to share your posts via E-mail", 'trx_addons') ),
				"std" => trx_addons_get_share_url('email'),
				"type" => "text"
			),
		
		
		
			// Section 'Shortcodes'
			'sc_section' => array(
				"title" => esc_html__('Shortcodes', 'trx_addons'),
				"desc" => wp_kses_data( __("Shortcodes settings", 'trx_addons') ),
				"type" => "section"
			),
			'sc_anchor_info' => array(
				"title" => esc_html__('Anchor', 'trx_addons'),
				"desc" => wp_kses_data( __("Settings of the 'Anchor' shortcode", 'trx_addons') ),
				"type" => "info"
			),
			'scroll_to_anchor' => array(
				"title" => esc_html__('Scroll to Anchor', 'trx_addons'),
				"desc" => wp_kses_data( __('Scroll to Prev/Next anchor on mouse wheel', 'trx_addons') ),
				"std" => "1",
				"type" => "checkbox"
			),
			'update_location_from_anchor' => array(
				"title" => esc_html__('Update location from Anchor', 'trx_addons'),
				"desc" => wp_kses_data( __("Update browser location bar form the anchor's href when page is scrolling", 'trx_addons') ),
				"std" => "0",
				"type" => "checkbox"
			),
		
	
			// Section 'Theme Specific'
			'theme_specific_section' => array(
				"title" => esc_html__('Theme specific', 'trx_addons'),
				"desc" => wp_kses_data( __("Theme specific settings", 'trx_addons') ),
				"type" => "section"
			),
			'hovers_info' => array(
				"title" => esc_html__('Hover effects', 'trx_addons'),
				"desc" => wp_kses_data( __("Settings of the Hover effects", 'trx_addons') ),
				"type" => "info"
			),
			'input_hover' => array(
				"title" => esc_html__("Input field's hover", 'trx_addons'),
				"desc" => wp_kses_data( __("Select the default hover effect for the shortcode 'form' input fields and for the comment's form (if theme support)", 'trx_addons') ),
				"std" => 'default',
				"options" => trx_addons_get_list_input_hover(),
				"type" => "select"
			),
			'columns_info' => array(
				"title" => esc_html__('Columns Grid', 'trx_addons'),
				"desc" => wp_kses_data( __("Theme-specific classes to use it instead plugin's internal classes to create columns", 'trx_addons') ),
				"type" => "info"
			),
			'columns_wrap_class' => array(
				"title" => esc_html__("Column's wrap class", 'trx_addons'),
				"desc" => wp_kses_data( __("Specify theme specific class for the column's wrap. If empty - use plugin's internal grid", 'trx_addons') ),
				"std" => '',
				"type" => "text"
			),
			'columns_wrap_class_fluid' => array(
				"title" => esc_html__("Column's wrap class for fluid columns", 'trx_addons'),
				"desc" => wp_kses_data( __("Specify theme specific class for the fluid column's wrap. If empty - use plugin's internal grid", 'trx_addons') ),
				"std" => '',
				"type" => "text"
			),
			'column_class' => array(
				"title" => esc_html__('Class for the single column', 'trx_addons'),
				"desc" => wp_kses_data( __("For example: column-$1_$2, where $1 - column width, $2 - total columns: column-1_4, column-2_3, etc. If empty - use plugin's internal grid", 'trx_addons') ),
				"std" => "",
				"type" => "text"
			)
		));

		trx_addons_load_options();
	}
}
?>