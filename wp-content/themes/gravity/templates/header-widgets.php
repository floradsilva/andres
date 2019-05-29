<?php
/**
 * The template to display the widgets area in the header
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

// Header sidebar
$gravity_header_name = gravity_get_theme_option('header_widgets');
$gravity_header_present = !gravity_is_off($gravity_header_name) && is_active_sidebar($gravity_header_name);
if ($gravity_header_present) { 
	gravity_storage_set('current_sidebar', 'header');
	$gravity_header_wide = gravity_get_theme_option('header_wide');
	ob_start();
    if ( is_active_sidebar( $gravity_header_name ) ) {
        dynamic_sidebar( $gravity_header_name );
    }
	$gravity_widgets_output = ob_get_contents();
	ob_end_clean();
	if (trim(strip_tags($gravity_widgets_output)) != '') {
		$gravity_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $gravity_widgets_output);
		$gravity_need_columns = strpos($gravity_widgets_output, 'columns_wrap')===false;
		if ($gravity_need_columns) {
			$gravity_columns = max(0, (int) gravity_get_theme_option('header_columns'));
			if ($gravity_columns == 0) $gravity_columns = min(6, max(1, substr_count($gravity_widgets_output, '<aside ')));
			if ($gravity_columns > 1)
				$gravity_widgets_output = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($gravity_columns).' widget ', $gravity_widgets_output);
			else
				$gravity_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($gravity_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$gravity_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($gravity_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'gravity_action_before_sidebar' );
				gravity_show_layout($gravity_widgets_output);
				do_action( 'gravity_action_after_sidebar' );
				if ($gravity_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$gravity_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>