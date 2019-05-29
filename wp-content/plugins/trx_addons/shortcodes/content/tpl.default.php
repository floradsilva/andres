<?php
/**
 * The style "default" of the Content Block
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_content');

?><div id="<?php echo esc_attr($args['id']); ?>"
		class="sc_content sc_content_<?php
			echo esc_attr($args['type']);
			if (!empty($args['float']) && !trx_addons_is_off($args['float'])) echo ' sc_float_'.esc_attr($args['float']);
			if (!empty($args['align']) && !trx_addons_is_off($args['align'])) echo ' sc_align_'.esc_attr($args['align']);
			if (!empty($args['class'])) echo ' '.esc_attr($args['class']);
			if (!empty($args['width']) && !trx_addons_is_off($args['width'])) echo ' sc_content_width_'.esc_attr(str_replace('/', '_', $args['width']));
			if (!empty($args['padding']) && !trx_addons_is_off($args['padding'])) echo ' sc_padding_'.esc_attr($args['padding']);
			?>"<?php
		if ($args['css']!='') echo ' style="'.esc_attr($args['css']).'"';
?>><?php

	trx_addons_sc_show_titles('sc_content', $args);
	
	?><div class="sc_content_container<?php if (!empty($args['number'])) echo ' with_number'; ?>"><?php
		if (!empty($args['number'])) {
			?><span class="sc_content_number sc_content_number_<?php echo esc_attr($args['number_position']); ?>"<?php
				if (!empty($args['number_color'])) echo ' style="color:'.esc_attr($args['number_color']).';"';
				?>><?php
					echo esc_html($args['number']);
			?></span><?php
		}
		echo trim($args['content']);
	?></div><?php

	trx_addons_sc_show_links('sc_content', $args);
	
?></div><!-- /.sc_content -->