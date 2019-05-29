<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0.10
 */

// Footer sidebar
$gravity_footer_name = gravity_get_theme_option('footer_widgets');
$gravity_footer_present = !gravity_is_off($gravity_footer_name) && is_active_sidebar($gravity_footer_name);
if ($gravity_footer_present) { 
	gravity_storage_set('current_sidebar', 'footer');
	$gravity_footer_wide = gravity_get_theme_option('footer_wide');
	ob_start();
    if ( is_active_sidebar( $gravity_footer_name ) ) {
        dynamic_sidebar( $gravity_footer_name );
    }
	$gravity_out = trim(ob_get_contents());
	ob_end_clean();
	if (trim(strip_tags($gravity_out)) != '') {
		$gravity_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $gravity_out);
		$gravity_need_columns = true;
		if ($gravity_need_columns) {
			$gravity_columns = max(0, (int) gravity_get_theme_option('footer_columns'));
			if ($gravity_columns == 0) $gravity_columns = min(6, max(1, substr_count($gravity_out, '<aside ')));
			if ($gravity_columns > 1)
				$gravity_out = preg_replace("/class=\"widget /", "class=\"column-1_".esc_attr($gravity_columns).' widget ', $gravity_out);
			else
				$gravity_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($gravity_footer_wide) ? ' footer_fullwidth' : ''; ?>">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$gravity_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($gravity_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'gravity_action_before_sidebar' );
				gravity_show_layout($gravity_out);
				do_action( 'gravity_action_after_sidebar' );
				if ($gravity_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$gravity_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>