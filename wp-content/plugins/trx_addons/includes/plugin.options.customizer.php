<?php
/**
 * Plugin's options customizer
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	die( '-1' );
}

// Add ThemeREX Addons item in the Appearance menu
// Hooks order for the plugin and theme on action 'after_setup_theme':
// 1 - plugin's components and/or theme register filters:
//     'trx_addons_filter_options' - to add/remove plugin options array
//     'trx_addons_cpt_list' - to enable/disable plugin's CPT
//     'trx_addons_sc_list' - to enable/disable plugin's shortcodes
//     'trx_addons_widgets_list' - to enable/disable plugin's widgets
//     'trx_addons_cv_enable' - to enable/disable plugin's CV functionality
// 3 - plugin do apply_filters('trx_addons_filter_options', $options) and load options
// 4 - plugin save options (if on the ThemeREX Addons Options page)
// 6 - plugin include components (shortcodes, widgets, CPT, etc.) filtered by theme hooks

if (!function_exists('trx_addons_add_menu_items')) {
	add_action( 'admin_menu', 'trx_addons_add_menu_items' );
	function trx_addons_add_menu_items() {
		add_theme_page(
			esc_html__('ThemeREX Addons', 'trx_addons'),	//page_title
			esc_html__('ThemeREX Addons', 'trx_addons'),	//menu_title
			'manage_options',								//capability
			'trx_addons_options',							//menu_slug
			'trx_addons_options_page_builder',				//callback
			'',												//icon
			''												//menu position
		);
	}
}

// Load scripts and styles
if (!function_exists('trx_addons_options_page_load_scripts')) {
	add_action("admin_enqueue_scripts", 'trx_addons_options_page_load_scripts');
	function trx_addons_options_page_load_scripts() {
		if (apply_filters('trx_addons_filter_need_options', isset($_REQUEST['page']) && $_REQUEST['page']=='trx_addons_options')) {
			// WP styles & scripts
			wp_enqueue_style( 'wp-color-picker', false, array(), null);
			wp_enqueue_script('wp-color-picker', false, array('jquery'), null, true);
			wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
			wp_enqueue_script('jquery-ui-accordion', false, array('jquery', 'jquery-ui-core'), null, true);
			wp_enqueue_script('jquery-ui-sortable', false, array('jquery', 'jquery-ui-core'), null, true);
			// Fontello icons must be loaded before main stylesheet
			wp_enqueue_style( 'trx_addons-icons', trx_addons_get_file_url('css/font-icons/css/trx_addons_icons-embedded.css') );
			// Internal styles & scripts
			wp_enqueue_style( 'trx_addons-options', trx_addons_get_file_url('css/trx_addons.options.css'), array(), null );
			wp_enqueue_script( 'trx_addons-options', trx_addons_get_file_url('js/trx_addons.options.js'), array('jquery'), null, true );
			if (isset($_REQUEST['page']) && $_REQUEST['page']=='trx_addons_options') {
				wp_localize_script( 'trx_addons-options', 'TRX_ADDONS_DEPENDENCIES', trx_addons_get_options_dependencies() );
			} else {
				global $TRX_ADDONS_STORAGE;
				$screen = function_exists('get_current_screen') ? get_current_screen() : false;
				if (is_object($screen) && in_array($screen->post_type, $TRX_ADDONS_STORAGE['post_types']) && isset($TRX_ADDONS_STORAGE['meta_box_'.$screen->post_type])) {
					wp_localize_script( 'trx_addons-options', 'TRX_ADDONS_DEPENDENCIES', trx_addons_get_options_dependencies($TRX_ADDONS_STORAGE['meta_box_'.$screen->post_type]) );
				}
			}
		}
	}
}

// Build options page
if (!function_exists('trx_addons_options_page_builder')) {
	function trx_addons_options_page_builder() {
		global $TRX_ADDONS_STORAGE;
		$result = trx_addons_get_last_result();
		?>
		<div class="trx_addons_options">
			<h2 class="trx_addons_options_title"><?php esc_html_e('ThemeREX Addons Settings', 'trx_addons'); ?></h2>
			<div class="trx_addons_options_result">
				<?php
				if (!empty($result['error'])) {
					?><div class="error"><p><?php echo trim($result['error']); ?></p></div><?php
				} else if (!empty($result['success'])) {
					?><div class="updated"><p><?php echo trim($result['success']); ?></p></div><?php
				}
				?>
			</div>
			<form id="trx_addons_options_form" action="#" method="post" enctype="multipart/form-data">
				<input type="hidden" name="trx_addons_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
				<?php trx_addons_options_show_fields(); ?>
				<div class="trx_addons_options_buttons">
					<input type="submit" value="<?php esc_html_e('Save Options', 'trx_addons'); ?>">
				</div>
			</form>
		</div>
		<?php		
	}
}

// Display all option's fields
if ( !function_exists('trx_addons_options_show_fields') ) {
	function trx_addons_options_show_fields($options=false) {
		global $TRX_ADDONS_STORAGE;
		if (empty($options)) $options = $TRX_ADDONS_STORAGE['options'];
		$tabs_titles = $tabs_content = array();
		$last_section = 'default';
		$last_panel = '';
		foreach ($options as $k=>$v) {
			if ($v['type']=='section') {
				if (!isset($tabs_titles[$k])) {
					$tabs_titles[$k] = $v['title'];
					$tabs_content[$k] = '';
				}
				if (!empty($last_panel)) {
					$tabs_content[$last_section] .= '</div></div>';
					$last_panel = '';
				}
				$last_section = $k;
			} else if ($v['type']=='panel') {
				if (empty($last_panel))
					$tabs_content[$last_section] = (!isset($tabs_content[$last_section]) ? '' : $tabs_content[$last_section]) . '<div class="trx_addons_options_panels">';
				else
					$tabs_content[$last_section] .= '</div>';
				$tabs_content[$last_section] .= '<h4 class="trx_addons_options_panel_title">' . esc_html($v['title']) . '</h4>'
												. '<div class="trx_addons_options_panel_content">';
				$last_panel = $k;
			} else if ($v['type']=='panel_end') {
				if (!empty($last_panel)) {
					$tabs_content[$last_section] .= '</div></div>';
					$last_panel = '';
				}
			} else {
				$tabs_content[$last_section] = (!isset($tabs_content[$last_section]) ? '' : $tabs_content[$last_section]) . trx_addons_options_show_field($k, $v);
			}
		}
		if (!empty($last_panel)) {
			$tabs_content[$last_section] .= '</div></div>';
		}
		
		if (count($tabs_content) > 0) {
			?>
			<div id="trx_addons_options_tabs" class="<?php echo count($tabs_titles) > 1 ? 'with_tabs' : 'no_tabs'; ?>">
				<?php if (count($tabs_titles) > 1) { ?>
					<ul><?php
						$cnt = 0;
						foreach ($tabs_titles as $k=>$v) {
							$cnt++;
							?><li><a href="#trx_addons_options_section_<?php echo esc_attr($cnt); ?>"><?php echo esc_html($v); ?></a></li><?php
						}
					?></ul>
				<?php
				}
				$cnt = 0;
				foreach ($tabs_content as $k=>$v) {
					$cnt++;
					?>
					<div id="trx_addons_options_section_<?php echo esc_attr($cnt); ?>" class="trx_addons_options_section">
						<?php echo trim($v); ?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
}

// Display single option's field
if ( !function_exists('trx_addons_options_show_field') ) {
	function trx_addons_options_show_field($name, $field) {
		$output = '<div class="trx_addons_options_item'
						. ' trx_addons_options_item_'.esc_attr($field['type'])
						. (!empty($field['class']) ? ' '.$field['class'] : '')
						. '">'
							. '<h4 class="trx_addons_options_item_title">' . esc_html($field['title']) . '</h4>'
							. '<div class="trx_addons_options_item_data">'
								. '<div class="trx_addons_options_item_field' . (!empty($field['dir']) ? ' trx_addons_options_item_field_'.esc_attr($field['dir']) : '') . '"'
									. ' data-param="'.esc_attr($name)
									. '">';

		if ($field['type']=='checkbox') {
			$output .= '<label class="trx_addons_options_item_label">'
						. '<input type="checkbox" name="trx_addons_options_field_'.esc_attr($name).'" value="1"'.(!empty($field['val']) ? ' checked="checked"' : '').' />'
						. esc_html($field['title'])
					. '</label>';

		} else if ($field['type']=='checklist') {
			$output .= '<div class="trx_addons_options_item_choises' . (!empty($field['sortable']) ? ' trx_addons_options_sortable' : '') . '">';
			foreach ($field['val'] as $k=>$v) {
				$output .= '<label class="trx_addons_options_item_label">'
							. '<input type="checkbox" name="trx_addons_options_field_'.esc_attr($name).'['.$k.']" value="1" data-name="'.$k.'"'.((int) $v == 1 ? ' checked="checked"' : '').' />'
							. esc_html(!empty($field['options'][$k]) ? $field['options'][$k] : $k)
						. '</label>';
			}
			$output .= '<input type="hidden" name="trx_addons_options_field_'.esc_attr($name).'" value="'.trx_addons_options_put_field_value($field).'" />'
					. '</div>';

		} else if ($field['type']=='radio' || $field['type']=='switch') {
			foreach ($field['options'] as $k=>$v) {
				$output .= '<label class="trx_addons_options_item_label">'
								. '<input type="radio" name="trx_addons_options_field_'.esc_attr($name).'" value="'.esc_attr($k).'"'.($field['val']==$k ? ' checked="checked"' : '').'>'
								. esc_html($v)
							. '</label>';
			}

		} else if ($field['type']=='button') {
			$output .= '<input type="button" name="trx_addons_options_field_'.esc_attr($name).'" value="'.esc_attr($field['title']).'" data-action="'.esc_attr($field['val']).'">';

		} else if (in_array($field['type'], array('text', 'date', 'time'))) {
			$output .= '<input type="text" name="trx_addons_options_field_'.esc_attr($name).'" value="'.esc_attr($field['val']).'" />';

		} else if ($field['type']=='textarea') {
			$output .= '<textarea name="trx_addons_options_field_'.esc_attr($name).'">'.esc_attr($field['val']).'</textarea>';

		} else if ($field['type']=='select') {
			$output .= '<select size="1" name="trx_addons_options_field_'.esc_attr($name).'">';
			foreach ($field['options'] as $k=>$v) {
				$output .= '<option value="'.esc_attr($k).'"'.($field['val']==$k ? ' selected="selected"' : '').' class="'.esc_attr($k).'">'.esc_html($v).'</option>';
			}
			$output .= '</select>';

		} else if ($field['type']=='icon') {
			$output .= '<select size="1" name="trx_addons_options_field_'.esc_attr($name).'">';
			foreach ($field['options'] as $v) {
				$output .= '<option class="'.esc_attr($v).'" value="'.esc_attr($v).'"'.($field['val']==$v ? ' selected="selected"' : '').'>'
						. esc_html(str_replace(array('trx_addons_icon-', 'icon-'), '', $v))
					. '</option>';
			}
			$output .= '</select>';

		} else if ($field['type']=='color') {
			$output .= '<input type="text" class="trx_addons_color_selector" name="trx_addons_options_field_'.esc_attr($name).'" value="'.esc_attr($field['val']).'" />';

		} else if ($field['type']=='image') {
			$output .= '<input type="text" id="trx_addons_options_field_'.esc_attr($name).'" name="trx_addons_options_field_'.esc_attr($name).'" value="'.esc_attr($field['val']).'" />'
					. trx_addons_options_show_custom_field('trx_addons_options_field_'.esc_attr($name).'_button', array('type'=>'mediamanager', 'linked_field_id'=>'trx_addons_options_field_'.esc_attr($name)), null)
					. '<div class="trx_addons_options_field_preview">'
						. ($field['val'] ? '<img src="' . esc_url($field['val']) . '" alt="">' : '')
					. '</div>';
		}

		$output .=  		'</div><!-- /.trx_addons_options_item_field -->'
							. '<div class="trx_addons_options_item_description">'
								. (!empty($field['override']['desc']) ? trim($field['override']['desc']) : trim($field['desc']))	// param 'desc' already processed with wp_kses()!
							. '</div><!-- /.trx_addons_options_item_description -->'
						. '</div><!-- /.trx_addons_options_item_data -->'
					. '</div><!-- /.trx_addons_options_item -->';
		return $output;
	}
}

// Display custom option's field
if (!function_exists('trx_addons_options_show_custom_field')) {
	function trx_addons_options_show_custom_field($id, $field) {
		$output = '';
		switch ($field['type']) {
			case 'mediamanager':
				wp_enqueue_media( );
				$title = empty($field['data_type']) || $field['data_type']=='image'
								? esc_html__( 'Choose Image', 'trx_addons')
								: esc_html__( 'Choose Media', 'trx_addons');
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager trx_addons_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] 
						? esc_html__( 'Choose Images', 'trx_addons') 
						: $title
						).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] 
						? esc_html__( 'Add to Gallery', 'trx_addons') 
						: $title
						).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-type="'.esc_attr(!empty($field['data_type']) ? $field['data_type'] : 'image').'"
					data-linked-field="'.esc_attr($field['linked_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] 
						? esc_html__( 'Choose Images', 'trx_addons') 
						: esc_html($title)
					) . '</a>';
				break;
		}
		return $output;
	}
}


// Prepare complex field value to put into single tag's value
if (!function_exists('trx_addons_options_put_field_value')) {
	function trx_addons_options_put_field_value($field) {
		if (is_array($field['val'])) {
			$val = '';
			foreach ($field['val'] as $k=>$v) {
				$val .= ($val ? '|' : '') . $k . '=' . $v;
			}
		} else
			$val = $field['val'];
		return $val;
	}
}


// Get complex field value from POST
if (!function_exists('trx_addons_options_get_field_value')) {
	function trx_addons_options_get_field_value($name, $field) {
		$val = isset($_POST['trx_addons_options_field_'.$name])
							? trx_addons_get_value_gp('trx_addons_options_field_'.$name)
							: ($field['type']=='checkbox' ? 0 : '');
		if (is_array($field['std'])) {
			$tmp = explode('|', $val);
			$val = array();
			foreach ($tmp as $v) {
				$v = explode('=', $v);
				$val[$v[0]] = $v[1];
			}
		}
		return $val;
	}
}



// Save options
if (!function_exists('trx_addons_options_save')) {
	add_action('after_setup_theme', 'trx_addons_options_save', 4);
	function trx_addons_options_save() {

		if (!isset($_REQUEST['page']) || $_REQUEST['page']!='trx_addons_options' || trx_addons_get_value_gp('trx_addons_nonce')=='') return;

		global $TRX_ADDONS_STORAGE;

		// verify nonce
		if ( !wp_verify_nonce( trx_addons_get_value_gp('trx_addons_nonce'), admin_url() ) ) {
			$TRX_ADDONS_STORAGE['result']['error'] = esc_html__('Bad security code! Options are not saved!', 'trx_addons');
			return;
		}

		// Check permissions
		if (!current_user_can('manage_options')) {
			$TRX_ADDONS_STORAGE['result']['error'] = esc_html__('Manage options is denied for the current user! Options are not saved!', 'trx_addons');
			return;
		}

		// Save options
		$options = array();
		foreach ($TRX_ADDONS_STORAGE['options'] as $k=>$v) {
			// Skip options without value (section, info, etc.)
			if (!isset($v['std'])) continue;
			// Get option value from POST
			$TRX_ADDONS_STORAGE['options'][$k]['val'] = $options[$k] = trx_addons_options_get_field_value($k, $v);
		}
		update_option('trx_addons_options', $options);

		// Apply action - moved into delayed state (see below) to load all enabled modules and apply changes after
		//do_action('trx_addons_action_save_options');
		
		// Return result
		update_option('trx_addons_action', 'trx_addons_action_save_options');
		update_option('trx_addons_message', esc_html__('Options are saved', 'trx_addons'));
		wp_redirect($_SERVER['HTTP_REFERER']);
		exit();
	}
}
?>