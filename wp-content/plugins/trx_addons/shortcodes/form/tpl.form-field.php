<?php
/**
 * Template of one field of the form
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_form_field');
?>
<label class="sc_form_field sc_form_field_<?php echo esc_attr($args['field_name']); ?><?php echo !empty($args['field_req']) ? ' required' : ' optional'; ?>">
	<?php if (!empty($args['labels']) && trx_addons_is_on($args['labels'])) { ?>
		<span class="sc_form_field_title"><?php echo esc_attr($args['field_title']); ?></span>
	<?php } ?>
	<span class="sc_form_field_wrap"><?php
		if ($args['field_type'] == 'textarea') {
			?><textarea 
					name="<?php echo esc_attr($args['field_name']); ?>"
					id="<?php echo esc_attr($args['field_name']); ?>"
					<?php if (!empty($args['field_req'])) echo ' aria-required="true"'; ?>
					<?php if ($args['style']=='default') echo ' placeholder="'.esc_attr($args['field_placeholder']).'"'; ?>
					><?php if (!empty($args['field_value'])) echo esc_html($args['field_value']); ?></textarea><?php
		} else {
			?><input type="<?php echo esc_attr($args['field_type']); ?>" 
					name="<?php echo esc_attr($args['field_name']); ?>"
					id="<?php echo esc_attr($args['field_name']); ?>"
					value="<?php if (!empty($args['field_value'])) echo esc_attr($args['field_value']); ?>"
					<?php if (!empty($args['field_req'])) echo ' aria-required="true"'; ?>
					<?php if ($args['style']=='default') echo ' placeholder="'.esc_attr($args['field_placeholder']).'"'; ?>
					><?php
		}
		if ($args['style']!='default') { 
			?><span class="sc_form_field_hover"><?php
				if ($args['style'] == 'path') {
					$path_height = $args['field_type'] == 'text' ? 75 : 190;
					?><svg class="sc_form_field_graphic" preserveAspectRatio="none" viewBox="0 0 520 <?php echo intval($path_height); ?>" height="100%" width="100%"><path d="m0,0l520,0l0,<?php echo intval($path_height); ?>l-520,0l0,-<?php echo intval($path_height); ?>z"></svg><?php
				} else if ($args['style'] == 'iconed') {
					?><i class="sc_form_field_icon <?php echo esc_attr($args['field_icon']); ?>"></i><?php
				}
				?><span class="sc_form_field_content" data-content="<?php echo esc_attr($args['field_title']); ?>"><?php echo esc_html($args['field_title']); ?></span><?php
			?></span><?php
		}
		?>
	</span>
</label>
