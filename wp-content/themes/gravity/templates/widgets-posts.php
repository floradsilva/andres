<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

$gravity_post_id    = get_the_ID();
$gravity_post_date  = gravity_get_date();
$gravity_post_title = get_the_title();
$gravity_post_link  = get_permalink();
$gravity_post_author_id   = get_the_author_meta('ID');
$gravity_post_author_name = get_the_author_meta('display_name');
$gravity_post_author_url  = get_author_posts_url($gravity_post_author_id, '');

$gravity_args = get_query_var('gravity_args_widgets_posts');
$gravity_show_date = isset($gravity_args['show_date']) ? (int) $gravity_args['show_date'] : 1;
$gravity_show_image = isset($gravity_args['show_image']) ? (int) $gravity_args['show_image'] : 1;
$gravity_show_author = isset($gravity_args['show_author']) ? (int) $gravity_args['show_author'] : 1;
$gravity_show_counters = isset($gravity_args['show_counters']) ? (int) $gravity_args['show_counters'] : 1;
$gravity_show_categories = isset($gravity_args['show_categories']) ? (int) $gravity_args['show_categories'] : 1;

$gravity_output = gravity_storage_get('gravity_output_widgets_posts');

$gravity_post_counters_output = '';
if ( $gravity_show_counters ) {
	$gravity_post_counters_output = '<span class="post_info_item post_info_counters">'
								. gravity_get_post_counters('comments')
							. '</span>';
}


$gravity_output .= '<article class="post_item with_thumb">';

if ($gravity_show_image) {
	$gravity_post_thumb = get_the_post_thumbnail($gravity_post_id, gravity_get_thumb_size('tiny'), array(
		'alt' => get_the_title()
	));
	if ($gravity_post_thumb) $gravity_output .= '<div class="post_thumb">' . ($gravity_post_link ? '<a href="' . esc_url($gravity_post_link) . '">' : '') . ($gravity_post_thumb) . ($gravity_post_link ? '</a>' : '') . '</div>';
}

$gravity_output .= '<div class="post_content">'
			. ($gravity_show_categories 
					? '<div class="post_categories">'
						. gravity_get_post_categories()
						. $gravity_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($gravity_post_link ? '<a href="' . esc_url($gravity_post_link) . '">' : '') . ($gravity_post_title) . ($gravity_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('gravity_filter_get_post_info', 
								'<div class="post_info">'
									. ($gravity_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($gravity_post_link ? '<a href="' . esc_url($gravity_post_link) . '" class="post_info_date">' : '') 
											. esc_html($gravity_post_date) 
											. ($gravity_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($gravity_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'gravity') . ' ' 
											. ($gravity_post_link ? '<a href="' . esc_url($gravity_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($gravity_post_author_name) 
											. ($gravity_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$gravity_show_categories && $gravity_post_counters_output
										? $gravity_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
gravity_storage_set('gravity_output_widgets_posts', $gravity_output);
?>