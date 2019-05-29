<?php
/**
 * Social share and profiles
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Return share URL for the specified network
if ( !function_exists( 'trx_addons_get_share_url' ) ) {
	function trx_addons_get_share_url($soc='') {
		$list = array(
			'blogger' =>		'http://www.blogger.com/blog_this.pyra?t&u={link}&n={title}',
			'bobrdobr' =>		'http://bobrdobr.ru/add.html?url={link}&title={title}&desc={descr}',
			'delicious' =>		'http://delicious.com/save?url={link}&title={title}&note={descr}',
			'designbump' =>		'http://designbump.com/node/add/drigg/?url={link}&title={title}',
			'designfloat' =>	'http://www.designfloat.com/submit.php?url={link}',
			'digg' =>			'http://digg.com/submit?url={link}',
			'evernote' =>		'https://www.evernote.com/clip.action?url={link}&title={title}',
			'email' =>			'mailto:'.get_bloginfo('admin_email'),
//			'facebook' =>		'http://www.facebook.com/sharer.php?s=100&p[url]={link}&p[title]={title}&p[summary]={descr}&p[images][0]={image}',
			'facebook' =>		'http://www.facebook.com/sharer.php?u={link}',
			'friendfeed' =>		'http://www.friendfeed.com/share?title={title} - {link}',
			'google' =>			'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk={link}&title={title}&annotation={descr}',
			'gplus' => 			'https://plus.google.com/share?url={link}', 
			'identi' => 		'http://identi.ca/notice/new?status_textarea={title} - {link}', 
			'juick' => 			'http://www.juick.com/post?body={title} - {link}',
			'linkedin' => 		'http://www.linkedin.com/shareArticle?mini=true&url={link}&title={title}', 
			'liveinternet' =>	'http://www.liveinternet.ru/journal_post.php?action=n_add&cnurl={link}&cntitle={title}',
			'livejournal' =>	'http://www.livejournal.com/update.bml?event={link}&subject={title}',
			'mail' =>			'http://connect.mail.ru/share?url={link}&title={title}&description={descr}&imageurl={image}',
			'memori' =>			'http://memori.ru/link/?sm=1&u_data[url]={link}&u_data[name]={title}', 
			'mister-wong' =>	'http://www.mister-wong.ru/index.php?action=addurl&bm_url={link}&bm_description={title}', 
			'mixx' =>			'http://chime.in/chimebutton/compose/?utm_source=bookmarklet&utm_medium=compose&utm_campaign=chime&chime[url]={link}&chime[title]={title}&chime[body]={descr}', 
			'moykrug' =>		'http://share.yandex.ru/go.xml?service=moikrug&url={link}&title={title}&description={descr}',
			'myspace' =>		'http://www.myspace.com/Modules/PostTo/Pages/?u={link}&t={title}&c={descr}', 
			'newsvine' =>		'http://www.newsvine.com/_tools/seed&save?u={link}&h={title}',
			'odnoklassniki' =>	'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl={link}&title={title}', 
			'pikabu' =>			'http://pikabu.ru/add_story.php?story_url={link}',
			'pinterest' =>		'json:{"link": "http://pinterest.com/pin/create/button/", "script": "//assets.pinterest.com/js/pinit.js", "style": "", "attributes": {"data-pin-do": "buttonPin", "data-pin-media": "{image}", "data-pin-url": "{link}", "data-pin-description": "{title}", "data-pin-custom": "true","nopopup": "true"}}',
			'posterous' =>		'http://posterous.com/share?linkto={link}&title={title}',
			'postila' =>		'http://postila.ru/publish/?url={link}&agregator=themerex',
			'reddit' =>			'http://reddit.com/submit?url={link}&title={title}', 
			'rutvit' =>			'http://rutvit.ru/tools/widgets/share/popup?url={link}&title={title}', 
			'stumbleupon' =>	'http://www.stumbleupon.com/submit?url={link}&title={title}', 
			'surfingbird' =>	'http://surfingbird.ru/share?url={link}', 
			'technorati' =>		'http://technorati.com/faves?add={link}&title={title}', 
			'tumblr' =>			'http://www.tumblr.com/share?v=3&u={link}&t={title}&s={descr}', 
			'twitter' =>		'https://twitter.com/intent/tweet?text={title}&url={link}',
			'vk' =>				'http://vk.com/share.php?url={link}&title={title}&description={descr}',
			'vk2' =>			'http://vk.com/share.php?url={link}&title={title}&description={descr}',
			'vkontakte' =>		'http://vk.com/share.php?url={link}&title={title}&description={descr}',
			'webdiscover' =>	'http://webdiscover.ru/share.php?url={link}',
			'yahoo' =>			'http://bookmarks.yahoo.com/toolbar/savebm?u={link}&t={title}&d={descr}',
			'yandex' =>			'http://zakladki.yandex.ru/newlink.xml?url={link}&name={title}&descr={descr}',
			'ya' =>				'http://my.ya.ru/posts_add_link.xml?URL={link}&title={title}&body={descr}',
			'yosmi' =>			'http://yosmi.ru/index.php?do=share&url={link}'
		);
		return $soc 
					? (isset($list[$soc]) 
						? $list[$soc] 
						: '') 
					: $list;
	}
}


// Return (and show) share social links
if (!function_exists('trx_addons_get_share_links')) {
	function trx_addons_get_share_links($args) {

		$args = array_merge(array(
			'post_id' => 0,						// post ID
			'post_link' => '',					// post link
			'post_title' => '',					// post title
			'post_descr' => '',					// post descr
			'post_thumb' => '',					// post featured image
			'size' => 'tiny',					// icons size: tiny|small|medium|big
			'style' => 'icons',					// style for show icons: icons|images|bg
			'type' => 'block',					// share block type: list|block|drop
			'popup' => true,					// open share url in new window or in popup window
			'counters' => true,					// show share counters
			'direction' => 'horizontal',		// share block direction
			'caption' => esc_html__('Share:', 'trx_addons'),			// share block caption
			'before' => '',						// HTML-code before the share links
			'after' => '',						// HTML-code after the share links
			'echo' => true						// if true - show on page, else - only return as string
			), $args);

		
		if (empty($args['post_id']))	$args['post_id'] = get_the_ID();
		if (empty($args['post_link']))	$args['post_link'] = get_permalink();
		if (empty($args['post_title']))	$args['post_title'] = get_the_title();
		if (empty($args['post_descr']))	$args['post_descr'] = strip_tags(get_the_excerpt());
		if (empty($args['post_thumb']))	{
			$args['post_thumb'] = trx_addons_get_attachment_url( get_post_thumbnail_id( $args['post_id'] ), trx_addons_get_thumb_size('big') );
		}
		
		$output = '';

		global $TRX_ADDONS_STORAGE;

		foreach ($TRX_ADDONS_STORAGE['options'] as $k=>$v) {
			if (substr($k, 0, 6) != 'share_' || !isset($v['std']) || empty($v['val'])) continue;
			$tmp = explode('_', $k);
			$sn = $tmp[1];
			$url = $v['val'];

			if (substr($url, 0, 5) == 'json:') {
				$url = json_decode(substr($url, 5), true);
				if (is_null($url))
					continue;
			}

			$icon = $args['style']=='icons' ? 'trx_addons_icon-'.$sn : trx_addons_get_file_url('images/socials/'.$sn.'.png');
			$link = str_replace(
				array('{id}', '{link}', '{title}', '{descr}', '{image}'),
				array(
					urlencode($args['post_id']),
					urlencode($args['post_link']),
					urlencode(strip_tags($args['post_title'])),
					urlencode(strip_tags($args['post_descr'])),
					urlencode($args['post_thumb'])
					),
				(is_array($url) ? $url['link']: $url));
			$output .= '<span class="social_item'.(!empty($args['popup']) ? ' social_item_popup' : '').'">'
						. '<a href="'.esc_url($link).'"'
						. ' class="social_icons social_'.esc_attr($sn).'"'
						. ($args['style']=='bg' ? ' style="background-image: url('.esc_url($icon).');"' : '')
						. ($args['popup'] ? ' data-link="' . esc_url($link) .'"' : ' target="_blank"')
						. ($args['counters'] ? ' data-count="'.esc_attr($sn).'"' : '') ;

			if (isset($url['attributes']) && is_array($url['attributes'])) {
				foreach($url['attributes'] as $k2=>$v2) {
					$v2 = str_replace(
						array('{id}', '{link}', '{title}', '{descr}', '{image}'),
						array(
							$k2 == 'href' ? urlencode($args['post_id']) : $args['post_id'],
							$k2 == 'href' ? urlencode($args['post_link']) : $args['post_link'],
							$k2 == urlencode(strip_tags($args['post_title'])),
							$k2 == urlencode(strip_tags($args['post_descr'])),
							$k2 == 'href' ? urlencode($args['post_thumb']) : $args['post_thumb']
						),
						$v2);
					$output .= " {$k2}=\"" . ($k2=='href' ? esc_url($v2) : esc_attr($v2)) . '"';
				}
			}
			$output .= '>'
						. ($args['style']=='icons'
							? '<span class="' . esc_attr($icon) . '"></span>'
							: ($args['style']=='images'
								? '<img src="'.esc_url($icon).'" alt="'.esc_attr($sn).'" />'
								: '<span class="social_hover" style="background-image: url('.esc_url($icon).');"></span>'
								)
							)
						//. ($args['counters'] ? '<span class="share_counter">0</span>' : '')
						. ($args['type']=='drop' ? '<i>' . trim($sn) . '</i>' : '')
					. '</a>'
				. '</span>';

			if (!empty($url['script']))
				wp_enqueue_script( "trx_addons_share_{$sn}", $url['script'], array(), null, true );
		}
		
		if (!empty($output)) {
			$output = $args['before']
						. '<div class="socials_wrap socials_share socials_size_'.esc_attr($args['size']).' socials_type_'.esc_attr($args['type']).' socials_dir_'.esc_attr($args['direction']).'">'
							. ($args['caption']!='' 
								? ($args['type']=='drop' 
									? '<a href="#" class="socials_caption"><span class="socials_caption_label">'.($args['caption']).'</span></a>'
									: '<span class="socials_caption">'.($args['caption']).'</span>')
								: '')
							. '<span class="social_items">'
								. $output
							. '</span>'
						. '</div>'
					. $args['after'];
			if ($args['echo']) echo trim($output);
		}
		return $output;
	}
}


// Return social icons links
if (!function_exists('trx_addons_get_socials_links')) {
	function trx_addons_get_socials_links($style='icons') {
		global $TRX_ADDONS_STORAGE;
		$icons = array();
		foreach ($TRX_ADDONS_STORAGE['options'] as $k=>$v) {
			if (substr($k, 0, 8) != 'socials_' || !isset($v['std']) || empty($v['val'])) continue;
			$tmp = explode('_', $k);
			$icons[] = array(
				'name'	=> $tmp[1],
				'url'	=> $v['val']
			);
		}
		return trx_addons_get_socials_links_custom($icons, $style);
	}
}


// Return social icons links from array
if (!function_exists('trx_addons_get_socials_links_custom')) {
	function trx_addons_get_socials_links_custom($icons, $style='icons') {
		$output = '';
		if (is_string($icons)) {
			$tmp = explode("\n", $icons);
			$icons = array();
			foreach ($tmp as $str) {
				$tmp2 = explode("=", trim(chop($str)));
				if (count($tmp2)==2) {
					$icons[] = array(
						'name' => trim($tmp2[0]),
						'url' => trim($tmp2[1])
					);
				}
			}
		}
		foreach ($icons as $social) {
			$sn = $social['name'];
			$url = $social['url'];
			$icon = $style=='icons' ? 'trx_addons_icon-'.$sn : trx_addons_get_file_url('images/socials/'.$sn.'.png');
			$output .= '<span class="social_item">'
					. '<a href="'.esc_url($url).'" target="_blank" class="social_icons social_'.esc_attr($sn).'"'
					. ($style=='bg' ? ' style="background-image: url('.esc_url($icon).');"' : '')
					. '>'
					. ($style=='icons' 
						? '<span class="' . esc_attr($icon) . '"></span>' 
						: ($style=='images' 
							? '<img src="'.esc_url($icon).'" alt="" />' 
							: '<span class="social_hover" style="background-image: url('.esc_url($icon).');"></span>'))
					. '</a>'
					. '</span>';
		}
		return $output;
	}
}


// Add facebook meta tags for post/page sharing
if (!function_exists('trx_addons_facebook_og_tags')) {
	add_action( 'wp_head', 'trx_addons_facebook_og_tags', 5 );
	function trx_addons_facebook_og_tags() {
		global $wp_query;
		if ( is_admin() || !is_singular() || (isset($wp_query->is_posts_page) && $wp_query->is_posts_page==1)) return;
		if ( has_post_thumbnail(get_the_ID()) ) {
			echo '<meta property="og:image" content="' . esc_url( trx_addons_get_attachment_url( get_post_thumbnail_id( get_the_ID() ), 'full' ) ) . '"/>' . "\n";
		}
		//echo '<meta property="og:title" content="' . esc_attr( strip_tags( get_the_title() ) ) . '" />' . "\n"
		//	.'<meta property="og:description" content="' . esc_attr( strip_tags( strip_shortcodes( get_the_excerpt()) ) ) . '" />' . "\n"
		//	.'<meta property="og:url" content="' . esc_attr( get_permalink() ) . '" />';
	}
}
?>