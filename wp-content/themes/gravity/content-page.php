<?php
/**
 * The default template to display the content of the single page
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_single post_type_page' ); ?>>

	<?php
	// Now featured image used as header's background
	?>

	<div class="post_content entry-content">
		<?php
			the_content( );

			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'gravity' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'gravity' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

</article>
