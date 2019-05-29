<?php
/**
 * The style "default" of the Widget "Contacts"
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.10
 */

$args = get_query_var('trx_addons_args_widget_contacts');
extract($args);
		
// Before widget (defined by themes)
trx_addons_show_layout($before_widget);
			
// Widget title if one was input (before and after defined by themes)
trx_addons_show_layout($title, $before_title, $after_title);
	
// Widget body
?><div class="contacts_wrap"><?php

if (!empty($logo)) {
	?><div class="contacts_logo"><?php trx_addons_show_layout($logo); ?></div><?php
}
if (!empty($description)) {
	$description = str_replace(array('{{Y}}', '{Y}'), date('Y'), $description);
	?><div class="contacts_description"><?php echo wpautop($description); ?></div><?php
}
$show_info = !empty($address) || !empty($phone) || !empty($email);
if (!$show_info) $googlemap_position = 'top';
if ($show_info || !empty($googlemap)) {
	if ($show_info && !empty($googlemap)) {
		?><div class="contacts_place contacts_map_<?php echo esc_attr($googlemap_position); ?>"><?php
	}
	if (!empty($googlemap) && !empty($address) && function_exists('trx_addons_sc_googlemap')) {
		trx_addons_show_layout(trx_addons_sc_googlemap(array(
												'address' => $address,
												'height' => $googlemap_height,
												'zoom' => 10
												)), '<div class="contacts_map">', '</div>');
	}
	if (!empty($address) || !empty($phone) || !empty($email)) {
		?><div class="contacts_info"><?php
			if (!empty($address)) {
				?><div class="contacts_left"><span class="contacts_label"><?php echo esc_html__( 'Address', 'gravity' ); ?></span><span class="contacts_address"><?php echo str_replace('|', "<br>", $address); ?></span></div><?php
			}
        if (!empty($phone)) {
            ?><div class="contacts_left"><span class="contacts_label"><?php echo esc_html__( 'Phone', 'gravity' ); ?></span><span class="contacts_phone"><a href="tel:<?php gravity_show_layout($phone); ?>"><?php gravity_show_layout($phone); ?></a></span></div><?php
        }
        if (!empty($email)) {
            ?><div class="contacts_left"><span class="contacts_label"><?php echo esc_html__( 'Email', 'gravity' ); ?></span><span class="contacts_email"><a href="mailto:<?php echo esc_url($email); ?>"><?php gravity_show_layout($email); ?></a></span></div><?php
        }
		?></div><?php
	}
	if ($show_info && !empty($googlemap)) {
		?></div><?php
	}
}

// Social icons
if ( $socials && ($output = trx_addons_get_socials_links()) != '') {
	?><div class="contacts_socials socials_wrap"><?php trx_addons_show_layout($output); ?></div><?php
}

// Custom content
if ( !empty($content) ) {
	?><div class="contacts_content"><?php trx_addons_show_layout($content); ?></div><?php
}

?></div><!-- /.contacts_wrap --><?php
	
// After widget (defined by themes)
trx_addons_show_layout($after_widget);
?>