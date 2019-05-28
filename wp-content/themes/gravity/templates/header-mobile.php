<?php
/**
 * The template to show mobile menu
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */
?>
<div class="menu_mobile_overlay"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr(gravity_get_theme_option('menu_mobile_fullscreen') > 0 ? 'fullscreen' : 'narrow'); ?>">
	<div class="menu_mobile_inner">
        <div class="content_wrap clearfix">
            <a class="menu_mobile_close icon-cancel"></a>
        </div>
		<?php


		// Mobile menu
		$gravity_menu_mobile = gravity_get_nav_menu('menu_mobile');
		if (empty($gravity_menu_mobile)) {
			$gravity_menu_mobile = apply_filters('gravity_filter_get_mobile_menu', '');
			if (empty($gravity_menu_mobile)) $gravity_menu_mobile = gravity_get_nav_menu('menu_main');
			if (empty($gravity_menu_mobile)) $gravity_menu_mobile = gravity_get_nav_menu();
		}
		if (!empty($gravity_menu_mobile)) {
			if (!empty($gravity_menu_mobile))
				$gravity_menu_mobile = str_replace(
					array('menu_main', 'id="menu-', 'sc_layouts_menu_nav', 'sc_layouts_hide_on_mobile', 'hide_on_mobile'),
					array('menu_mobile', 'id="menu_mobile-', '', '', ''),
					$gravity_menu_mobile
					);
			if (strpos($gravity_menu_mobile, '<nav ')===false)
				$gravity_menu_mobile = sprintf('<nav class="menu_mobile_nav_area">%s</nav>', $gravity_menu_mobile);
			gravity_show_layout(apply_filters('gravity_filter_menu_mobile_layout', $gravity_menu_mobile));
		}

		?>
	</div>
</div>
