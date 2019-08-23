<?php
/**
 * The style "chess" of the Services item
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.6.13
 */

$args = get_query_var('trx_addons_args_sc_services');

$link = get_permalink();

if ($args['slider']) {
	?><div class="swiper-slide"><?php
} else if ($args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?> "><?php
}
?>
<div class="sc_services_item<?php
	echo !isset($args['hide_excerpt']) || $args['hide_excerpt']==0 ? ' with_content' : ' without_content';
?>"><?php
	set_query_var('trx_addons_args_featured', array(
		'class' => 'sc_services_item_header',
		'show_no_image' => true,
		'thumb_bg' => true,
		'thumb_size' => apply_filters('trx_addons_filter_services_thumb_size', trx_addons_get_thumb_size($args['columns']==1 ? 'masonry-big' : 'masonry'), $args)
	));
	if (($fdir = trx_addons_get_file_dir('templates/tpl.featured.php')) != '') { include $fdir; }
	?>
	<div class="sc_services_item_content">
		<?php
		$title_tag = 'h6';
		if ($args['columns'] == 1) $title_tag = 'h4';
		?>
		<<?php echo esc_attr($title_tag); ?> class="sc_services_item_title"><a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></<?php echo esc_attr($title_tag); ?>>
		<div class="sc_services_item_subtitle"><?php echo trim(trx_addons_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_SERVICES_TAXONOMY));?></div>
		<?php if (!isset($args['hide_excerpt']) || $args['hide_excerpt']==0) { ?>
			<div class="sc_services_item_text"><?php the_excerpt(); ?></div>
		<?php } ?>
	</div>
</div>
<?php
if ($args['slider'] || $args['columns'] > 1) {
	?></div><?php
}
?>