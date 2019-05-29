<?php
/**
 * The template to display the site logo in the footer
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0.10
 */

// Logo
if (gravity_is_on(gravity_get_theme_option('logo_in_footer'))) {
	$gravity_logo_image = '';
	if (gravity_get_retina_multiplier(2) > 1)
		$gravity_logo_image = gravity_get_theme_option( 'logo_footer_retina' );
	if (empty($gravity_logo_image)) 
		$gravity_logo_image = gravity_get_theme_option( 'logo_footer' );
	$gravity_logo_text   = get_bloginfo( 'name' );
	if (!empty($gravity_logo_image) || !empty($gravity_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($gravity_logo_image)) {
					$gravity_attr = gravity_getimagesize($gravity_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($gravity_logo_image).'" class="logo_footer_image" '.(!empty($gravity_attr[3]) ? sprintf(' %s', $gravity_attr[3]) : '').'></a>' ;
				} else if (!empty($gravity_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($gravity_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>