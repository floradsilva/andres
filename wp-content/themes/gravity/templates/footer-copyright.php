<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package WordPress
 * @subpackage GRAVITY
 * @since GRAVITY 1.0.10
 */

// Copyright area
$gravity_footer_scheme =  gravity_is_inherit(gravity_get_theme_option('footer_scheme')) ? gravity_get_theme_option('color_scheme') : gravity_get_theme_option('footer_scheme');
$gravity_copyright_scheme = gravity_is_inherit(gravity_get_theme_option('copyright_scheme')) ? $gravity_footer_scheme : gravity_get_theme_option('copyright_scheme');

$gravity_copyright = gravity_prepare_macros(gravity_get_theme_option('copyright'));
if (!empty($gravity_copyright)) {
	?>
	<div class="footer_copyright_wrap scheme_<?php echo esc_attr($gravity_copyright_scheme); ?>">
		<div class="footer_copyright_inner">
			<div class="content_wrap">
				<div class="copyright_text"><?php
					// Replace {{...}} and [[...]] on the <i>...</i> and <b>...</b>
					if (!empty($gravity_copyright)) {
						// Replace {date_format} on the current date in the specified format
						if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $gravity_copyright, $gravity_matches)) {
							$gravity_copyright = str_replace($gravity_matches[1], date(str_replace(array('{', '}'), '', $gravity_matches[1])), $gravity_copyright);
						}
						// Display copyright
						echo wp_kses_data(nl2br($gravity_copyright));
					}
					?></div>
			</div>
		</div>
	</div>
	<?php
}
