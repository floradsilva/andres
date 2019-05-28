<?php
/**
 * The template to display the socials in the footer
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0.10
 */


// Socials
if ( gravity_is_on(gravity_get_theme_option('socials_in_footer')) && ($gravity_output = gravity_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php gravity_show_layout($gravity_output); ?>
		</div>
	</div>
	<?php
}
?>