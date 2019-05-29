<?php
/**
 * Widget: Categories list
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Load widget
if (!function_exists('trx_addons_widget_categories_list_load')) {
	add_action( 'widgets_init', 'trx_addons_widget_categories_list_load' );
	function trx_addons_widget_categories_list_load() {
		register_widget('trx_addons_widget_categories_list');
	}
}

// Widget Class
class trx_addons_widget_categories_list extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_categories_list', 'description' => esc_html__('Display categories list with icons or images', 'trx_addons'));
		parent::__construct( 'trx_addons_widget_categories_list', esc_html__('ThemeREX Addons - Categories list', 'trx_addons'), $widget_ops );
	}

	// Show widget
	function widget($args, $instance) {

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
		$style = isset($instance['style']) ? max(1, (int) $instance['style']) : 1;
		$number = isset($instance['number']) ? (int) $instance['number'] : '';
		$columns = isset($instance['columns']) ? (int) $instance['columns'] : '';
		$show_posts = isset($instance['show_posts']) ? (int) $instance['show_posts'] : 0;
		$show_children = isset($instance['show_children']) ? (int) $instance['show_children'] : 0;
		$cat_list = isset($instance['cat_list']) ? $instance['cat_list'] : '';

		$categories = get_categories(array(
			'type'                     => 'post',
			'taxonomy'                 => 'category',
			'include'                  => $cat_list,
			'number'                   => $number > 0 && empty($cat_list) ? $number : '',
			'parent'                   => $show_children && is_category() ? (int) get_query_var( 'cat' ) : '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 0,
			'hierarchical'             => 0,
			'pad_counts'               => $show_posts > 0 
		
		));

		// If result is empty - exit without output
		if (count($categories)==0) return;

		set_query_var('trx_addons_args_widget_categories_list', array_merge($args, compact('title', 'style', 'number', 'columns', 'show_posts', 'show_children', 'categories')));
		if (($fdir = trx_addons_get_file_dir('widgets/categories_list/tpl.categories-list-'.trim($style).'.php')) != '') { include $fdir; }
		else if (($fdir = trx_addons_get_file_dir('widgets/categories_list/tpl.categories-list-1.php')) != '') { include $fdir; }
	}

	// Update the widget settings
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Strip tags for title and comments count to remove HTML (important for text inputs)
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['style'] = (int) $new_instance['style'];
		$instance['number'] = (int) $new_instance['number'];
		$instance['columns'] = (int) $new_instance['columns'];
		$instance['show_posts'] = !empty($new_instance['show_posts']) ? 1 : 0;
		$instance['show_children'] = !empty($new_instance['show_children']) ? 1 : 0;
		$instance['cat_list'] = join(',', $new_instance['cat_list']);
		return $instance;
	}

	// Displays the widget settings controls on the widget panel
	function form($instance) {
		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'style' => '1',
			'number' => '5',
			'columns' => '5',
			'show_posts' => '1',
			'show_children' => '0',
			'cat_list' => ''
			)
		);
		$title = $instance['title'];
		$style = (int) $instance['style'];
		$number = (int) $instance['number'];
		$columns = (int) $instance['columns'];
		$show_posts = (int) $instance['show_posts'];
		$show_children = (int) $instance['show_children'];
		$cat_list = $instance['cat_list'];
		// Prepare categories list
		$categories = get_categories(array(
			'type'                     => 'post',
			'taxonomy'                 => 'category',
			'orderby'                  => 'id',
			'order'                    => 'ASC',
			'hide_empty'               => 1,
			'hierarchical'             => 0,
			'pad_counts'               => true 
		
		));
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Widget title:', 'trx_addons'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('style')); ?>_1"><?php esc_html_e('Output style:', 'trx_addons'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('style')); ?>_1" name="<?php echo esc_attr($this->get_field_name('style')); ?>" value="1" <?php echo (1==$style ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('style')); ?>_1"><?php esc_html_e('Style 1', 'trx_addons'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('style')); ?>_2" name="<?php echo esc_attr($this->get_field_name('style')); ?>" value="2" <?php echo (2==$style ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('style')); ?>_2"><?php esc_html_e('Style 2', 'trx_addons'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('style')); ?>_3" name="<?php echo esc_attr($this->get_field_name('style')); ?>" value="3" <?php echo (3==$style ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('style')); ?>_3"><?php esc_html_e('Style 3', 'trx_addons'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('cat_list')); ?>"><?php esc_html_e('Categories to show:', 'trx_addons'); ?></label>
			<span class="widgets_param_catlist">
				<?php 
				foreach ($categories as $cat) {
					?><input type="checkbox"
								value="<?php echo esc_attr($cat->term_id); ?>" 
								id="<?php echo esc_attr($this->get_field_id('cat_list')); ?>_<?php echo esc_attr($cat->term_id); ?>" 
								name="<?php echo esc_attr($this->get_field_name('cat_list')); ?>[]"
								<?php if (strpos(','.$cat_list.',', ','.$cat->term_id.',')!==false) echo ' checked="checked"'; ?>>
					<label for="<?php echo esc_attr($this->get_field_id('cat_list')); ?>_<?php echo esc_attr($cat->term_id); ?>"><?php echo esc_html($cat->name); ?></label><br><?php
				}
				?>
			</span>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number categories to show (if field above is empty):', 'trx_addons'); ?></label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($number); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Columns number:', 'trx_addons'); ?></label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('columns')); ?>" name="<?php echo esc_attr($this->get_field_name('columns')); ?>" value="<?php echo esc_attr($columns); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_posts')); ?>_1"><?php esc_html_e('Show posts count:', 'trx_addons'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_posts')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_posts')); ?>" value="1" <?php echo (1==$show_posts ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_posts')); ?>_1"><?php esc_html_e('Show', 'trx_addons'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_posts')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_posts')); ?>" value="0" <?php echo (0==$show_posts ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_posts')); ?>_0"><?php esc_html_e('Hide', 'trx_addons'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_children')); ?>_1"><?php esc_html_e('Only children of the current category:', 'trx_addons'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_children')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_children')); ?>" value="1" <?php echo (1==$show_children ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_children')); ?>_1"><?php esc_html_e('Children', 'trx_addons'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_children')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_children')); ?>" value="0" <?php echo (0==$show_children ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_children')); ?>_0"><?php esc_html_e('From root', 'trx_addons'); ?></label>
		</p>
	<?php
	}
}

	
// Load required styles and scripts for the frontend
if ( !function_exists( 'trx_addons_widget_categories_list_load_scripts_front' ) ) {
	add_action("wp_enqueue_scripts", 'trx_addons_widget_categories_list_load_scripts_front');
	function trx_addons_widget_categories_list_load_scripts_front() {
		if (trx_addons_is_on(trx_addons_get_option('debug_mode'))) {
			wp_enqueue_style( 'trx_addons-widget_categories_list', trx_addons_get_file_url('widgets/categories_list/categories_list.css'), array(), null );
		}
	}
}

	
// Merge widget specific styles into single stylesheet
if ( !function_exists( 'trx_addons_widget_categories_list_merge_styles' ) ) {
	add_action("trx_addons_filter_merge_styles", 'trx_addons_widget_categories_list_merge_styles');
	function trx_addons_widget_categories_list_merge_styles($list) {
		$list[] = 'widgets/categories_list/categories_list.css';
		return $list;
	}
}



// trx_widget_categories_list
//-------------------------------------------------------------
/*
[trx_widget_categories_list id="unique_id" title="Widget title" style="1" number="4" columns="4" show_posts="0|1" show_children="0|1" cat_list="id1,id2,id3,..."]
*/
if ( !function_exists( 'trx_addons_sc_widget_categories_list' ) ) {
	function trx_addons_sc_widget_categories_list($atts, $content=null){	
		$atts = trx_addons_sc_prepare_atts('trx_widget_categories_list', $atts, array(
			// Individual params
			"title" => '',
			'style' => '1',
			'number' => '5',
			'columns' => '5',
			'show_posts' => '1',
			'show_children' => '0',
			'cat_list' => '',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
			)
		);
		extract($atts);
		$type = 'trx_addons_widget_categories_list';
		$output = '';
		global $wp_widget_factory, $TRX_ADDONS_STORAGE;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_categories_list' 
								. (trx_addons_exists_visual_composer() ? ' vc_widget_categories_list wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
								. '"'
							. ($css ? ' style="'.esc_attr($css).'"' : '')
						. '>';
			ob_start();
			the_widget( $type, $atts, trx_addons_prepare_widgets_args($TRX_ADDONS_STORAGE['widgets_args'], $id ? $id.'_widget' : 'widget_categories_list', 'widget_categories_list') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('trx_addons_sc_output', $output, 'trx_widget_categories_list', $atts, $content);
	}
	if (trx_addons_exists_visual_composer()) add_shortcode("trx_widget_categories_list", "trx_addons_sc_widget_categories_list");
}


// Add [trx_widget_categories_list] in the VC shortcodes list
if (!function_exists('trx_addons_sc_widget_categories_list_add_in_vc')) {
	function trx_addons_sc_widget_categories_list_add_in_vc() {
		
		vc_map( apply_filters('trx_addons_sc_map', array(
				"base" => "trx_widget_categories_list",
				"name" => esc_html__("Widget Categories List", 'trx_addons'),
				"description" => wp_kses_data( __("Insert categories list with icons or images", 'trx_addons') ),
				"category" => esc_html__('ThemeREX', 'trx_addons'),
				"icon" => 'icon_trx_widget_categories_list',
				"class" => "trx_widget_categories_list",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array_merge(
					array(
						array(
							"param_name" => "title",
							"heading" => esc_html__("Widget title", 'trx_addons'),
							"description" => wp_kses_data( __("Title of the widget", 'trx_addons') ),
							"admin_label" => true,
							"type" => "textfield"
						),
						array(
							"param_name" => "style",
							"heading" => esc_html__("Style", 'trx_addons'),
							"description" => wp_kses_data( __("Select style to display categories list", 'trx_addons') ),
							"std" => 1,
							"value" => array(
								esc_html__('Style 1') => 1,
								esc_html__('Style 2') => 2
								),
							"type" => "dropdown"
						),
						array(
							"param_name" => "number",
							"heading" => esc_html__("Number of categories to show", 'trx_addons'),
							"description" => wp_kses_data( __("How many categories display in widget?", 'trx_addons') ),
							"admin_label" => true,
							"value" => "5",
							"type" => "textfield"
						),
						array(
							"param_name" => "columns",
							"heading" => esc_html__("Coulumns number to show", 'trx_addons'),
							"description" => wp_kses_data( __("How many columns use to display categories list?", 'trx_addons') ),
							"admin_label" => true,
							"value" => "5",
							"type" => "textfield"
						),
						array(
							"param_name" => "show_posts",
							"heading" => esc_html__("Show posts number", 'trx_addons'),
							"description" => wp_kses_data( __("Do you want display posts number?", 'trx_addons') ),
							"std" => "1",
							"value" => array("Show posts number" => "1" ),
							"type" => "checkbox"
						),
						array(
							"param_name" => "show_children",
							"heading" => esc_html__("Show children", 'trx_addons'),
							"description" => wp_kses_data( __("Show only children of current category", 'trx_addons') ),
							"std" => "0",
							"value" => array("Show children" => "1" ),
							"type" => "checkbox"
						)
					),
					trx_addons_vc_add_id_param()
				)
			), 'trx_widget_categories_list') );
			
		class WPBakeryShortCode_Trx_Widget_Categories_List extends WPBakeryShortCode {}

	}
	if (trx_addons_exists_visual_composer()) add_action('after_setup_theme', 'trx_addons_sc_widget_categories_list_add_in_vc', 20);
}
?>