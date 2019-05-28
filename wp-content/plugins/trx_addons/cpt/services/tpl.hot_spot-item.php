<?php
/**
 * The style "default" of the Services
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4
 */

$args = get_query_var('trx_addons_args_sc_services');

$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
$link = get_permalink();
$featured_position = !empty($args['featured_position']) ? $args['featured_position'] : 'top';
$svg_present = false;

if ($args['slider']) {
	?><div class="swiper-slide"><?php
} else if ($args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
}
?>
<div class="sc_services_item<?php
	echo isset($args['hide_excerpt']) && $args['hide_excerpt'] ? ' without_content' : ' with_content';
	echo empty($args['featured']) || $args['featured']=='image' 
					? ' with_image' 
					: ($args['featured']=='icon' ? ' with_icon' : ' with_number');
	echo ' sc_services_item_featured_'.esc_attr($featured_position);
?>">
	<?php
if (isset($args['type']) && $args['type'] == 'hot_spot') {
    $align = !empty($args['title_align']) ? ' sc_align_'.trim($args['title_align']) : '';
    $style = !empty($args['title_style']) ? ' sc_item_title_style_'.trim($args['title_style']) : '';
if (!empty($args['title'])) {
    if (empty($size)) $size = is_page() ? 'large' : 'normal';
    $title_tag = !empty($args['title_tag']) && !trx_addons_is_off($args['title_tag'])
        ? $args['title_tag']
        : apply_filters('trx_addons_filter_sc_item_title_tag', 'large' == $size ? 'h2' : ('tiny' == $size ? 'h4' : 'h3'));
    $title_tag_class = !empty($args['title_tag']) && !trx_addons_is_off($args['title_tag'])
        ? ' sc_item_title_tag'
        : '';
    ?><<?php echo esc_attr($title_tag); ?> class="<?php echo esc_attr(apply_filters('trx_addons_filter_sc_item_title_class', 'sc_item_title sc_services_title'.$align.$style.$title_tag_class, 'sc_services')); ?>"><?php echo trim(trx_addons_prepare_macros($args['title'])); ?></<?php echo esc_attr($title_tag); ?>><?php
}
}
    // Featured image
    get_template_part( 'templates/post-featured-hot-spot' );


	?>
	<div class="sc_services_item_info">
        <div class="sc_services_item_button sc_item_button"><a href="<?php echo esc_url($link); ?>" class="sc_button sc_button_simple"><?php esc_html_e('More info', 'trx_addons'); ?></a></div>
	</div>
</div>
<?php
if ($args['slider'] || $args['columns'] > 1) {
	?></div><?php
}
if (trx_addons_is_on(trx_addons_get_option('debug_mode')) && $svg_present) {
	wp_enqueue_script( 'vivus', trx_addons_get_file_url('shortcodes/icons/vivus.js'), array('jquery'), null, true );
	wp_enqueue_script( 'trx_addons-sc_icons', trx_addons_get_file_url('shortcodes/icons/icons.js'), array('jquery'), null, true );
}
?>