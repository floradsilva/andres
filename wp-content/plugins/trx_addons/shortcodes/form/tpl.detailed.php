<?php
/**
 * The style "detailed" of the Contact form
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_form');
$form_style = $args['style'] = empty($args['style']) || trx_addons_is_inherit($args['style']) ? trx_addons_get_option('input_hover') : $args['style'];
?>
<div
	<?php if (!empty($args['id'])) echo ' id="'.esc_attr($args['id']).'"'; ?> 
	class="sc_form sc_form_detailed<?php 
		if (!empty($args['class'])) echo ' '.esc_attr($args['class']);
		if (!empty($args['align']) && !trx_addons_is_off($args['align'])) echo ' sc_align_'.esc_attr($args['align']); 
		?>"
	<?php if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"'; ?>
	>
	<?php trx_addons_sc_show_titles('sc_form', $args); ?>
	<div class="<?php echo esc_attr(trx_addons_get_columns_wrap_class()); ?> columns_padding_bottom"><?php
		// Contact form. Attention! Column's tags can't start from new line
		?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, 2)); ?>">
			<form class="sc_form_form <?php if ($form_style != 'default') echo 'sc_input_hover_'.esc_attr($form_style); ?>" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>"><?php
				
				set_query_var('trx_addons_args_sc_form_field', array_merge($args, array(
					'field_name'  => 'name',
					'field_type'  => 'text',
					'field_req'   => true,
					'field_icon'  => 'trx_addons_icon-user-alt',
					'field_title' => esc_html__('Name', 'trx_addons'),
					'field_placeholder' => esc_html__('Enter your name', 'trx_addons')
					
				)));
				if (($fdir = trx_addons_get_file_dir('shortcodes/form/tpl.form-field.php')) != '') { include $fdir; }
				
				set_query_var('trx_addons_args_sc_form_field', array_merge($args, array(
					'field_name'  => 'email',
					'field_type'  => 'text',
					'field_req'   => true,
					'field_icon'  => 'trx_addons_icon-mail',
					'field_title' => esc_html__('E-mail', 'trx_addons'),
					'field_placeholder' => esc_html__('Enter your e-mail', 'trx_addons')
					
				)));
				if ($fdir != '') { include $fdir; }

				set_query_var('trx_addons_args_sc_form_field', array_merge($args, array(
					'field_name'  => 'message',
					'field_type'  => 'textarea',
					'field_req'   => true,
					'field_icon'  => 'trx_addons_icon-feather',
					'field_title' => esc_html__('Message', 'trx_addons'),
					'field_placeholder' => esc_html__('Enter your message', 'trx_addons')
					
				)));
				if ($fdir != '') { include $fdir; }
				?>
                <?php
                static $cnt = 0;
                $cnt++;
                $privacy = trx_addons_get_privacy_text();
                if (!empty($privacy)) {
                    ?><div class="sc_form_field sc_form_field_checkbox"><?php
                    ?><input type="checkbox" id="i_agree_privacy_policy_sc_form_<?php echo esc_attr($cnt); ?>" name="i_agree_privacy_policy" class="sc_form_privacy_checkbox" value="1">
                    <label for="i_agree_privacy_policy_sc_form_<?php echo esc_attr($cnt); ?>"><?php trx_addons_show_layout($privacy); ?></label>
                    </div><?php
                }
                ?>
				<div class="sc_form_field sc_form_field_button"><button><?php esc_html_e('Send Message', 'trx_addons'); ?></button></div>
				<div class="trx_addons_message_box sc_form_result"></div>
			</form>
		</div><?php 
		
		// Contact data. Attention! Column's tags can't start from new line
		?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, 2)); ?>">
			<div class="sc_form_info">
				<?php
				if (!empty($args['phone'])) {
					$args['phone'] = explode('|', $args['phone']);
					?> 
					<div class="sc_form_info_item sc_form_info_item_phone">
						<span class="sc_form_info_icon"></span>
						<span class="sc_form_info_area">
							<span class="sc_form_info_title"><?php esc_html_e('Phone:', 'trx_addons'); ?></span>
							<span class="sc_form_info_data"><?php
								foreach ($args['phone'] as $item) {
									echo '<span>' . esc_html($item) . '</span>';
								}
							?></span>
						</span>
					</div>
					<?php
				}
				if (!empty($args['email'])) {
					$args['email'] = explode('|', $args['email']);
					?> 
					<div class="sc_form_info_item sc_form_info_item_email">
						<span class="sc_form_info_icon"></span>
						<span class="sc_form_info_area">
							<span class="sc_form_info_title"><?php esc_html_e('E-mail:', 'trx_addons'); ?></span>
						<span class="sc_form_info_data"><?php
							foreach ($args['email'] as $item) {
								echo '<a href="'.(strpos($item, '@')!==false ? 'mailto:'.trim($item) : esc_url($item)).'">' . esc_html($item) . '</a>';
							}
						?></span>
						</span>
					</div>
					<?php
				}
				if (!empty($args['address'])) {
					$args['address'] = explode('|', $args['address']);
					?> 
					<div class="sc_form_info_item sc_form_info_item_address">
						<span class="sc_form_info_icon"></span>
						<span class="sc_form_info_area">
							<span class="sc_form_info_title"><?php esc_html_e('Address:', 'trx_addons'); ?></span>
							<span class="sc_form_info_data"><?php
								foreach ($args['address'] as $item) {
									echo '<span>' . esc_html($item) . '</span>';
								}
							?></span>
						</span>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div><!-- /.sc_form -->
