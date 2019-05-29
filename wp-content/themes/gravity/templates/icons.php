<?php
/**
 * The template to displaying popup with Theme Icons
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

$gravity_icons = gravity_get_list_icons();
if (is_array($gravity_icons)) {
	?>
	<div class="gravity_list_icons">
		<?php
		foreach($gravity_icons as $icon) {
			?><span class="<?php echo esc_attr($icon); ?>" title="<?php echo esc_attr($icon); ?>"></span><?php
		}
		?>
	</div>
	<?php
}
?>