<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

$gravity_args = get_query_var('gravity_logo_args');

// Site logo
$gravity_logo_image  = gravity_get_logo_image(isset($gravity_args['type']) ? $gravity_args['type'] : '');
$gravity_logo_text   = gravity_is_on(gravity_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$gravity_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($gravity_logo_image) || !empty($gravity_logo_text)) {
	?><a class="sc_layouts_logo" href="<?php echo is_front_page() ? '#' : esc_url(home_url('/')); ?>"><?php
		if (!empty($gravity_logo_image)) {
			$gravity_attr = gravity_getimagesize($gravity_logo_image);
			echo '<img src="'.esc_url($gravity_logo_image).'" '.(!empty($gravity_attr[3]) ? sprintf(' %s', $gravity_attr[3]) : '').'>' ;
		} else {
			gravity_show_layout(gravity_prepare_macros($gravity_logo_text), '<span class="logo_text">', '</span>');
			gravity_show_layout(gravity_prepare_macros($gravity_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>