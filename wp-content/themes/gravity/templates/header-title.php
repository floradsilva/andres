<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */

// Page (category, tag, archive, author) title

if ( gravity_need_page_title() ) {
	gravity_sc_layouts_showed('title', true);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title">
						<?php
						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$gravity_blog_title = gravity_get_blog_title();
							$gravity_blog_title_text = $gravity_blog_title_class = $gravity_blog_title_link = $gravity_blog_title_link_text = '';
							if (is_array($gravity_blog_title)) {
								$gravity_blog_title_text = $gravity_blog_title['text'];
								$gravity_blog_title_class = !empty($gravity_blog_title['class']) ? ' '.$gravity_blog_title['class'] : '';
								$gravity_blog_title_link = !empty($gravity_blog_title['link']) ? $gravity_blog_title['link'] : '';
								$gravity_blog_title_link_text = !empty($gravity_blog_title['link_text']) ? $gravity_blog_title['link_text'] : '';
							} else
								$gravity_blog_title_text = $gravity_blog_title;
							?>
							<h1 class="sc_layouts_title_caption<?php echo esc_attr($gravity_blog_title_class); ?>"><?php
								$gravity_top_icon = gravity_get_category_icon();
								if (!empty($gravity_top_icon)) {
									$gravity_attr = gravity_getimagesize($gravity_top_icon);
									?><img src="<?php echo esc_url($gravity_top_icon); ?>"  <?php if (!empty($gravity_attr[3])) gravity_show_layout($gravity_attr[3]);?>><?php
								}
								echo wp_kses_data($gravity_blog_title_text);
							?></h1>
							<?php
							if (!empty($gravity_blog_title_link) && !empty($gravity_blog_title_link_text)) {
								?><a href="<?php echo esc_url($gravity_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($gravity_blog_title_link_text); ?></a><?php
							}
							
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'gravity_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>