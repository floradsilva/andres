<?php
/**
 * The style "default" of the Googlemap
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_googlemap');

?><div id="<?php echo esc_attr($args['id']); ?>_wrap" class="sc_googlemap_wrap"><?php

	trx_addons_sc_show_titles('sc_googlemap', $args);
	
	if ($args['content']) {
		?><div class="sc_googlemap_content_wrap"><?php
	}
	?><div id="<?php echo esc_attr($args['id']); ?>"
			class="sc_googlemap sc_googlemap_<?php
				echo esc_attr($args['type']);
				echo (!empty($args['class']) ? ' '.esc_attr($args['class']) : '');
			?>"
			<?php echo (trim($args['css']!='' ? ' style="'.esc_attr($args['css']).'"' : '')); ?>
			data-zoom="<?php echo esc_attr($args['zoom']); ?>"
			data-style="<?php echo esc_attr($args['style']); ?>"
			><?php
			$cnt = 0;
			$key = trx_addons_get_option('api_google');
			foreach ($args['markers'] as $marker) {
				$cnt++;
				if ( empty($key) ) {
					?><iframe
					src="https://maps.google.com/maps?t=m&output=embed&iwloc=near&z=<?php
					echo esc_attr($args['zoom'] ? $args['zoom'] : 14);
					?>&q=<?php
					echo esc_attr(!empty($marker['address']) ? urlencode($marker['address']) : '')
						. ( !empty($marker['latlng'])
							? ( !empty($marker['address']) ? '@' : '' ) . str_replace(' ', '', $marker['latlng'])
							: ''
						)
					?>"
					aria-label="<?php echo esc_attr(!empty($marker['title']) ? $marker['title'] : ''); ?>"></iframe><?php
					break; // Remove this line if you want display separate iframe for each marker (otherwise only first marker shown)
				} else {
					if (!empty($marker['icon']))
						$marker['icon'] = trx_addons_get_attachment_url($marker['icon'], 'full');
					?><div id="<?php echo esc_attr($args['id'].'_'.intval($cnt)); ?>" class="sc_googlemap_marker"
						   data-latlng="<?php echo esc_attr(!empty($marker['latlng']) ? $marker['latlng'] : ''); ?>"
						   data-address="<?php echo esc_attr(!empty($marker['address']) ? $marker['address'] : ''); ?>"
						   data-description="<?php echo esc_attr(!empty($marker['description']) ? $marker['description'] : ''); ?>"
						   data-title="<?php echo esc_attr(!empty($marker['title']) ? $marker['title'] : ''); ?>"
						   data-icon="<?php echo esc_attr(!empty($marker['icon']) ? $marker['icon'] : ''); ?>"
						   data-offsetx="<?php echo esc_attr(!empty($marker['offsetX']) ? $marker['offsetX'] : '0'); ?>"
						   data-offsety="<?php echo esc_attr(!empty($marker['offsetY']) ? $marker['offsetY'] : '0'); ?>"
					></div><?php
				}
			}
	?></div><?php
	
	if ($args['content']) {
		?>
			<div class="sc_googlemap_content sc_googlemap_content_<?php echo esc_attr(trim($args['type'])); if (isset($args['large_content']) && $args['large_content'] == 1) echo esc_attr(' sc_googlemap_large_content'); ?>"><?php gravity_show_layout($args['content']); ?></div>
		</div>
		<?php
	}

	trx_addons_sc_show_links('sc_googlemap', $args);
	
?></div><!-- /.sc_googlemap_wrap -->