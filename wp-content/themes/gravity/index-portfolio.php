<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

gravity_storage_set('blog_archive', true);

// Load scripts for both 'Gallery' and 'Portfolio' layouts!
wp_enqueue_script( 'classie', gravity_get_file_url('js/theme.gallery/classie.min.js'), array(), null, true );
wp_enqueue_script( 'imagesloaded', gravity_get_file_url('js/theme.gallery/imagesloaded.min.js'), array(), null, true );
wp_enqueue_script( 'masonry', gravity_get_file_url('js/theme.gallery/masonry.min.js'), array(), null, true );
wp_enqueue_script( 'gravity-gallery-script', gravity_get_file_url('js/theme.gallery/theme.gallery.js'), array(), null, true );

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$gravity_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$gravity_sticky_out = is_array($gravity_stickies) && count($gravity_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$gravity_cat = gravity_get_theme_option('parent_cat');
	$gravity_post_type = gravity_get_theme_option('post_type');
	$gravity_taxonomy = gravity_get_post_type_taxonomy($gravity_post_type);
	$gravity_show_filters = gravity_get_theme_option('show_filters');
	$gravity_tabs = array();
	if (!gravity_is_off($gravity_show_filters)) {
		$gravity_args = array(
			'type'			=> $gravity_post_type,
			'child_of'		=> $gravity_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $gravity_taxonomy,
			'pad_counts'	=> false
		);
		$gravity_portfolio_list = get_terms($gravity_args);
		if (is_array($gravity_portfolio_list) && count($gravity_portfolio_list) > 0) {
			$gravity_tabs[$gravity_cat] = esc_html__('All', 'gravity');
			foreach ($gravity_portfolio_list as $gravity_term) {
				if (isset($gravity_term->term_id)) $gravity_tabs[$gravity_term->term_id] = $gravity_term->name;
			}
		}
	}
	if (count($gravity_tabs) > 0) {
		$gravity_portfolio_filters_ajax = true;
		$gravity_portfolio_filters_active = $gravity_cat;
		$gravity_portfolio_filters_id = 'portfolio_filters';
		if (!is_customize_preview())
			wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
		?>
		<div class="portfolio_filters gravity_tabs gravity_tabs_ajax">
			<ul class="portfolio_titles gravity_tabs_titles">
				<?php
				foreach ($gravity_tabs as $gravity_id=>$gravity_title) {
					?><li><a href="<?php echo esc_url(gravity_get_hash_link(sprintf('#%s_%s_content', $gravity_portfolio_filters_id, $gravity_id))); ?>" data-tab="<?php echo esc_attr($gravity_id); ?>"><?php echo esc_html($gravity_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$gravity_ppp = gravity_get_theme_option('posts_per_page');
			if (gravity_is_inherit($gravity_ppp)) $gravity_ppp = '';
			foreach ($gravity_tabs as $gravity_id=>$gravity_title) {
				$gravity_portfolio_need_content = $gravity_id==$gravity_portfolio_filters_active || !$gravity_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $gravity_portfolio_filters_id, $gravity_id)); ?>"
					class="portfolio_content gravity_tabs_content"
					data-blog-template="<?php echo esc_attr(gravity_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(gravity_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($gravity_ppp); ?>"
					data-post-type="<?php echo esc_attr($gravity_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($gravity_taxonomy); ?>"
					data-cat="<?php echo esc_attr($gravity_id); ?>"
					data-parent-cat="<?php echo esc_attr($gravity_cat); ?>"
					data-need-content="<?php echo (false===$gravity_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($gravity_portfolio_need_content) 
						gravity_show_portfolio_posts(array(
							'cat' => $gravity_id,
							'parent_cat' => $gravity_cat,
							'taxonomy' => $gravity_taxonomy,
							'post_type' => $gravity_post_type,
							'page' => 1,
							'sticky' => $gravity_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		gravity_show_portfolio_posts(array(
			'cat' => $gravity_cat,
			'parent_cat' => $gravity_cat,
			'taxonomy' => $gravity_taxonomy,
			'post_type' => $gravity_post_type,
			'page' => 1,
			'sticky' => $gravity_sticky_out
			)
		);
	}

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>