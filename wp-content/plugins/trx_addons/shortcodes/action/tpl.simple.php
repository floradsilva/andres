<?php
/**
 * The style "simple" of the Action
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4.3
 */

$args = get_query_var('trx_addons_args_sc_action');

$icon_present = '';

?><div <?php if (!empty($args['id'])) echo ' id="'.esc_attr($args['id']).'"'; ?> 
	class="sc_action sc_action_simple<?php
		if ($args['slider']) echo ' swiper-slider-container slider_swiper slider_noresize slider_nocontrols '.($args['slider_pagination'] > 0 ? 'slider_pagination' : 'slider_nopagination');
		if (!empty($args['class'])) echo ' '.esc_attr($args['class']); ?>"<?php
	echo ($args['columns'] > 1 ? ' data-slides-per-view="' . esc_attr($args['columns']) . '"' : '')
		. ($args['slides_space'] > 0 ? ' data-slides-space="' . esc_attr($args['slides_space']) . '"' : '')
		. ' data-slides-min-width="250"';
	if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"'; ?>
	><?php

	trx_addons_sc_show_titles('sc_action', $args);

	if ($args['slider']) {
		?><div class="sc_action_slider sc_item_slider slides swiper-wrapper"><?php
	} else if ($args['columns'] > 1) {
		?><div class="sc_action_columns sc_item_columns <?php echo esc_attr(trx_addons_get_columns_wrap_class()); ?> columns_padding_bottom"><?php
	} else {
		?><div class="sc_action_content sc_item_content"><?php
	}	

	foreach ($args['actions'] as $item) {
		if (empty($item['color'])) $item['color'] = '';
		if (empty($item['position'])) $item['position'] = 'mc';
		if ($args['slider']) {
			?><div class="swiper-slide"><?php
		} else if ($args['columns'] > 1) {
			?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
		}
		if (!empty($item['bg_image'])) {
			$item['bg_image'] = trx_addons_get_attachment_url($item['bg_image'], trx_addons_get_thumb_size('huge'));
		}
		?><div class="sc_action_item sc_action_item_simple<?php
			if (!empty($item['bg_image']) || !empty($item['bg_color'])) echo ' with_image'; 
			if (!empty($item['bg_color'])) echo ' with_bg_color'; 
			if ($args['full_height'] == 1) echo ' trx_addons_stretch_height'; 
			if (!empty($args['height']) && $args['full_height'] == 0) echo ' sc_action_fixed_height';
			if (!empty($item['position'])) echo ' sc_action_item_'.esc_attr($item['position']); 
			?>"<?php
			if (!empty($item['bg_image']) || !empty($item['bg_color']) || (!empty($args['height']) && $args['full_height'] == 0)) 
				echo ' style="'
							. (!empty($item['bg_color']) ? 'background-color:'.esc_attr($item['bg_color']).';' : '')
							. (!empty($item['bg_image']) ? 'background-image:url('.esc_url($item['bg_image']).');' : '')
							. (!empty($args['height']) && $args['full_height'] == 0 ? 'height:'.esc_attr($args['height']).';overflow:hidden;' : '')
							. '"'; 
		?>>
			<?php
			if (!empty($item['bg_image']) || !empty($item['bg_color'])) {
				?>
				<div class="sc_action_item_mask"></div>
				<div class="sc_action_item_inner">
				<?php
			}
			if (!empty($item['image'])) {
				$item['image'] = trx_addons_get_attachment_url($item['image'], trx_addons_get_thumb_size('small'));
				$attr = trx_addons_getimagesize($item['image']);
				?><div class="sc_action_item_image"><img src="<?php echo esc_url($item['image']); ?>" alt=""<?php echo (!empty($attr[3]) ? ' '.trim($attr[3]) : ''); ?>></div><?php
			} else {
				$icon = !empty($item['icon_type']) && !empty($item['icon_' . $item['icon_type']]) && $item['icon_' . $item['icon_type']] != 'empty' ? $item['icon_' . $item['icon_type']] : '';
				if (!empty($icon) && strpos($icon_present, $item['icon_type'])===false)
					$icon_present .= (!empty($icon_present) ? ',' : '') . $item['icon_type'];
				 if (!empty($icon)) {
					?><div class="sc_action_item_icon sc_action_item_icon_type_<?php echo esc_attr($item['icon_type']); ?> <?php echo esc_attr($icon); ?>"
						<?php //if (!empty($item['color'])) echo ' style="color: '.esc_attr($item['color']).'"'; ?>
						><span class="sc_action_item_icon_type_<?php echo esc_attr($item['icon_type']); ?> <?php echo esc_attr($icon); ?>"
							<?php //if (!empty($item['color'])) echo ' style="color: '.esc_attr($item['color']).'; border-color: '.esc_attr($item['color']).';"'; ?>
						></span></div><?php
				 }
			}
			if (!empty($item['subtitle'])) {
				$item['subtitle'] = explode('|', $item['subtitle']);
				?><h6 class="sc_action_item_subtitle"<?php if (!empty($item['color'])) echo ' style="color: '.esc_attr($item['color']).'"'; ?>><?php
					foreach ($item['subtitle'] as $str) {
						?><span><?php echo esc_html($str); ?></span><?php
					}
				?></h6><?php
			}
			if (!empty($item['title'])) {
				$item['title'] = explode('|', $item['title']);
				?><h3 class="sc_action_item_title"><?php
					foreach ($item['title'] as $str) {
						?><span><?php echo esc_html($str); ?></span><?php
					}
				?></h3><?php
			}
			if (!empty($item['date'])) {
				?><div class="sc_action_item_date"><?php echo esc_html($item['date']); ?></div><?php
			}
			if (!empty($item['description'])) {
				$item['description'] = explode('|', str_replace("\n", '|', $item['description']));
				?><div class="sc_action_item_description"><?php
					foreach ($item['description'] as $str) {
						?><span><?php echo trim($str); ?></span><?php
					}
				?></div><?php
			}
			if (!empty($item['link']) && !empty($item['link_text'])) {
				?><a href="<?php echo esc_url($item['link']); ?>" class="sc_action_item_link"><?php echo esc_html($item['link_text']); ?></a><?php
			}
			if (!empty($item['info'])) {
				$item['info'] = explode('|', $item['info']);
				?><div class="sc_action_item_info"><?php
					foreach ($item['info'] as $str) {
						?><span><?php echo trim($str); ?></span><?php
					}
				?></div><?php
			}
			if (!empty($item['bg_image']) || !empty($item['bg_color'])) {
				?></div><!-- /.sc_action_item_inner --><?php
				if (!empty($item['link']) && empty($item['link_text'])) {
					?><a href="<?php echo esc_url($item['link']); ?>" class="sc_action_item_link sc_action_item_link_over"></a><?php
				}
			}
		?></div><?php

		if ($args['slider'] || $args['columns'] > 1) {
			?></div><?php
		}
	}

	?></div><?php

	if ($args['slider'] > 0 && $args['slider_pagination'] > 0) {
		?><div class="slider_pagination_wrap swiper-pagination"></div><?php
	}

	trx_addons_sc_show_links('sc_actions', $args);


?></div><!-- /.sc_action --><?php

trx_addons_load_icons($icon_present);
?>