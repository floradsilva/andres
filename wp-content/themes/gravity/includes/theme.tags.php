<?php
/**
 * Theme tags
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */


//----------------------------------------------------------------------
//-- Common tags
//----------------------------------------------------------------------

// Return true if current page need title
if ( !function_exists('gravity_need_page_title') ) {
	function gravity_need_page_title() {
		return !is_front_page() && apply_filters('gravity_filter_need_page_title', true);
	}
}

// Output string with the html layout (if not empty)
// (put it between 'before' and 'after' tags)
// Attention! This string may contain layout formed in any plugin (widgets or shortcodes output) and not require escaping to prevent damage!
if ( !function_exists('gravity_show_layout') ) {
	function gravity_show_layout($str, $before='', $after='') {
		if (trim($str) != '') {
			printf("%s%s%s", $before, $str, $after);
		}
	}
}

// Return logo images (if set)
if ( !function_exists('gravity_get_logo_image') ) {
	function gravity_get_logo_image($type='') {
		$logo_image = '';
		if (gravity_get_retina_multiplier(2) > 1)
			$logo_image = gravity_get_theme_option( 'logo'.(!empty($type) ? '_'.trim($type) : '').'_retina' );
		if (empty($logo_image)) 
			$logo_image = gravity_get_theme_option( 'logo'.(!empty($type) ? '_'.trim($type) : '') );
		return $logo_image;
	}
}

// Return header video (if set)
if ( !function_exists('gravity_get_header_video') ) {
	function gravity_get_header_video() {
		$video = '';
		if (apply_filters('gravity_header_video_enable', !wp_is_mobile() && is_front_page())) {
			if (gravity_check_theme_option('header_video')) {
				$video = gravity_get_theme_option('header_video');
				if ((int) $video > 0) $video = wp_get_attachment_url( $video );
			} else if (function_exists('get_header_video_url')) {
				$video = get_header_video_url();
			}
		}
		return $video;
	}
}


//----------------------------------------------------------------------
//-- Post parts
//----------------------------------------------------------------------

// Show post meta block: post date, author, categories, counters, etc.
if ( !function_exists('gravity_show_post_meta') ) {
	function gravity_show_post_meta($args=array()) {
		$args = array_merge(array(
			'categories' => true,
			'date' => true,
			'author' => true,
			'edit' => true,
			'seo' => false,
			'share' => true,
			'counters' => 'comments',
			'echo' => true
			), $args);

		if (!$args['echo']) ob_start();

		?><div class="post_meta"><?php
			// Post categories
			if ( !empty($args['categories'])) {
				$cats = get_post_type()=='post' ? get_the_category_list(', ') : apply_filters('gravity_filter_get_post_categories', '');
				if (!empty($cats)) {
					?>
					<span class="post_meta_item post_categories"><?php gravity_show_layout($cats); ?></span>
					<?php
				}
			}
			// Post date
			if ( !empty($args['date'])) {
				$dt = apply_filters('gravity_filter_get_post_date', gravity_get_date());
				if (!empty($dt)) {
					?>
					<span class="post_meta_item post_date<?php if (!empty($args['seo'])) echo ' date updated'; ?>"<?php if (!empty($args['seo'])) echo ' itemprop="datePublished"'; ?>><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html($dt); ?></a></span>
					<?php
				}
			}

			// Post counters
			gravity_show_layout(gravity_get_post_counters($args['counters']));

			// Socials share
			if ( !empty($args['share']) ) {
				gravity_show_share_links(array(
						'type' => 'drop',
						'caption' => esc_html__('Share', 'gravity'),
						'before' => '<span class="post_meta_item post_share">',
						'after' => '</span>'
					));
			}

			// Post author
			if ( !empty($args['author'])) {
				?>
				<span class="post_meta_item post_author"> <?php echo esc_html__('By', 'gravity'); ?>
                    <a  rel="author" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                        <?php echo esc_html(get_the_author()); ?>
                    </a>
                </span>
				<?php
			}

			// Edit page link
			if ( !empty($args['edit']) ) {
				edit_post_link( esc_html__( 'Edit', 'gravity' ), '<span class="post_meta_item post_edit icon-pencil">', '</span>' );
			}
		?></div><!-- .post_meta --><?php
		
		if (!$args['echo']) {
			$rez = ob_get_contents();
			ob_end_clean();
			return $rez;
		} else
			return '';
	}
}

// Show post featured block: image, video, audio, etc.
if ( !function_exists('gravity_show_post_featured') ) {
	function gravity_show_post_featured($args=array()) {

		$args = array_merge(array(
			'hover' => gravity_get_theme_option('image_hover'),	// Hover effect
			'class' => '',									// Additional Class for featured block
			'post_info' => '',								// Additional layout after hover
			'thumb_bg' => false,							// Put thumb image as block background
			'thumb_size' => '',								// Image size
			'thumb_only' => false,							// Display only thumb (without post formats)
			'show_no_image' => false,						// Display 'no-image.jpg' if post haven't thumbnail
			'seo' => gravity_is_on(gravity_get_theme_option('seo_snippets')),
			'singular' => is_singular()						// Current page is singular (true) or blog/shortcode (false)
			), $args);

		if ( post_password_required() ) return;

		$thumb_size = !empty($args['thumb_size']) ? $args['thumb_size'] : gravity_get_thumb_size(is_attachment() ? 'full' : (is_single() ? 'huge' : 'big'));
		$post_format = str_replace('post-format-', '', get_post_format());
		if ($args['thumb_bg']) {
			if (has_post_thumbnail()) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $thumb_size );
				$image = $image[0];
			} else if ($post_format == 'image') {
				$image = gravity_get_post_image();
				if (!empty($image)) 
					$image = gravity_add_thumb_size($image, $thumb_size);
			}
			if (empty($image))
				$image = gravity_get_no_image();
			if (!empty($image))
				$args['class'] .= ($args['class'] ? ' ' : '') . 'post_featured_bg' . ' ' . gravity_add_inline_style('background-image: url('.esc_url($image).');');
		}

		if ( $args['singular'] ) {
			
			if ( is_attachment() ) {
				?>
				<div class="post_featured post_attachment<?php if ($args['class']) echo ' '.esc_attr($args['class']); ?>">

					<?php if (!$args['thumb_bg']) echo wp_get_attachment_image( get_the_ID(), $thumb_size ); ?>

					<nav id="image-navigation" class="navigation image-navigation">
						<div class="nav-previous"><?php previous_image_link( false, '' ); ?></div>
						<div class="nav-next"><?php next_image_link( false, '' ); ?></div>
					</nav><!-- .image-navigation -->
				
				</div><!-- .post_featured -->
				
				<?php
				if ( has_excerpt() ) {
					?><div class="entry-caption"><?php the_excerpt(); ?></div><!-- .entry-caption --><?php
				}
	
			} else if ( has_post_thumbnail() || !empty($args['show_no_image']) ) {

				?>
				<div class="post_featured<?php if ($args['class']) echo ' '.esc_attr($args['class']); ?>"<?php if ($args['seo']) echo ' itemscope itemprop="image" itemtype="http://schema.org/ImageObject"'; ?>>
					<?php
					if (has_post_thumbnail() && $args['seo']) {
						$gravity_attr = gravity_getimagesize( wp_get_attachment_url( get_post_thumbnail_id() ) );
						?>
						<meta itemprop="width" content="<?php echo esc_attr($gravity_attr[0]); ?>">
						<meta itemprop="height" content="<?php echo esc_attr($gravity_attr[1]); ?>">
						<?php
					}
					if (!$args['thumb_bg']) {
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( $thumb_size, array(
								'alt' => get_the_title(),
								'itemprop' => 'url'
								)
							);
						} else {
							$image = gravity_get_no_image();
							if (!empty($image)) {
								?><img<?php if ($args['seo']) echo ' itemprop="url"'; ?> src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"><?php
							}
						}
					}
					?>
				</div><!-- .post_featured -->
				<?php

			}
	
		} else {
	
			if (empty($post_format)) $post_format='standard';
			$has_thumb = has_post_thumbnail();
			$post_info = !empty($args['post_info']) ? $args['post_info'] : '';

			if ($has_thumb || !empty($args['show_no_image']) || (!$args['thumb_only'] && in_array($post_format, array('gallery', 'image', 'audio', 'video')))) {
	
				?><div class="post_featured <?php echo (!empty($has_thumb) || $post_format == 'image' || !empty($args['show_no_image']) 
														? ('with_thumb' . ($args['thumb_only'] || !in_array($post_format, array('audio', 'video', 'gallery')) || ($post_format=='gallery' && ($has_thumb || $args['thumb_bg']))
																				? ' hover_'.esc_attr($args['hover'])
																				: (in_array($post_format, array('video')) ? ' hover_play' : '')
																			)
															)
														: 'without_thumb')
													. (!empty($args['class']) ? ' '.esc_attr($args['class']) : '');
								?>"><?php 

				// Put the thumb or gallery or image or video from the post
				if ( $args['thumb_bg'] ) {
					?><div class="mask"></div><?php
					if (!in_array($post_format, array('audio', 'video'))) {
						gravity_hovers_add_icons($args['hover']);
					}

				} else if ( $has_thumb ) {
					the_post_thumbnail( $thumb_size, array( 'alt' => get_the_title() ) );
					?><div class="mask"></div><?php
					if ($args['thumb_only'] || !in_array($post_format, array('audio', 'video'))) {
						gravity_hovers_add_icons($args['hover']);
					}
	
				} else if ($post_format == 'gallery' && !$args['thumb_only']) {

					if ( ($output = gravity_build_slider_layout(array('thumb_size' => $thumb_size, 'controls' => 'yes', 'pagination' => 'yes'))) != '' )
						gravity_show_layout($output);
	
				} else if ($post_format == 'image') {
					$image = gravity_get_post_image();
					if (!empty($image)) {
						$image = gravity_add_thumb_size($image, $thumb_size);
						?>
						<img src="<?php echo esc_url($image); ?>" alt="<?php echo get_the_title(); ?>">
						<div class="mask"></div>
						<?php 
						gravity_hovers_add_icons($args['hover'], array('image' => $image));
					}
				} else if (!empty($args['show_no_image']) && ($image=gravity_get_no_image())!='') {
					?>
					<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
					<div class="mask"></div>
					<?php 
					gravity_hovers_add_icons($args['hover']);
				}
				
				// Put video under the thumb
				if ($post_format == 'video' && !$args['thumb_only']) {
					//NEW
					$post_content = gravity_get_post_content();
					$post_content_parsed = $post_content;
					$video = gravity_get_post_video( $post_content, false );
						if ( empty( $video ) ) {
							$video = gravity_get_post_iframe( $post_content, false );
						}
						if ( empty( $video ) ) {
							// Only get video from the content if a playlist isn't present.
							$post_content_parsed = gravity_filter_post_content( $post_content );
							if ( false === strpos( $post_content_parsed, 'wp-playlist-script' ) ) {
								$videos = get_media_embedded_in_content( $post_content_parsed, array( 'video', 'object', 'embed', 'iframe' ) );
								if ( ! empty( $videos ) && is_array( $videos ) ) {
									$video = gravity_array_get_first( $videos, false );
								}
							}
						}
						if ( ! empty( $video ) ) {
							if ( $has_thumb ) {
								$video = gravity_make_video_autoplay( $video );
								?>
								<div class="post_video_hover" data-video="<?php echo esc_attr( $video ); ?>"></div>
								<?php
							}
							?>
							<div class="post_video video_frame">
								<?php
								if ( ! $has_thumb ) {
									gravity_show_layout( $video );
								}
								?>
							</div>
							<?php
						}
					//NEW
	
				}
				
				// Put audio over the thumb
				if ($post_format == 'audio' && !$args['thumb_only']) {

					$audio = gravity_get_post_audio('', false);
					if (empty($audio))
						$audio = gravity_get_post_iframe('', false);
					if (!empty($audio)) {
		
						?><div class="post_audio<?php if (strpos($audio, 'soundcloud')!==false) echo ' with_iframe'; ?>"><?php 
							// Add author and title
							$media_author = gravity_get_theme_option('media_author', '', false, get_the_ID());
							$media_title = gravity_get_theme_option('media_title', '', false, get_the_ID());
							if ( !empty($media_author) && !gravity_is_inherit($media_author) ) {
								?><div class="post_audio_author"><?php gravity_show_layout($media_author); ?></div><?php
							}
							if ( !empty($media_title) && !gravity_is_inherit($media_title) ) {
								?><h5 class="post_audio_title"><?php gravity_show_layout($media_title); ?></h5><?php
							}
							// Display audio
							gravity_show_layout($audio); 
						?></div><?php
					}

				}
				
				// Put optional info block over the thumb
				gravity_show_layout($post_info);
				?></div><?php
			}
		}
	}
}


// Return path to the 'no-image'
if ( !function_exists('gravity_get_no_image') ) {
	function gravity_get_no_image($no_image='') {
		static $img = '';
		if (empty($img)) {
			$img = gravity_get_theme_option( 'no_image' );
			// Remove false in next line if you want display theme-specific image in the posts without featured image
			if (false && empty($img)) $img = gravity_get_file_url('images/no-image.jpg');
		}
		if (!empty($img)) $no_image = $img;
		return $no_image;
	}
}


// Add featured image as background image to post navigation elements.
if ( !function_exists('gravity_add_bg_in_post_nav') ) {
	function gravity_add_bg_in_post_nav() {
		if ( ! is_single() ) return;
	
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );
		$css      = '';
		$noimg    = gravity_get_no_image();
		
		if ( is_attachment() && $previous->post_type == 'attachment' ) return;
	
		if ( $previous ) {
			if ( has_post_thumbnail( $previous->ID ) ) {
				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), gravity_get_thumb_size('med') );
				$img = $img[0];
			} else
				$img = $noimg;
			if ( !empty($img) )
				$css .= '.post-navigation .nav-previous a .nav-arrow { background-image: url(' . esc_url( $img ) . '); }';
			else
				$css .= '.post-navigation .nav-previous a .nav-arrow { background-color: rgba(128,128,128,0.05); border-color:rgba(128,128,128,0.1); }';
		}
	
		if ( $next ) {
			if ( has_post_thumbnail( $next->ID ) ) {
				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), gravity_get_thumb_size('med') );
				$img = $img[0];
			} else
				$img = $noimg;
			if ( !empty($img) )
				$css .= '.post-navigation .nav-next a .nav-arrow { background-image: url(' . esc_url( $img ) . '); }';
			else
				$css .= '.post-navigation .nav-next a .nav-arrow { background-color: rgba(128,128,128,0.05); border-color:rgba(128,128,128,0.1); }';
		}
	
		wp_add_inline_style( 'gravity-main', $css );
	}
}

// Show related posts
if ( !function_exists('gravity_show_related_posts') ) {
	function gravity_show_related_posts($args=array(), $style=1, $title='') {
		$args = array_merge(array(
			'suppress_filters' => true,
			'ignore_sticky_posts' => true,
			'posts_per_page' => 2,
			'orderby' => 'rand',
			'order' => 'DESC',
			'post_type' => 'post',
			'post_status' => 'publish',
			'post__not_in' => array(),
			'category__in' => array()
			), $args);
		
		$args['post__not_in'][] = get_the_ID();
		
		if (empty($args['category__in']) || is_array($args['category__in']) && count($args['category__in']) == 0) {
			$post_categories_ids = array();
			$post_cats = get_the_category(get_the_ID());
			if (is_array($post_cats) && !empty($post_cats)) {
				foreach ($post_cats as $cat) {
					$post_categories_ids[] = $cat->cat_ID;
				}
			}
			$args['category__in'] = $post_categories_ids;
		}
		
		$query = new WP_Query( $args );

		if ($query->found_posts > 0) {
			?>
			<section class="related_wrap">
				<h3 class="section title related_wrap_title"><?php
					if (!empty($title))
						echo esc_html($title);
					else
						esc_html_e('You May Also Like', 'gravity');
				?></h3>
				<div class="columns_wrap posts_container">
					<?php
					while ( $query->have_posts() ) { $query->the_post();
						?><div class="column-1_<?php echo intval(max(2, $args['posts_per_page'])); ?>"><?php
							 get_template_part('templates/related-posts', $style);
						?></div><?php
					}
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php
		}
	}
}


// Show portfolio posts
if ( !function_exists('gravity_show_portfolio_posts') ) {
	function gravity_show_portfolio_posts($args=array()) {
		$args = array_merge(array(
			'cat' => 0,
			'parent_cat' => 0,
			'taxonomy' => 'category',
			'post_type' => 'post',
			'page' => 1,
			'sticky' => false,
			'blog_style' => '',
			'echo' => true
			), $args);

		$blog_style = explode('_', empty($args['blog_style']) ? gravity_get_theme_option('blog_style') : $args['blog_style']);
		$style = $blog_style[0];
		$columns = empty($blog_style[1]) ? 2 : max(2, $blog_style[1]);

		if ( !$args['echo'] ) {
			ob_start();

			$q_args = array(
				'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
			);
			$q_args = gravity_query_add_posts_and_cats($q_args, '', $args['post_type'], $args['cat'], $args['taxonomy']);
			if ($args['page'] > 1) {
				$q_args['paged'] = $args['page'];
				$q_args['ignore_sticky_posts'] = true;
			}
			$ppp = gravity_get_theme_option('posts_per_page');
			if ((int) $ppp != 0)
				$q_args['posts_per_page'] = (int) $ppp;

			query_posts( $q_args );
		}

		// Show posts
		$class = sprintf('portfolio_wrap posts_container portfolio_%s', $columns)
				. ($style!='portfolio' ? sprintf(' %s_wrap %s_%s', $style, $style, $columns) : '');
		if ($args['sticky']) {
			?><div class="columns_wrap sticky_wrap"><?php	
		} else {
			?><div class="<?php echo esc_attr($class); ?>"><?php	
		}
	
		while ( have_posts() ) { the_post(); 
			if ($args['sticky'] && !is_sticky()) {
				$args['sticky'] = false;
				?></div><div class="<?php echo esc_attr($class); ?>"><?php
			}
			get_template_part( 'content', $args['sticky'] && is_sticky() ? 'sticky' : ($style == 'gallery' ? 'portfolio-gallery' : $style) );
		}
		
		?></div><?php
	
		gravity_show_pagination();
		
		if (!$args['echo']) {
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
}

// AJAX handler for the gravity_ajax_get_posts action
if ( !function_exists( 'gravity_ajax_get_posts_callback' ) ) {
	add_action('wp_ajax_gravity_ajax_get_posts',			'gravity_ajax_get_posts_callback');
	add_action('wp_ajax_nopriv_gravity_ajax_get_posts',	'gravity_ajax_get_posts_callback');
	function gravity_ajax_get_posts_callback() {
		if ( !wp_verify_nonce( gravity_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$id = !empty($_REQUEST['blog_template']) ? $_REQUEST['blog_template'] : 0;
		if ($id > 0) {
			gravity_storage_set('blog_archive', true);
			gravity_storage_set('blog_mode', 'blog');
			gravity_storage_set('options_meta', get_post_meta($id, 'gravity_options', true));
		}

		$response = array(
			'error'=>'', 
			'data' => gravity_show_portfolio_posts(array(
							'cat' => (int) $_REQUEST['cat'],
							'parent_cat' => (int) $_REQUEST['parent_cat'],
							'page' => (int) $_REQUEST['page'],
							'post_type' => trim($_REQUEST['post_type']),
							'taxonomy' => trim($_REQUEST['taxonomy']),
							'blog_style' => trim($_REQUEST['blog_style']),
							'echo' => false
							)
						)
		);

		if (empty($response['data'])) {
			$response['error'] = esc_html__('Sorry, but nothing matched your search criteria.', 'gravity');
		}
		echo json_encode($response);
		die();
	}
}


// Show pagination
if ( !function_exists('gravity_show_pagination') ) {
	function gravity_show_pagination() {
		global $wp_query;
		// Pagination
		$pagination = gravity_get_theme_option('blog_pagination');
		if ($pagination == 'pages') {
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => esc_html__( '<', 'gravity' ),
				'next_text' => esc_html__( '>', 'gravity' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'gravity' ) . ' </span>',
			) );
		} else if ($pagination == 'more' || $pagination == 'infinite') {
			$page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
			if ($page_number < $wp_query->max_num_pages) {
				?>
				<div class="nav-links-more<?php if ($pagination == 'infinite') echo ' nav-links-infinite'; ?>">
					<a class="nav-load-more" href="#" 
						data-page="<?php echo esc_attr($page_number); ?>" 
						data-max-page="<?php echo esc_attr($wp_query->max_num_pages); ?>"
						><span><?php esc_html_e('Load more posts', 'gravity'); ?></span></a>
				</div>
				<?php
			}
		} else if ($pagination == 'links') {
			?>
			<div class="nav-links-old">
				<span class="nav-prev"><?php previous_posts_link( is_search() ? esc_html__('Previous posts', 'gravity') : esc_html__('Newest posts', 'gravity') ); ?></span>
				<span class="nav-next"><?php next_posts_link( is_search() ? esc_html__('Next posts', 'gravity') : esc_html__('Older posts', 'gravity'), $wp_query->max_num_pages ); ?></span>
			</div>
			<?php
		}
	}
}
?>