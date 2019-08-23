<?php
/**
 * The template to display menu in the footer
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0.10
 */

// Footer menu
$gravity_menu_footer = gravity_get_nav_menu('menu_footer', '', 1);
if (!empty($gravity_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php gravity_show_layout($gravity_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>