<?php
/**
 * @param $type
 * Display WoowBot Icon ball
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly
add_action('wp_footer', 'woo_chatbot_load_footer_html');
function woo_chatbot_load_footer_html()
{
    if (get_option('disable_woo_chatbot') != 1 && woo_chatbot_load_controlling() == true) {
        ?>
        <style>
            <?php if(get_option('enable_woo_chatbot_custom_color')==1){?>

            /*
            START
            GET COLOR FROM PLUGIN ADMIN*/

            #woo-chatbot-chat-container, .woo-chatbot-product-description, .woo-chatbot-product-description p,.woo-chatbot-product-quantity label, .woo-chatbot-product-variable label {
                color: <?php echo get_option('woo_chatbot_text_color')?> !important;
            }

            #woo-chatbot-chat-container a {
                color: <?php echo get_option('woo_chatbot_link_color')?> !important;
            }
            #woo-chatbot-chat-container a:hover {
                color: <?php echo get_option('woo_chatbot_link_hover_color')?> !important;
            }


            ul.woo-chatbot-messages-container > li.woo-chatbot-msg .woo-chatbot-paragraph,
            .woo-chatbot-agent-profile .woo-chatbot-bubble {
                color: <?php echo get_option('woo_chatbot_bot_msg_text_color')?> !important;
                background-color: <?php echo get_option('woo_chatbot_bot_msg_bg_color')?> !important;
                word-break: break-word;
            }
			
			
			span.qcld-chatbot-product-category, span.qcld-chatbot-support-items, span.qcld-chatbot-wildcard, span.qcld-chatbot-suggest-email, span.qcld-chatbot-reset-btn, #woo-chatbot-loadmore, .woo-chatbot-shortcode-template-container span.qcld-chatbot-product-category, .woo-chatbot-shortcode-template-container span.qcld-chatbot-support-items, .woo-chatbot-shortcode-template-container span.qcld-chatbot-wildcard, .woo-chatbot-shortcode-template-container span.woo-chatbot-card-button, .woo-chatbot-shortcode-template-container span.qcld-chatbot-suggest-email, span.qcld-chatbot-suggest-phone, .woo-chatbot-shortcode-template-container span.qcld-chatbot-reset-btn, .woo-chatbot-shortcode-template-container #woo-chatbot-loadmore, .woo-chatbot-ball-cart-items {
                color: <?php echo get_option('woo_chatbot_bot_buttons_color')?> !important;
                background-color: <?php echo get_option('woo_chatbot_buttons_bg_color')?> !important;
               background-image: none !important;
            }
			
			

            li.woo-chat-user-msg .woo-chatbot-paragraph {
                color: <?php echo get_option('woo_chatbot_user_msg_text_color')?> !important;
                background-color: <?php echo get_option('woo_chatbot_user_msg_bg_color')?> !important;
            }

            ul.woo-chatbot-messages-container > li.woo-chatbot-msg > .woo-chatbot-paragraph:before,
            .woo-chatbot-bubble:before {
                border-right: 10px solid <?php echo get_option('woo_chatbot_bot_msg_bg_color')?> !important;

            }

            ul.woo-chatbot-messages-container > li.woo-chat-user-msg > .woo-chatbot-paragraph:before {
                border-left: 10px solid <?php echo get_option('woo_chatbot_user_msg_bg_color')?> !important;
            }
            
            /*
              END
              GET COLOR FROM PLUGIN ADMIN*/
            <?php
            }
            ?>
            ul.woo-chatbot-messages-container > li.woo-chatbot-msg .woo-chatbot-paragraph.woo-chatbot-proactive{
                color: <?php echo get_option('woo_chatbot_proactive_text_color')?> !important;
                background-color: <?php echo get_option('woo_chatbot_proactive_bg_color')?> !important;
            }
			
            <?php

            if(get_option('woo_chatbot_custom_css')!="") {
            echo get_option('woo_chatbot_custom_css');
            }
            ?>
			
			
			 <?php if (get_option('qcld_woo_chatbot_custom_icons') == 1) { ?>
			 		<?php if (get_option('qcld_woo_chatbot_custom_icon_help') !="") { ?>
					.woo-chatbot-tab-nav ul li a[data-option="help"] {
						background: url(<?php echo get_option('qcld_woo_chatbot_custom_icon_help'); ?>) no-repeat;
						background-position:center center !important;
						background-size:contain;
					}
					 <?php } ?>
					<?php if (get_option('qcld_woo_chatbot_custom_icon_support') !="") { ?>
					.woo-chatbot-tab-nav ul li a[data-option="support"] {
						background: url(<?php echo get_option('qcld_woo_chatbot_custom_icon_support'); ?>) no-repeat;
						background-position:center center !important;
						background-size:contain;
					}
					 <?php } ?>
					
					<?php if (get_option('qcld_woo_chatbot_custom_icon_recent') !="") { ?>
					.woo-chatbot-tab-nav ul li a[data-option="recent"] {
						background: url(<?php echo get_option('qcld_woo_chatbot_custom_icon_recent'); ?>) no-repeat;
						background-position:center center !important;
						background-size:contain;
					}
					 <?php } ?>
					
					<?php if (get_option('qcld_woo_chatbot_custom_icon_cart') !="") { ?>
					.woo-chatbot-tab-nav ul li a[data-option="cart"] {
						background: url(<?php echo get_option('qcld_woo_chatbot_custom_icon_cart'); ?>) no-repeat;
						background-position:center center !important;
						background-size:contain;
					}
					 <?php } ?>
					
					<?php if (get_option('qcld_woo_chatbot_custom_icon_chat') !="") { ?>
					.woo-chatbot-tab-nav ul li a[data-option="chat"] {
						background: url(<?php echo get_option('qcld_woo_chatbot_custom_icon_chat'); ?>) no-repeat;
						background-position:center center !important;
						background-size:contain;
					}
					 <?php } ?>
					
			 <?php } ?>

        </style>
        <?php if (get_option('qcld_woo_chatbot_change_bg') == 1) {
            if (get_option('qcld_woo_chatbot_board_bg_path') != "") {
                $qcld_woo_chatbot_board_bg_path = get_option('qcld_woo_chatbot_board_bg_path');
            } else {
                $qcld_woo_chatbot_board_bg_path = QCLD_WOOCHATBOT_IMG_URL . 'background/background.png';
            }
            ?>
            <style>
                .woo-chatbot-container {
                    background-image: url(<?php echo $qcld_woo_chatbot_board_bg_path;?>) !important;
                }
				.woo-chatbot-board-container, .woo-chatbot-template-01 .woo-chatbot-content{
					background:none !important;
				}
            </style>
        <?php }
        $woo_chatbot_enable_rtl = "";
        if (get_option('enable_woo_chatbot_rtl')) {
            $woo_chatbot_enable_rtl .= "woo-chatbot-rtl";
        }
        $woo_chatbot_enable_mobile_screen = "";
        if (get_option('enable_woo_chatbot_mobile_full_screen') == 1) {
            $woo_chatbot_enable_mobile_screen .= "woo-chatbot-mobile-full-screen";
        }
        ?>
        <div id="woo-chatbot-chat-container"
             class="<?php echo 'woo-chatcontainer_'.get_option('qcld_woo_chatbot_theme').' '.$woo_chatbot_enable_rtl . ' ' . $woo_chatbot_enable_mobile_screen; ?>">
            <div id="woo-chatbot-integration-container">
                <div class="woo-chatbot-integration-button-container">
                    <?php if (get_option('enable_woo_chatbot_skype_floating_icon') == 1) { ?>
                        <a href="skype:<?php echo get_option('enable_woo_chatbot_skype_id'); ?>?chat"><span
                                    class="inetegration-skype-btn" title="<?php _e('Skype', 'woochatbot'); ?>"> </span></a>
                    <?php } ?>
                    <?php if (get_option('enable_woo_chatbot_floating_whats') == 1) { ?>
                        <a href="<?php echo 'https://api.whatsapp.com/send?phone=' . get_option('qlcd_woo_chatbot_whats_num'); ?>"
                           target="_blank"><span class="intergration-whats"
                                                 title="<?php _e('WhatsApp', 'woochatbot'); ?>"></span></a>
                    <?php } ?>
                    <?php if (get_option('enable_woo_chatbot_floating_viber') == 1) { ?>
                        <a href="<?php echo 'https://live.viber.com/#/' . get_option('qlcd_woo_chatbot_viber_acc'); ?>"
                           target="_blank"><span class="intergration-viber"
                                                 title="<?php _e('Viber', 'woochatbot'); ?>"></span></a>
                    <?php } ?>
                    <?php if (get_option('enable_woo_chatbot_floating_phone') == 1 && get_option('qlcd_woo_chatbot_phone') != "") { ?>
                        <a href="tel:<?php echo get_option('qlcd_woo_chatbot_phone'); ?>"><span
                                    class="intergration-phone"
                                    title="<?php _e('Phone', 'woochatbot'); ?>"> </span></a>
                    <?php } ?>

                    <?php if (get_option('enable_woo_chatbot_floating_livechat') == 1 && get_option('enable_woo_chatbot_floating_livechat') != "") { ?>
                        <?php if(get_option('woo_custom_icon_livechat')!=''): ?>
                            <a href="#" id="woobot_live_chat_floating_btn" title="Live Chat">
                                <span style="background:url(<?php echo get_option('woo_custom_icon_livechat'); ?>);background-size: 40px;"></span>
                            </a>
                        <?php else: ?>
                            <a href="#" id="woobot_live_chat_floating_btn" title="Live Chat">
                                <span><i class="fa fa-commenting" aria-hidden="true"></i></span>
                            </a>
                        <?php endif; ?>
                    <?php } ?>

                    <?php if (get_option('enable_woo_chatbot_floating_link') == 1 && get_option('qlcd_woo_chatbot_weblink') != "") { ?>
                        <a href="<?php echo get_option('qlcd_woo_chatbot_weblink'); ?>" target="_blank"><span
                                    class="intergration-weblink" title="<?php _e('Web Link', 'woochatbot'); ?>"></span></a>
                    <?php } ?>
                </div>
            </div>
            <?php
            //Get Woocommerce cart
            global $woocommerce;
            $cart_items_number = $woocommerce->cart->cart_contents_count;
            $qcld_woo_chatbot_theme = get_option('qcld_woo_chatbot_theme');
            if (file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/style.css')) {
                wp_register_style('qcld-woo-chatbot-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/templates/' . $qcld_woo_chatbot_theme . '/style.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
                wp_enqueue_style('qcld-woo-chatbot-style');
            }
            if (file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/template.php')) {
                require_once(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/template.php');
            } else {
                echo "<h2>" . __('No WooWBot Theme Found!', 'woochatbot') . "</h2>";
            }
            ?>
            <?php
            if (woo_chatbot_notification_msg_handle()) {

                ?>
                <div id="woo-chatbot-notification-container" class="woo-chatbot-notification-container">
                    <div class="woo-chatbot-notification-controller"> <span class="woo-chatbot-notification-close">
      <?php _e('X', 'woochatbot'); ?>
      </span></div>
                    <?php
                    $testingTip = "";
                    if (get_option('woo_chatbot_agent_image') == "custom-agent.png") {
                        $woo_chatbot_custom_agent_path = get_option('woo_chatbot_custom_agent_path');
                    } else if (get_option('woo_chatbot_agent_image') != "custom-agent.png") {
                        $woo_chatbot_custom_agent_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_agent_image');
                    } else {
                        $woo_chatbot_custom_agent_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
                    }
                    ?>
                    <div class="woo-chatbot-notification-agent-profile">
                        <div class="woo-chatbot-notification-widget-avatar"><img
                                    src="<?php echo $woo_chatbot_custom_agent_path; ?>" alt=""></div>
                        <div class="woo-chatbot-notification-welcome"><?php echo randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_welcome'))) . ' <strong>' . get_option('qlcd_woo_chatbot_host') . '</strong>'; ?></div>
                    </div>
                    <?php $notifications = qcld_woo_chatbot_func_str_replace(unserialize(get_option('qlcd_woo_chatbot_notifications'))); ?>
                    <div class="woo-chatbot-notification-message"><?php echo $notifications[0]; ?></div>
                </div>
            <?php } ?>
            <!--woo-chatbot-board-container-->
            <div id="woo-chatbot-ball" class="">
                <div class="woo-chatbot-ball">
                    <div class="woo-chatbot-ball-animator woo-chatbot-ball-animation-switch"></div>
                    <?php
                    if (get_option('woo_chatbot_icon') == "custom.png") {
                        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_icon_path');
                    } else if (get_option('woo_chatbot_icon') != "custom.png") {
                        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_icon');
                    } else {
                        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom.png';
                    }
                    ?>
                    <img src="<?php echo $woo_chatbot_custom_icon_path; ?>"
                         alt="WooChatIcon">
                    <?php if (get_option('disable_woo_chatbot_cart_item_number') != 1) { ?>
                        <span class="woo-chatbot-ball-cart-items"><?php echo $cart_items_number; ?></span>
                    <?php } ?>
                </div>
            </div>
            <?php
            $fb_app_id = get_option('qlcd_woo_chatbot_fb_app_id');
            $fb_page_id = get_option('qlcd_woo_chatbot_fb_page_id');
            $fb_mgs_color = get_option('qlcd_woo_chatbot_fb_color') != '' ? get_option('qlcd_woo_chatbot_fb_color') : '#0084ff';
            $fb_mgs_in = get_option('qlcd_woo_chatbot_fb_in_msg') != '' ? get_option('qlcd_woo_chatbot_fb_in_msg') : 'You are welcome';
            $fb_mgs_out = get_option('qlcd_woo_chatbot_fb_out_msg') != '' ? get_option('qlcd_woo_chatbot_fb_out_msg') : 'You are not logged in';
            if (get_option('enable_woo_chatbot_messenger') == 1 && get_option('enable_woo_chatbot_messenger_floating_icon') == 1) {
                ?>
                <!--                woo-chatbot-board-container-->
                <script>
                    window.fbAsyncInit = function () {
                        FB.init({
                            appId: '<?php echo $fb_app_id;?>',
                            autoLogAppEvents: true,
                            xfbml: true,
                            version: 'v2.12'
                        });
                    };
                    (function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                </script>
                <div class="fb-customerchat"
                     page_id="<?php echo $fb_page_id; ?>"
                     greeting_dialog_display="hide"
                     theme_color="<?php echo $fb_mgs_color; ?>"
                     logged_in_greeting="<?php echo $fb_mgs_in; ?>"
                     logged_out_greeting="<?php echo $fb_mgs_out; ?>"></div>
                <?php
            }
            ?>
            <!--container-->
            <!--woo-chatbot-ball-wrapper-->
        </div>
        <audio id="woo-chatbot-proactive-audio" <?php if (get_option('enable_woo_chatbot_sound_initial') == 1) {
            echo "autoplay";
        } ?>>
            <source src="<?php echo QCLD_WOOCHATBOT_IMG_URL; ?>pro-active.mp3">
            </source>
        </audio>
        <?php
    } else {
        ?>
        <script>
            var openingHourIsFn = 1;
        </script>
        <?php
    }
}

//woo_chatbot load control handler.
function woo_chatbot_load_controlling()
{
    $woo_chatbot_load = true;
    if (woo_chatbot_is_mobile() && get_option('disable_woo_chatbot_on_mobile') == 1) {
        $woo_chatbot_load = false;
    }
    if (get_option('woo_chatbot_show_home_page') == 'off' && is_home()) {
        $woo_chatbot_load = false;
    }
    if (get_option('woo_chatbot_show_posts') == 'off' && 'post' == get_post_type()) {
        $woo_chatbot_load = false;
    }
    if (get_option('woo_chatbot_show_pages') == 'off') {
        $woo_chatbot_select_pages = unserialize(get_option('woo_chatbot_show_pages_list'));
        if (is_page() && !empty($woo_chatbot_select_pages)) {
            if (in_array(get_the_ID(), $woo_chatbot_select_pages) == true) {
                $woo_chatbot_load = true;
            } else {
                $woo_chatbot_load = false;
            }
        }
    }
    if (get_option('woo_chatbot_show_woocommerce') == 'off') {
        if (is_shop() || is_cart() || is_checkout() || 'product' == get_post_type()) {
            $woo_chatbot_load = false;
        }
    }
    //load woowbot shortcode template and prevent default woowbot from footer.
    if (is_page()) {
        $page_id = get_the_ID();
        $page = get_post($page_id);
        if (has_shortcode($page->post_content, 'woowbot')) {
            $woo_chatbot_load = false;
        }
    }
    //Opening Hours for WoowBot.
    if (get_option('enable_woo_chatbot_opening_hour') == 1) {
        if (woo_chatbot_check_opening_hours() == false) {
            $woo_chatbot_load = false;
        } else {
            $woo_chatbot_load = true;
        }
    }

    return $woo_chatbot_load;
}

//Notification message controlling
function woo_chatbot_notification_msg_handle()
{
    $show_notification_msg = true;
    if (woo_chatbot_is_mobile() && get_option('disable_woo_chatbot_notification_mobile') == 1) {
        $show_notification_msg = false;
    }
    if (get_option('disable_woo_chatbot_notification') == 1 && !woo_chatbot_is_mobile()) {
        $show_notification_msg = false;
    }
    return $show_notification_msg;
}

//checking Devices
function woo_chatbot_is_mobile(){
    if(isset( $_SERVER['HTTP_USER_AGENT'])){
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            return true;
        } else {
            return false;
        }
    }else{
        return false;
    }
}

//Checking Woowbot opening hour
function woo_chatbot_check_opening_hours()
{
    $curent_day = strtolower(date('l', strtotime(current_time('mysql'))));
    $current_time = date('H:i', strtotime(current_time('mysql')));
    $is_woowbot_open = false;
    if (get_option('woowbot_hours')) {
        $woowbot_times = unserialize(get_option('woowbot_hours'));
        if (isset($woowbot_times[$curent_day])) {
            $day_times = $woowbot_times[$curent_day];
            if (!empty($day_times)) {
                foreach ($day_times as $day_time) {
                    if (strtotime($current_time) > strtotime($day_time[0]) && strtotime($current_time) < strtotime($day_time[1])) {
                        $is_woowbot_open = true;
                    }
                }
            }
        }
    }
    return $is_woowbot_open;
}

//WoowBot shortcode.
add_shortcode('woowbot', 'woo_chatbot_short_code');
add_shortcode('WoowBot', 'woo_chatbot_short_code');
function woo_chatbot_short_code($atts = [])
{
    ob_start();
    woo_chatbot_shortcode_dom($atts);
    $content = ob_get_clean();
    return $content;
}

function woo_chatbot_shortcode_dom($atts)
{
    //Defaults & Set Parameters for shortcode
    extract(shortcode_atts(
        array(
            'template' => '01',
        ), $atts
    ));
    ?>
    <style>
        <?php if(get_option('woo_chatbot_custom_css')!=""){echo get_option('woo_chatbot_custom_css'); } ?>
    </style>
    <?php if (get_option('qcld_woo_chatbot_change_bg') == 1) {
    if (get_option('qcld_woo_chatbot_board_bg_path') != "") {
        $qcld_woo_chatbot_board_bg_path = get_option('qcld_woo_chatbot_board_bg_path');
    } else {
        $qcld_woo_chatbot_board_bg_path = QCLD_WOOCHATBOT_IMG_URL . 'background/background.png';
    }
    ?>
    <style>
        .woo-chatbot-container {
            background: url(<?php echo $qcld_woo_chatbot_board_bg_path ;?>) no-repeat top right !important;
        }
    </style>
<?php }
    $woo_chatbot_enable_rtl = "";
    if (get_option('enable_woo_chatbot_rtl')) {
        $woo_chatbot_enable_rtl .= "woo-chatbot-rtl";
    }
    ?>
    <div id="woo-chatbot-chat-container" class="<?php echo $woo_chatbot_enable_rtl; ?>">
        <?php
        //Get Woocommerce cart
        global $woocommerce;
        $cart_items_number = $woocommerce->cart->cart_contents_count;
        
		if($template=='mini'){
			$qcld_woo_chatbot_theme = $template.'-mode';
		}else{
			$qcld_woo_chatbot_theme = 'template-' . $template;
		}
        
		
		if (file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/style.css')) {
            wp_register_style('qcld-woo-chatbot-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/templates/' . $qcld_woo_chatbot_theme . '/style.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qcld-woo-chatbot-style');
        }
        if (file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/template.php')) {
            require_once(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/template.php');
        } else {
            echo "<h2>" . __('No WooWBot Theme Found!', 'woochatbot') . "</h2>";
        }
        ?>
        <?php if (get_option('disable_woo_chatbot') != 1): ?>
            <div id="woo-chatbot-notification-container" class="woo-chatbot-notification-container">
                <div class="woo-chatbot-notification-controller"><span class="woo-chatbot-notification-close">X</span>
                </div>
                <?php
                if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') == "custom-agent.png") {
                    $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_agent_path');
                } else if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') != "custom-agent.png") {
                    $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_agent_image');
                } else {
                    $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
                }
                ?>
                <div class="woo-chatbot-notification-agent-profile">
                    <div class="woo-chatbot-notification-widget-avatar"><img
                                src="<?php echo $woo_chatbot_custom_icon_path; ?>" alt=""></div>
                    <div class="woo-chatbot-notification-welcome"><?php echo randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_welcome'))) . ' <strong>' . get_option('qlcd_woo_chatbot_host') . '</strong>'; ?></div>
                </div>
                <div class="woo-chatbot-notification-message"><?php echo randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_notifications'))); ?></div>
            </div>
            <!--woo-chatbot-board-container-->
            <div id="woo-chatbot-ball" class="">
                <div class="woo-chatbot-ball">
                    <div class="woo-chatbot-ball-animator woo-chatbot-ball-animation-switch"></div>
                    <?php
                    if (get_option('woo_chatbot_custom_icon_path') != "" && get_option('woo_chatbot_icon') == "custom.png") {
                        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_icon_path');
                    } else if (get_option('woo_chatbot_custom_icon_path') != "" && get_option('woo_chatbot_icon') != "custom.png") {
                        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_icon');
                    } else {
                        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom.png';
                    }
                    ?>
                    <img src="<?php echo $woo_chatbot_custom_icon_path; ?>"
                         alt="WooChatIcon"> <span
                            class="woo-chatbot-ball-cart-items"><?php echo $cart_items_number; ?></span></div>
            </div>
            <!--                woo-chatbot-board-container-->
        <?php endif; ?>
        <!--container-->
        <!--woo-chatbot-ball-wrapper-->
    </div>
<?php } ?>
<?php
//Create shortcode for WoowBot for pages.
add_shortcode('woowbot-page', 'woo_chatbot_page_short_code');
add_shortcode('WoowBot-page', 'woo_chatbot_page_short_code');
function woo_chatbot_page_short_code()
{
    ob_start();
    woo_chatbot_page_dom();
    $content = ob_get_clean();
    return $content;
}

function woo_chatbot_page_dom()
{ ?>
    <style>
        <?php if(get_option('woo_chatbot_custom_css')!=""){echo get_option('woo_chatbot_custom_css'); } ?>
    </style>
    <?php
    //Get Woocommerce cart
    global $woocommerce;
    $cart_items_number = $woocommerce->cart->cart_contents_count;
    $qcld_woo_chatbot_theme = get_option('qcld_woo_chatbot_theme');
    $woo_chatbot_enable_rtl = "";
    if (get_option('enable_woo_chatbot_rtl') == 1) {
        $woo_chatbot_enable_rtl .= "woo-chatbot-rtl";
    }
    if (file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/shortcode.php')) {
        require_once(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/shortcode.php');
    } else {
        echo "<h2>" . __('No WoowBot ShortCode Theme Found!', 'woochatbot') . "</h2>";
    }
}

//shortcode for WooWBot mobile app
add_shortcode('woowbot_app', 'woo_chatbot_mobile_app_short_code');
function woo_chatbot_mobile_app_short_code()
{
    // keep traking app template.
    $template_app = 'yes';
    // add the action
    if (isset($_GET['from']) && $_GET['from'] == 'app') {
        if (!isset($_COOKIE['from_app'])) {
            setcookie('from_app', 'yes', time() + 3600);
        }
    }

    ?>
    <style>
        <?php if(get_option('woo_chatbot_custom_css')!=""){echo get_option('woo_chatbot_custom_css'); } ?>
    </style>
    <?php if (get_option('qcld_woo_chatbot_change_bg') == 1) {
    if (get_option('qcld_woo_chatbot_board_bg_path') != "") {
        $qcld_woo_chatbot_board_bg_path = get_option('qcld_woo_chatbot_board_bg_path');
    } else {
        $qcld_woo_chatbot_board_bg_path = QCLD_WOOCHATBOT_IMG_URL . 'background/background.png';
    }
    ?>
    <style>
        .woo-chatbot-container {
            background: url(<?php echo $qcld_woo_chatbot_board_bg_path ;?>) no-repeat top right !important;
        }
    </style>
<?php }
    $woo_chatbot_enable_rtl = "";
    if (get_option('enable_woo_chatbot_rtl')) {
        $woo_chatbot_enable_rtl .= "woo-chatbot-rtl";
    }
    ?>
    <div id="woo-chatbot-chat-app-shortcode-container" class="<?php echo $woo_chatbot_enable_rtl; ?>">
        <?php

        //Get Woocommerce cart
        global $woocommerce;
        $cart_items_number = $woocommerce->cart->cart_contents_count;
        //Handling shortcode enqeue and remove features part.
        define('WOOCOMMERCE', true);
        wp_enqueue_script('jquery');
        wp_enqueue_script('woocommerce', array('jquery'));
        wp_enqueue_script('wc-cart', array('jquery', 'woocommerce'));
        wp_enqueue_script('wc-address-i18n');
        wp_enqueue_script('wc-country-select');
        wp_enqueue_script('wc-checkout', array('jquery', 'woocommerce', 'wc-address-i18n', 'wc-country-select'));
        remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

        $qcld_woo_chatbot_theme = get_option('qcld_woo_chatbot_theme');
        if (file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/template.php')) {
            require_once(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/template.php');
        } else {
            echo "<h2>" . __('No WoowBot Theme Found!', 'woochatbot') . "</h2>";
        }
        ?>
    </div>
    <?php
}

/**
 * WoowBot Search keyword product
 */
add_action('wp_ajax_qcld_woo_chatbot_keyword', 'qcld_woo_chatbot_keyword');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_keyword', 'qcld_woo_chatbot_keyword');
function qcld_woo_chatbot_keyword()
{
    $keyword = sanitize_text_field($_POST['keyword']);
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    if (get_option('qlcd_woo_chatbot_search_option') == 'standard') {
        $product_orderby = get_option('qlcd_woo_chatbot_product_orderby') != '' ? get_option('qlcd_woo_chatbot_product_orderby') : 'title';
        $product_order = get_option('qlcd_woo_chatbot_product_order') != '' ? get_option('qlcd_woo_chatbot_product_order') : 'ASC';
        //Merging all query together.
        $argu_params = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $product_per_page,
            /*'orderby' => $product_orderby,
            'order' => $product_order,*/
            's' => $keyword,
        );
        /******
         *WP Query Operation to get products.*
         *******/
        $product_query = new WP_Query($argu_params);
        $product_num = $product_query->post_count;
        //Getting total product number by string.
        $total_argu = array('post_type' => 'product', 's' => $keyword, 'posts_per_page' => 100);
        $total_query = new WP_Query($total_argu);
        $total_product_num = $total_query->post_count;
        $html = '<div class="woo-chatbot-products-area">';
        $_pf = new WC_Product_Factory();
        //repeating the products
        if ($product_num > 0) {
            $html .= '<ul class="woo-chatbot-products">';
            while ($product_query->have_posts()) : $product_query->the_post();
                $product = $_pf->get_product(get_the_ID());
                if (woo_chatbot_product_controlling(get_the_ID()) == true && $product->is_visible()) {
                    $html .= '<li class="woo-chatbot-product">';
                    $html .= '<a target="_blank" href="' . get_permalink(get_the_ID()) . '"  woo-chatbot-pid= "' . get_the_ID() . '" title="' . esc_attr($product->get_title() ? $product->get_title(): get_the_ID()) . '">';
                    $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
                       <div class="woo-chatbot-product-summary">
                       <div class="woo-chatbot-product-table">
                       <div class="woo-chatbot-product-table-cell">
                       <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                       <div class="price">' . $product->get_price_html() . '</div>';
                    $html .= ' </div>
                       </div>
                       </div></a>
                       </li>';
                }
            endwhile;
            wp_reset_postdata();
            $html .= '</ul>';
            if ($total_product_num > $product_per_page && $product_per_page > 0) {
                $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="product" data-search-term="' . $keyword . '" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
            }
        }
        $html .= '</div>';
    } else if (get_option('qlcd_woo_chatbot_search_option') == 'advanced') {
        $result = WoowBot_Search::factory()->search($keyword);
        $products = $result['products'];
        $product_num = count($result['products']);
        $total_product_num = $result['total_products'];
        $more_product_ids = implode(",", $result['more_ids']);
        $html = '<div class="woo-chatbot-products-area">';
        $_pf = new WC_Product_Factory();
        //repeating the products
        if ($product_num > 0) {
            $html .= '<ul class="woo-chatbot-products">';
            foreach ($products as $product) {
                if (woo_chatbot_product_controlling($product->get_id()) == true && $product->is_visible()) {
                    $html .= '<li class="woo-chatbot-product">';
                    $html .= '<a target="_blank" href="' . get_permalink($product->get_id()) . '" woo-chatbot-pid= "' . $product->get_id() . '"  title="' . esc_attr($product->get_title()) . '">';
                    $html .= get_the_post_thumbnail($product->get_id(), 'shop_catalog') . '
                       <div class="woo-chatbot-product-summary">
                       <div class="woo-chatbot-product-table">
                       <div class="woo-chatbot-product-table-cell">
                       <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                       <div class="price">' . $product->get_price_html() . '</div>';
                    $html .= ' </div></div></div></a></li>';
                }
            }
            $html .= '</ul>';
            if ($total_product_num > $product_per_page && $product_per_page > 0) {
                $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="product" data-search-term="' . $more_product_ids . '" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
            }
        }
        $html .= '</div>';
    }
    $response = array('html' => $html, 'product_num' => $total_product_num, 'per_page' => $product_per_page);
    echo wp_send_json($response);
    wp_die();
}

/**
 * WoowBot Categories
 */
add_action('wp_ajax_qcld_woo_chatbot_category', 'qcld_woo_chatbot_category');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_category', 'qcld_woo_chatbot_category');
function qcld_woo_chatbot_category()
{
    $category_type = "common";
    if (get_option('woo_chatbot_show_parent_category') == 1) {
        //$terms = get_terms('product_cat', array('parent' => 0, 'hide_empty' => true, 'fields' => 'all'));
        $terms =  get_terms( array('taxonomy' => 'product_cat','parent' => 0,'hide_empty' => true));

    } else {
        $terms =  get_terms( array('taxonomy' => 'product_cat','hide_empty' => true));
    }
    $html = "";
    foreach ($terms as $term) {
        $child_terms = get_terms( array('taxonomy' => 'product_cat','parent' => $term->term_id,'hide_empty' => true));
        if ( get_option('woo_chatbot_show_parent_category')==1 && get_option('woo_chatbot_show_sub_category') == 1 && count($child_terms) > 0) {
            $category_type = "hasChilds";
        }
        $html .= '<span class="qcld-chatbot-product-category" data-category-type="' . $category_type . '"  data-category-slug="' . $term->slug . '" data-category-id="' . $term->term_id . '">' . $term->name . '</span>';
    }
    echo wp_send_json($html);
    wp_die();
}

/**
 * WoowBot Sub categories
 */
add_action('wp_ajax_qcld_woo_chatbot_sub_category', 'qcld_woo_chatbot_sub_category');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_sub_category', 'qcld_woo_chatbot_sub_category');
function qcld_woo_chatbot_sub_category()
{
    $category_type = "common";
    $parent_id = stripslashes($_POST['parent_id']);
    $terms = get_terms('product_cat', array('parent' => $parent_id, 'hide_empty' => true, 'fields' => 'all'));
    $html = "";
    foreach ($terms as $term) {
        $child_terms = get_terms( array('taxonomy' => 'product_cat','parent' => $term->term_id,'hide_empty' => true));
        if ( get_option('woo_chatbot_show_parent_category')==1 && get_option('woo_chatbot_show_sub_category') == 1 && count($child_terms) > 0) {
            $category_type = "hasChilds";
        }
        $html .= '<span class="qcld-chatbot-product-category" data-category-type="' . $category_type . '"  data-category-slug="' . $term->slug . '" data-category-id="' . $term->term_id . '">' . $term->name . '</span>';
    }
    echo wp_send_json($html);
    wp_die();
}

/**
 * WoowBot category product
 */
add_action('wp_ajax_qcld_woo_chatbot_category_products', 'qcld_woo_chatbot_category_products');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_category_products', 'qcld_woo_chatbot_category_products');
function qcld_woo_chatbot_category_products()
{
    $category_id = stripslashes($_POST['category']);
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $product_orderby = get_option('qlcd_woo_chatbot_product_orderby') != '' ? get_option('qlcd_woo_chatbot_product_orderby') : 'title';
    $product_order = get_option('qlcd_woo_chatbot_product_order') != '' ? get_option('qlcd_woo_chatbot_product_order') : 'ASC';
    //Merging all query together.
    $argu_params = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'orderby' => $product_orderby,
        'order' => $product_order,
        'posts_per_page' => $product_per_page,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN'
            )
        )
    );
    /******
     *WP Query Operation to get products.*
     *******/
    $product_query = new WP_Query($argu_params);
    $product_num = $product_query->post_count;
    //Getting total product number by string.
    $total_argu = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 100,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN'
            )
        )
    );
    $total_query = new WP_Query($total_argu);
    $total_product_num = $total_query->post_count;
    $_pf = new WC_Product_Factory();
    //repeating the products
    $html = '';
    if ($product_num > 0) {
        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<ul class="woo-chatbot-products">';
        while ($product_query->have_posts()) : $product_query->the_post();
            $product = $_pf->get_product(get_the_ID());
            if ($product->is_visible()) { // Display if visible
                $html .= '<li class="woo-chatbot-product">';
                $html .= '<a class="woo-chatbot-product-url" woo-chatbot-pid= "' . get_the_ID() . '" target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
                <div class="woo-chatbot-product-summary">
                <div class="woo-chatbot-product-table">
                <div class="woo-chatbot-product-table-cell">
                <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                <div class="price">' . $product->get_price_html() . '</div>';
                $html .= ' </div>
                </div>
                </div></a>
                </li>';
            }
        endwhile;
        wp_reset_postdata();
        $html .= '</ul>';
        if ($total_product_num > $product_per_page && $product_per_page > 0) {
            $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="category" data-search-term="' . $category_id . '" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
        }
        $html .= '</div>';
    } else {
        $html = '';
    }
    $response = array('html' => $html, 'product_num' => $total_product_num, 'per_page' => $product_per_page);
    echo wp_send_json($response);
    wp_die();
}

/**
 * WoowBot latest, featured, recent product
 */
add_action('wp_ajax_qcld_woo_chatbot_featured_products', 'qcld_woo_chatbot_featured_products');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_featured_products', 'qcld_woo_chatbot_featured_products');
function qcld_woo_chatbot_featured_products()
{
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $product_orderby = get_option('qlcd_woo_chatbot_product_orderby') != '' ? get_option('qlcd_woo_chatbot_product_orderby') : 'title';
    $product_order = get_option('qlcd_woo_chatbot_product_order') != '' ? get_option('qlcd_woo_chatbot_product_order') : 'ASC';
    //get featured products query.
    $argu_params = array(
        'posts_per_page' => $product_per_page,
        'post_type' => 'product',
        'post_status' => 'publish',
        'tax_query' => array(array('taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured'))
    );
    /******
     *WP Query Operation to get products.*
     *******/
    $product_query = new WP_Query($argu_params);
    $product_num = $product_query->post_count;
    //Getting total product number by string.
    $total_argu = array(
        'posts_per_page' => 100,
        'post_type' => 'product',
        'post_status' => 'publish',
        'tax_query' => array(array('taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured',),)
    );
    $total_query = new WP_Query($total_argu);
    $total_product_num = $total_query->post_count;
    $html = '<div class="woo-chatbot-products-area">';
    $_pf = new WC_Product_Factory();
    //repeating the products
    if ($product_num > 0) {
        //$html .= '<p>sdf sdfdsf : '.$asdfdf.'</p>';
        $html .= '<ul class="woo-chatbot-products">';
        while ($product_query->have_posts()) : $product_query->the_post();
            $product = $_pf->get_product(get_the_ID());
            if ($product->is_visible()) { // Display if visible
                $html .= '<li class="woo-chatbot-product">';
                $html .= '<a class="woo-chatbot-product-url" woo-chatbot-pid= "' . get_the_ID() . '" target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
                <div class="woo-chatbot-product-summary">
                <div class="woo-chatbot-product-table">
                <div class="woo-chatbot-product-table-cell">
                <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                <div class="price">' . $product->get_price_html() . '</div>';
                $html .= ' </div>
                </div>
                </div></a>
                </li>';
            }
        endwhile;
        wp_reset_postdata();
        $html .= '</ul>';
        if ($total_product_num > $product_per_page) {
            $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="product" data-search-term="featured" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
        }
    }
    $html .= '</div>';
    $response = array('html' => $html, 'product_num' => $total_product_num, 'per_page' => $product_per_page);
    echo wp_send_json($response);
    wp_die();
}

//Product display controll
function woo_chatbot_product_controlling($product_id)
{
    $display_product = true;
    //Controlling Out of Stock product display from back end.
    $_pf = new WC_Product_Factory();
    $product = $_pf->get_product($product_id);
    if ($product->get_manage_stock() == 'yes' && $product->get_stock_quantity() <= 0 && get_option('woo_chatbot_exclude_stock_out_product') == 1) {
        $display_product = false;
    }
    return $display_product;
}

//Get Sale products
add_action('wp_ajax_qcld_woo_chatbot_sale_products', 'qcld_woo_chatbot_sale_products');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_sale_products', 'qcld_woo_chatbot_sale_products');
function qcld_woo_chatbot_sale_products()
{
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $product_orderby = get_option('qlcd_woo_chatbot_product_orderby') != '' ? get_option('qlcd_woo_chatbot_product_orderby') : 'title';
    $product_order = get_option('qlcd_woo_chatbot_product_order') != '' ? get_option('qlcd_woo_chatbot_product_order') : 'ASC';
    //get sale products query.
    $argu_params = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page' => $product_per_page,
        'orderby' => $product_orderby,
        'order' => $product_order,
        //'offset' => $offset,
        'meta_query' => WC()->query->get_meta_query(),
        'post__in' => array_merge(array(0), wc_get_product_ids_on_sale())
    );
    /******
     *WP Query Operation to get products.*
     *******/
    $product_query = new WP_Query($argu_params);
    $product_num = $product_query->post_count;
    //Getting total product number by string.
    $total_argu = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 100,
        'meta_query' => WC()->query->get_meta_query(),
        'post__in' => array_merge(array(0), wc_get_product_ids_on_sale())
    );
    $total_query = new WP_Query($total_argu);
    $total_product_num = $total_query->post_count;
    $html = '<div class="woo-chatbot-products-area">';
    $_pf = new WC_Product_Factory();
    //repeating the products
    if ($product_num > 0) {
        //$html .= '<p>sdf sdfdsf : '.$asdfdf.'</p>';
        $html .= '<ul class="woo-chatbot-products">';
        while ($product_query->have_posts()) : $product_query->the_post();
            $product = $_pf->get_product(get_the_ID());
            if ($product->is_visible()) { // Display if visible
                $html .= '<li class="woo-chatbot-product">';
                $html .= '<a class="woo-chatbot-product-url" woo-chatbot-pid= "' . get_the_ID() . '" target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
                <div class="woo-chatbot-product-summary">
                <div class="woo-chatbot-product-table">
                <div class="woo-chatbot-product-table-cell">
                <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                <div class="price">' . $product->get_price_html() . '</div>';
                $html .= ' </div>
                </div>
                </div></a>
                </li>';
            }
        endwhile;
        wp_reset_postdata();
        $html .= '</ul>';
        if ($total_product_num > $product_per_page) {
            $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="product" data-search-term="sale" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
        }
    }
    $html .= '</div>';
    $response = array('html' => $html, 'product_num' => $total_product_num, 'per_page' => $product_per_page);
    echo wp_send_json($response);
    wp_die();
}

//load more
add_action('wp_ajax_qcld_woo_chatbot_load_more', 'qcld_woo_chatbot_load_more');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_load_more', 'qcld_woo_chatbot_load_more');
function qcld_woo_chatbot_load_more()
{
    $offset = stripslashes($_POST['offset']);
    $search_type = stripslashes($_POST['search_type']);
    $search_term = stripslashes($_POST['search_term']);
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $product_orderby = get_option('qlcd_woo_chatbot_product_orderby') != '' ? get_option('qlcd_woo_chatbot_product_orderby') : 'title';
    $product_order = get_option('qlcd_woo_chatbot_product_order') != '' ? get_option('qlcd_woo_chatbot_product_order') : 'ASC';
    $next_offset = intval($product_per_page + $offset);
    if ($search_type == 'product' && $search_term != 'featured' && $search_term != 'sale' && get_option('qlcd_woo_chatbot_search_option') == 'advanced') {
        //if have multiple ids then explode else have single need to array push
        if (strpos($search_term, ',') !== false) {
            $product_ids = explode(',', $search_term);
        } else {
            $product_ids = array($search_term);
        }
        $result = WoowBot_Search::factory()->get_load_more_products($product_ids);
        $products = $result['products'];
        $product_num = count($result['products']);
        $total_product_num = $result['total_products'];
        $more_product_ids = implode(",", $result['more_ids']);
        $_pf = new WC_Product_Factory();
        //repeating the products
        $html = '';
        if ($product_num > 0) {
            foreach ($products as $product) {
                if ($product->is_visible()) { // Display if visible
                    $html .= '<li class="woo-chatbot-product">';
                    $html .= '<a target="_blank" href="' . get_permalink($product->get_id()) . '" woo-chatbot-pid= "' . $product->get_id() . '"  title="' . esc_attr($product->get_title()) . '">';
                    $html .= get_the_post_thumbnail($product->get_id(), 'shop_catalog') . '
                   <div class="woo-chatbot-product-summary">
                   <div class="woo-chatbot-product-table">
                   <div class="woo-chatbot-product-table-cell">
                   <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                   <div class="price">' . $product->get_price_html() . '</div>';
                    $html .= ' </div></div></div></a></li>';
                }
            }
        }
        $response = array('html' => $html, 'product_num' => $total_product_num, 'search_term' => $more_product_ids, 'offset' => $next_offset, 'per_page' => $product_per_page);
    } else {
        if ($search_type == 'product' && $search_term != 'featured' && $search_term != 'sale') {  //For Standard search
            $argu_params = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => $product_per_page,
                'offset' => $offset,
                'orderby' => $product_orderby,
                'order' => $product_order,
                's' => $search_term,
            );
        } else if ($search_type == 'category') {
            $argu_params = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $product_per_page,
                'orderby' => $product_orderby,
                'order' => $product_order,
                'offset' => $offset,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $search_term,
                        'operator' => 'IN'
                    )
                )
            );
        } else if ($search_type == 'product' && $search_term == 'featured') {
            $argu_params = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $product_per_page,
                'orderby' => $product_orderby,
                'order' => $product_order,
                'offset' => $offset,
                'meta_query' => array('key' => '_featured', 'value' => 'yes')
            );
        } else if ($search_type == 'product' && $search_term == 'sale') {
            $argu_params = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $product_per_page,
                'orderby' => $product_orderby,
                'order' => $product_order,
                'offset' => $offset,
                'meta_query' => WC()->query->get_meta_query(),
                'post__in' => array_merge(array(0), wc_get_product_ids_on_sale())
            );
        }
        $product_query = new WP_Query($argu_params);
        $product_num = $product_query->post_count;
        $_pf = new WC_Product_Factory();
        //repeating the products
        $html = '';
        if ($product_num > 0) {
            while ($product_query->have_posts()) : $product_query->the_post();
                $product = $_pf->get_product(get_the_ID());
                if ($product->is_visible()) { // Display if visible
                    $html .= '<li class="woo-chatbot-product">';
                    $html .= '<a target="_blank" href="' . get_permalink(get_the_ID()) . '"  woo-chatbot-pid= "' . get_the_ID() . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                    $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
                   <div class="woo-chatbot-product-summary">
                   <div class="woo-chatbot-product-table">
                   <div class="woo-chatbot-product-table-cell">
                   <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                   <div class="price">' . $product->get_price_html() . '</div>';
                    $html .= ' </div>
                   </div>
                   </div></a>
                   </li>';
                }
            endwhile;
            wp_reset_postdata();
        } else {
            $html .= '';
        }
        $response = array('html' => $html, 'product_num' => $product_num, 'search_term' => $search_term, 'offset' => $next_offset, 'per_page' => $product_per_page);
    }
    echo wp_send_json($response);
    wp_die();
}

//product details
add_action('wp_ajax_qcld_woo_chatbot_product_details', 'qcld_woo_chatbot_product_details');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_product_details', 'qcld_woo_chatbot_product_details');
function qcld_woo_chatbot_product_details()
{
    $product_id = stripslashes($_POST['woo_chatbot_pid']);
    //Tracking product view from chat board
    woo_chatbot_view_track_product_by_id($product_id);
    //Woocommerce product factory
    $wc_pf = new WC_Product_Factory();
    $product = $wc_pf->get_product($product_id);
    $product_type = $wc_pf->get_product_type($product_id);
    $product_title = '<h2 id="woo-chatbot-product-title" ><a target="_blank" href="' . get_permalink($product->get_id()) . '">' . $product->get_title() . '</a></h2>';
    $product_desc = apply_filters('the_excerpt', $product->get_description());//$product->post->post_excerpt;
    $gallery_ids = $product->get_gallery_image_ids();
    //images processing..
    $product_feature_image_id = get_post_thumbnail_id($product_id);
    $product_feature_image = wp_get_attachment_image_src($product_feature_image_id, 'full');
    $product_feature_thumb = wp_get_attachment_image_src($product_feature_image_id, 'shop_thumbnail');
    //$full_img_src = $product_feature_image[0];
    //$product_image = '<img  style="width:500px;height:300px" src="' . $full_img_src . '" >';
    $product_image = '<div class="woo-chatbot-product-image-large">
                     <a href="' . $product_feature_image[0] . '" id="woo-chatbot-product-image-large-path"><img id="woo-chatbot-product-image-large-src" src="' . $product_feature_image[0] . '" alt="Large Image" title="Zoom Image" /></a>
                      </div>';
    $product_image .= '<div class="woo-chatbot-product-image-thumbs"><ul> 
                      <li class="woo-chatbot-product-active-image-thumbs"><a href="' . $product_feature_image[0] . '" class="woo-chatbot-product-image-thumbs-path"><img  class="woo-chatbot-product-image-thumbs-src" src="' . $product_feature_thumb[0] . '" alt="Thumb Image" /></a></li>';
    if (!empty($gallery_ids)) {
        foreach ($gallery_ids as $gallery_id) {
            $gallery_image = wp_get_attachment_image_src($gallery_id, 'full');
            $gallery_thumb = wp_get_attachment_image_src($gallery_id, 'shop_thumbnail');
            $product_image .= '<li><a href="' . $gallery_image[0] . '" class="woo-chatbot-product-image-thumbs-path"><img class="woo-chatbot-product-image-thumbs-src" src="' . $gallery_thumb[0] . '" alt="Thumb Image" /></a></li>';
        }
    }
    $product_image .= '</ul></div>';
    $product_price = '<p class="woo-chatbot-product-price" id="woo-chatbot-product-price">' . $product->get_price_html() . '</p>';
    $product_sku = '<p class="woo-chatbot-product-sku"> ' . __('SKU', 'woochatbot') . ' : ' . $product->get_sku() . '</p>';
    //if ( $product->is_in_stock() || $product->is_purchasable() )
    //Handle variable product start
    $variations = "";
    $add_cart_button = "";
    $product_quantity = "";
    if ($product->is_in_stock()) {
        if ($product_type == "variable") {
            //Getting product variation number based details
            $variations_data = array();
            foreach ($product->get_children() as $child_id) {
                $all_cfs = get_post_custom($child_id);
                array_push($variations_data, array('variation_id' => $child_id, 'variation_data' => $all_cfs));
            }
            $variations_data = json_encode($variations_data);
            $attributes = $product->get_attributes();
            //Handling Variant & Non Variant products
            $var_attrs = $product->get_variation_attributes();
            $varation_names = array();
            if (!empty($var_attrs)) {
                foreach ($var_attrs as $key => $value) {
                    array_push($varation_names, $key);
                }
            }
            $debug = $varation_names;
            foreach ($attributes as $attribute) {
                /*if (empty($attribute['is_visible']) || ($attribute['is_taxonomy'] && !taxonomy_exists($attribute['name']))) {
                    continue;
                }*/
                $title = wc_attribute_label($attribute['name']);
                $name = $attribute['name'];
                if ($attribute['is_taxonomy']) {
                    $values = wc_get_product_terms($product->get_id(), $attribute['name'], array('fields' => 'slugs'));
                } else {
                    $values = array_map('trim', explode(WC_DELIMITER, $attribute['value']));
                }
                natsort($values);
                if (!in_array($name, $varation_names)) {
                    $variations .= '<p><label for="' . sanitize_title($name) . '">' . $title . '</label> ' . ucfirst(implode(",", $values)) . '</p>';
                } else {
                    $variations .= '<div class="woo-chatbot-variable-' . sanitize_title($name) . '">';
                    $variations .= '<label for="' . sanitize_title($name) . '">' . $title . '</label>';
                    $variations .= '<select id="' . esc_attr(sanitize_title($name)) . '" name="attribute_' . sanitize_title($name) . '" data-attribute_name="attribute_' . sanitize_title($name) . '" class="each_attribute">';
                    $variations .= '<option value="">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_choose_option'))) . '</option>';
                    foreach ($values as $value) {
                        if (isset($_REQUEST['attribute_' . sanitize_title($name)])) {
                            $selected_value = $_REQUEST['attribute_' . sanitize_title($name)];
                        } else {
                            $selected_value = '';
                        }
                        $variations .= '<option value="' . esc_attr(strtolower($value)) . '"' . selected($selected_value, $value, false) . '>' . apply_filters('woocommerce_variation_option_name', $value) . '</option>';
                    }
                    $variations .= '</select></div>';
                }
            }
            $add_cart_button .= '<input type="button"  id="woo-chatbot-variation-add-to-cart" woo-chatbot-product-id="' . $product_id . '" value="' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_add_to_cart'))) . '" variation_id="" />';
            $add_cart_button .= "<input type='hidden'   id='woo-chatbot-variation-data'  data-product-variation='" . $variations_data . "' />";
        } else {
            $add_cart_button .= '<input type="button" id="woo-chatbot-add-cart-button" woo-chatbot-product-id="' . $product_id . '" value="' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_add_to_cart'))) . '" />';
        }
        //Handle variable product end.
        $product_quantity .= '<label for="">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_quantity'))) . '</label>
       <input type="number" id="vPQuantity" value="1" />';
    }
    //$response=$full_size_image;
    $response = array('title' => $product_title, 'description' => $product_desc, 'image' => $product_image, 'price' => $product_price, 'sku' => $product_sku, 'quantity' => $product_quantity, 'buttton' => $add_cart_button, 'variation' => $variations, 'type' => $product_type, 'debug' => $debug);
    echo wp_send_json($response);
    wp_die();
}

//Add to cart for variable product.
add_action('wp_ajax_variable_add_to_cart', 'qcld_woo_chatbot_variable_add_to_cart');
add_action('wp_ajax_nopriv_variable_add_to_cart', 'qcld_woo_chatbot_variable_add_to_cart');
function qcld_woo_chatbot_variable_add_to_cart()
{
    $product_id = stripslashes($_POST['p_id']);
    $quantity = stripslashes($_POST['quantity']);
    $variations_id = stripslashes($_POST['variations_id']);
    $attrs = stripslashes($_POST['attributes']);
    //echo wp_send_json(array('p_id'=>$product_id,'qnty'=>$quantity,'id'=>$variations_id,'att'=>$attrs));
    $attributes = array();
    foreach ($attrs as $attr) {
        $single = explode("#", $attr);
        if (isset($single[0])) {
            $a_name = explode("_", $single[0]);
        }
        $attributes[$a_name[2]] = $single[1];
    }
    global $woocommerce;
    $result = $woocommerce->cart->add_to_cart($product_id, $quantity, $variations_id, $attributes, null);
    if ($result != false) {
        echo wp_send_json('variable');
    } else {
        echo wp_send_json('error');
    }
    wp_die();
}

//Add to cart for simple product.
add_action('wp_ajax_qcld_woo_chatbot_add_to_cart', 'qcld_woo_chatbot_add_to_cart');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_add_to_cart', 'qcld_woo_chatbot_add_to_cart');
function qcld_woo_chatbot_add_to_cart()
{
    $product_id = stripslashes($_POST['product_id']);
    $product_quantity = stripslashes($_POST['quantity']);
    global $woocommerce;
    $result = $woocommerce->cart->add_to_cart($product_id, $product_quantity);
    if ($result != false) {
        echo wp_send_json('simple');
    } else {
        echo wp_send_json('error');
    }
    wp_die();
}

//Support Email
add_action('wp_ajax_qcld_woo_chatbot_support_email', 'qcld_woo_chatbot_support_email');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_support_email', 'qcld_woo_chatbot_support_email');
function qcld_woo_chatbot_support_email()
{
    $name = trim(sanitize_text_field($_POST['name']));
    $email = sanitize_email($_POST['email']);
    $message = sanitize_text_field($_POST['message']);
    $subject = get_option('qlcd_woo_chatbot_email_sub') != '' ? get_option('qlcd_woo_chatbot_email_sub') : 'Support Email from WooWBot by Client';
    //Extract Domain
    $url = get_site_url();
    $url = parse_url($url);
    $domain = $url['host'];
    //$admin_email = "admin@" . $domain;
    $admin_email = get_option('admin_email');
    $toEmail = get_option('qlcd_woo_chatbot_admin_email') != '' ? get_option('qlcd_woo_chatbot_admin_email') : $admin_email;
    $fromEmail = "wordpress@" . $domain;
    //Starting messaging and status.
    $response['status'] = 'fail';
    $response['message'] = str_replace('\\', '', get_option('qlcd_woo_chatbot_email_fail'));
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $response['message'] = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_invalid_email')));
        $response['status'] = 'fail';
    } else {
        //build email body
        $bodyContent = "";
        $bodyContent .= '<p><strong>' . __('Support Request Details', 'woochatbot') . ':</strong></p><hr>';
        $bodyContent .= '<p>' . __('Name', 'woochatbot') . ' : ' . $name . '</p>';
        $bodyContent .= '<p>' . __('Email', 'woochatbot') . ' : ' . $email . '</p>';
        $bodyContent .= '<p>' . __('Subject', 'woochatbot') . ' : ' . $subject . '</p>';
        $bodyContent .= '<p>' . __('Message', 'woochatbot') . ' : ' . $message . '</p>';
        $bodyContent .= '<p>' . __('Mail Generated on', 'woochatbot') . ': ' . date('F j, Y, g:i a') . '</p>';
        $to = $toEmail;
        $body = $bodyContent;
        $headers = array();
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $name . ' <' . $fromEmail . '>';
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
        $result = wp_mail($to, $subject, $body, $headers);
        //$response['message'] = 'to:'.$to.', $subject='.$subject.', $body='. $body.'$headers='.$headers;
	   
	    if ($result) {
            $response['status'] = 'success';
            $response['message'] = str_replace('\\', '', get_option('qlcd_woo_chatbot_email_sent'));
        }
    }
    ob_clean();
    echo json_encode($response);
    die();
}

//Support Phone
add_action('wp_ajax_qcld_woo_chatbot_support_phone', 'qcld_woo_chatbot_support_phone');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_support_phone', 'qcld_woo_chatbot_support_phone');
function qcld_woo_chatbot_support_phone()
{
    $name = trim(sanitize_text_field($_POST['name']));
    $phone = sanitize_text_field($_POST['phone']);
    $subject = 'WooWBot Support Mail Request for Call Back';
    //Extract Domain
    $url = get_site_url();
    $url = parse_url($url);
    $domain = $url['host'];
    //$admin_email = "admin@" . $domain;
    $admin_email = get_option('admin_email');
    $toEmail = get_option('qlcd_woo_chatbot_admin_email') != '' ? get_option('qlcd_woo_chatbot_admin_email') : $admin_email;
    $fromEmail = "wordpress@" . $domain;
    //Starting messaging and status.
    $response['status'] = 'fail';
    $response['message'] = str_replace('\\', '', get_option('qlcd_woo_chatbot_phone_fail'));
    //build email body
    $bodyContent = "";
    $bodyContent .= '<p><strong>' . __('Support Request Details', 'woochatbot') . ':</strong></p><hr>';
    $bodyContent .= '<p>' . __('Name', 'woochatbot') . ' : ' . $name . '</p>';
    $bodyContent .= '<p>' . __('Phone', 'woochatbot') . ' : ' . $phone . '</p>';
    $bodyContent .= '<p>' . __('Subject', 'woochatbot') . ' : ' . $subject . '</p>';
    $bodyContent .= '<p>' . __('Message', 'woochatbot') . ' : ' . __(' Call me at ', 'woochatbot') . $phone . '</p>';
    $bodyContent .= '<p>' . __('Mail Generated on', 'woochatbot') . ': ' . date('F j, Y, g:i a') . '</p>';
    $to = $toEmail;
    $body = $bodyContent;
    $headers = array();
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . $name . ' <' . $fromEmail . '>';
    $headers[] = 'Reply-To: ' . $name . ' <' . $fromEmail . '>';
    $result = wp_mail($to, $subject, $body, $headers);
    if ($result) {
        $response['status'] = 'success';
        $response['message'] = str_replace('\\', '', get_option('qlcd_woo_chatbot_phone_sent'));
    }
    ob_clean();
    echo json_encode($response);
    die();
}

// Order Status part.
add_action('wp_ajax_qcld_woo_chatbot_check_user', 'qcld_woo_chatbot_check_user');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_check_user', 'qcld_woo_chatbot_check_user');
function qcld_woo_chatbot_check_user()
{
    global $woocommerce;
    $user_name = trim(sanitize_text_field($_POST['user_name']));
    $response = array();
    $response['message'] = "";
    if (username_exists($user_name)) {
        if (get_option('qlcd_woo_chatbot_order_user') == 'login') {
            $response['status'] = "success";
            $response['message'] .= randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_order_username_thanks')));
            $response['html'] .= '<p>' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_order_username_password'))) . '</p>';
        } else if (get_option('qlcd_woo_chatbot_order_user') == 'not_login') {
            $response = get_order_by_username($user_name);
        }
    } else {
        $response['status'] = "fail";
        $response['message'] .= '<strong>' . $user_name . '</strong> ' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_order_username_not_exist')));
    }
    echo wp_send_json($response);
    die();
}

function randmom_message_handle($items)
{
    return $items[rand(0, count($items) - 1)];
}

function qcld_woo_chatbot_func_str_replace($messages = array())
{
    $refined_mesgses = array();
    foreach ($messages as $message) {
        $refined_msg = str_replace('\\', '', $message);
        array_push($refined_mesgses, $refined_msg);
    }
    return $refined_mesgses;
}

add_action('wp_ajax_qcld_woo_chatbot_login_user', 'qcld_woo_chatbot_login_user');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_login_user', 'qcld_woo_chatbot_login_user');
function qcld_woo_chatbot_login_user()
{
    // First check the nonce, if it fails the function will break
    check_ajax_referer('woowbot-order-nonce', 'security');
    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = trim(sanitize_text_field($_POST['user_name']));
    $info['user_password'] = trim(sanitize_text_field($_POST['user_pass']));
    $info['remember'] = true;
    $user_signon = wp_signon($info, false);
    $response = array();
    if (is_wp_error($user_signon)) {
        $response['status'] = "fail";
        $response['message'] = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_order_password_incorrect')));
    } else {
        $response = get_order_by_username($info['user_login']);
        $response['status'] = "success";
    }
    echo wp_send_json($response);
    die();
}

add_action('wp_ajax_qcld_woo_chatbot_loged_in_user_orders', 'qcld_woo_chatbot_loged_in_user_orders');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_loged_in_user_orders', 'qcld_woo_chatbot_loged_in_user_orders');
function qcld_woo_chatbot_loged_in_user_orders()
{
    $current_user = wp_get_current_user();
    $user_name = $current_user->user_login;
    $response = get_order_by_username($user_name);
    echo wp_send_json($response);
    die();
}

function get_order_by_username($user_name)
{
    global $post;
    $response = array();
    $response['status'] .= "success";
    $user = get_user_by('login', $user_name);
    // The query arguments
    $customer_orders = get_posts(array(
        'numberposts' => -1,
        'meta_key' => '_customer_user',
        'meta_value' => $user->ID,
        'post_type' => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()),
        'posts_per_page' => 10,
        'orderby' => 'date',
    ));
    $response['order_num'] = count($customer_orders);
    $order_html = '';
    if ($response['order_num'] > 0) {
        $response['message'] .= randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_order_found')));
        $order_html .= '<div class="woo-chatbot-orders-container">
            <div class="woo-chatbot-orders-header">
                <div class="order-id">' . __('ID', 'woochatbot') . '</div> 
                <div class="order-date">' . __('Date', 'woochatbot') . ' </div>
                <div class="order-items">' . __('Items', 'woochatbot') . '</div>
                <div class="order-status">' . __('Status', 'woochatbot') . '</div>
            </div>';
        foreach ($customer_orders as $order) {
            //Formatting order summery
            if (isset($_COOKIE['from_app']) && $_COOKIE['from_app'] == 'yes') {
                $thanks_page_id = get_option('woo_chatbot_app_order_thankyou');
                $thanks_parmanlink = esc_url(get_permalink($thanks_page_id));
                $order_url = '<a href="' . $thanks_parmanlink . '?order_id=' . $order->ID . '" >' . $order->ID . '</a>';
            } else {
                $order_url = '<a href="' . get_permalink(get_option('woocommerce_myaccount_page_id')) . get_option('woocommerce_myaccount_view_order_endpoint') . '/' . $order->ID . '" target="_blank" >' . $order->ID . '</a>';
            }
            $order_html .= '<div class="woo-chatbot-orders-single">
                <div class="order-id"> ' . $order_url . '</div>
                <div class="order-date"> <p>' . date("m/d/Y", strtotime($order->post_date)) . '</p> </div>
                <div class="order-items">';
            $singleOrder = new WC_Order($order->ID);
            $items = $singleOrder->get_items();
            foreach ($items as $item) {
                $order_html .= '<p>' . $item["name"] . ' X ' . $item["qty"] . '</p>';
            }
            $order_html .= '</div>
                <div class="order-status">' . strtoupper(end(explode("-", $order->post_status))) . '</div>
                
                 </div>';
        }
        $order_html .= '</div>';
    } else {
        $response['message'] .= get_option('qlcd_woo_chatbot_sorry') . '! <strong>' . $user_name . '</strong>';
        $order_html .= '<p>' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_order_not_found'))) . '</p>';
    }
    $response['html'] = $order_html;
    return $response;
}

//Step by Step Products Search by Dialogflow
add_action('wp_ajax_qcld_woo_chatbot_step_by_step_search', 'qcld_woo_chatbot_step_by_step_search');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_step_by_step_search', 'qcld_woo_chatbot_step_by_step_search');
function qcld_woo_chatbot_step_by_step_search()
{
    $parameters = $_POST['params'];
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $keys = array();
    $price = 0;
    foreach ($parameters as $key => $val) {
        if (strtolower($key) == 'price') {
            $price = $val;
        } else {
            $keys[] = $val;
        }

    }
    $result = step_by_step_search_get_products($keys, $price);
    $products = $result['products'];
    $product_num = count($result['products']);
    $total_product_num = $result['total_products'];
    $more_product_ids = implode(",", $result['more_ids']);
    $html = '<div class="woo-chatbot-products-area">';
    $_pf = new WC_Product_Factory();
    //repeating the products
    if ($product_num > 0) {
        $html .= '<ul class="woo-chatbot-products">';
        foreach ($products as $product) {
            if (woo_chatbot_product_controlling($product->get_id()) == true && $product->is_visible()) {
                $html .= '<li class="woo-chatbot-product">';
                $html .= '<a target="_blank" href="' . get_permalink($product->get_id()) . '" woo-chatbot-pid= "' . $product->get_id() . '"  title="' . esc_attr($product->get_title()) . '">';
                $html .= get_the_post_thumbnail($product->get_id(), 'shop_catalog') . '
                        <div class="woo-chatbot-product-summary">
                        <div class="woo-chatbot-product-table">
                        <div class="woo-chatbot-product-table-cell">
                        <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                        <div class="price">' . $product->get_price_html() . '</div>';
                $html .= ' </div></div></div></a></li>';
            }
        }
        $html .= '</ul>';
        if ($total_product_num > $product_per_page && $product_per_page > 0) {
            $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="product" data-search-term="' . $more_product_ids . '" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
        }
        $html .= '</div>';
    }
    $response = array('html' => $html, 'product_num' => $total_product_num, 'per_page' => $product_per_page);
    echo wp_send_json($response);
//echo wp_send_json(array('sql'=>$sql,'key'=>$keys,'price'=>$price));
    wp_die();
}

function step_by_step_search_get_products($keys, $price)
{
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $products_array = array();
    $more_product_ids = array();
    $posts_ids = step_by_step_search_get_posts_ids($keys, $price);
    $products_num = count($posts_ids);
    if ($products_num > 0) {
        //if result products ids are more than per_page then keep remaing ids as load more option.
        if ($products_num > $product_per_page) {
            $more_product_ids = array_slice($posts_ids, $product_per_page);
            $current_product_ids = array_slice($posts_ids, 0, $product_per_page);
        } else {
            $current_product_ids = $posts_ids;
        }
        foreach ($current_product_ids as $post_id) {
            $product = wc_get_product($post_id);
            $products_array[] = $product;
        }
    }
    return array('products' => $products_array, 'more_ids' => $more_product_ids, 'total_products' => $products_num);
}

function step_by_step_search_get_posts_ids($keys, $price)
{
    $posts_ids = array();
    $counter = 1;
    //$test_sql=array();
    foreach ($keys as $key) {
        $new_ids = qcld_get_step_by_step_post_ids($keys, $price, $counter);
        $posts_ids = array_merge($posts_ids, $new_ids);
        $counter++;
    }
    return $posts_ids;

}

function qcld_get_step_by_step_post_ids($keys, $price, $counter)
{
    global $wpdb;
    $posts_ids = array();
    $sql = step_by_step_search_sql_prepare($keys, $price, $counter);
    $search_results = $wpdb->get_results($sql);
    if (!empty($search_results) && !is_wp_error($search_results) && is_array($search_results)) {
        foreach ($search_results as $search_result) {
            $post_id = intval($search_result->ID);
            if (!in_array($post_id, $posts_ids)) {
               
			   //added by Raju, QC
			   $terms =  get_the_terms( $post_id, 'product_tag' );
			   $tags = array();
				if( count($terms) > 0 ){
					foreach($terms as $term){
						$term_slug = $term->slug; // Product tag slug
						$tags[] = $term_slug;
					}
				}
			   
			   $total_matched = count(array_intersect(array_map('strtolower',$keys), array_map('strtolower',$tags))); 
			   $total_keys = count($keys);
			   //var_dump($total_matched);
			   //var_dump($tags);
			  // var_dump($keys);
			   
			   $containsSearch = count(array_intersect($keys, $tags)) == count($keys);
			   //var_dump($containsSearch);
			  // if($containsSearch){
				if($total_matched == $total_keys){  
				   $posts_ids[] = $post_id;
			   }
			   
			   //added by Raju end
			    
            }
        }
    }
    unset($search_results);
    return $posts_ids;
}

function step_by_step_search_sql_prepare($search_tags, $price, $counter)
{

    global $wpdb;

    // Initialise some variables
    $fields = '';
    $where = '';
    $join = '';
    $groupby = '';
    $orderby = '';
    $posts_per_page = 5;
    $offset = 0;

    $post_types = array('product');

    $n = '%';

    if (count($search_tags) > 0) {

        $search_terms = $search_tags;

        // Fields to return
        $fields = 'ID';
        //Kbs taxonomies
        $kbx_taxs = array('product_tag');
        // Create the WHERE Clause
        $where = ' AND ( ';
        for ($i = 0; $i < count($search_terms); $i = $i + 1) {
            foreach ($kbx_taxs as $tax) {
                if ($i == 0) {
                    $where .= $wpdb->prepare("(tt.taxonomy = '%s' AND t.slug = '%s')", strtolower($tax), $search_terms[$i]);
                } else {
                    if ($i >= $counter) {
                        $where .= $wpdb->prepare("AND (tt.taxonomy = '%s' AND t.slug = '%s')", strtolower($tax), $search_terms[$i]);
                    } else {
                        $where .= $wpdb->prepare("OR (tt.taxonomy = '%s' AND t.slug = '%s')", strtolower($tax), $search_terms[$i]);
                    }

                }

            }
        }
        $where .= ' ) ';

        if ($price != 0) {
            $where .= $wpdb->prepare("OR (pm.meta_key = '%s' AND pm.meta_value >= '%f' AND pm.meta_value <= '%f' )", '_price', ($price * .75), ($price * 1.75));
        }

        $where .= " AND (post_status = 'publish' OR post_status = 'inherit')";

        // Array of post types
        $where .= " AND $wpdb->posts.post_type IN ('" . join("', '", $post_types) . "') ";

        // Create the ORDERBY Clause
        $orderby = ' post_date DESC ';

    }

    /**
     * Filter the JOIN clause of the query.
     *
     * @since    2.0.0
     *
     * @param string $join The JOIN clause of the query
     * @param string $search_info [0]    Search query
     */
    $join = step_by_step_search_join_table($join);

    /**
     * Filter the JOIN clause of the query.
     *
     * @since    2.0.0
     *
     * @param string $limits The JOIN clause of the query
     * @param string $search_info [0]    Search query
     */
    //$limits = apply_filters( 'bsearch_posts_limits', $limits, $search_info[0] );
    $limits = " LIMIT " . $posts_per_page . " OFFSET " . $offset;

    if (!empty($groupby)) {
        $groupby = 'GROUP BY ' . $groupby;
    }
    if (!empty($orderby)) {
        $orderby = 'ORDER BY ' . $orderby;
    }

    $sql = "SELECT DISTINCT $fields FROM $wpdb->posts $join WHERE 1=1 $where $groupby $orderby $limits";

    /**
     * Filter MySQL string used to fetch results.
     *
     * @param    string $sql MySQL string
     * @param    array $search_info Search query
     * @param    bool $boolean_mode Set BOOLEAN mode for FULLTEXT searching
     * @param    bool $bydate Sort by date?
     */
   
   $apply_filters = apply_filters('woowbot_step_serach_sql_prepare', $sql, $search_tags);
   //var_dump($apply_filters); 
    return  $apply_filters;
    /***
     * Table Joins for taxonomies
     */
    //add_filter('posts_join_request', array($this, 'kbx_articles_join_table'));
}

function step_by_step_search_join_table($join)
{
    global $wpdb;
    //join taxonomies table
    $join .= " LEFT JOIN $wpdb->term_relationships tr ON ($wpdb->posts.ID = tr.object_id) ";
    $join .= " LEFT JOIN $wpdb->postmeta pm ON ($wpdb->posts.ID = pm.post_id) ";
    $join .= " LEFT JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) ";
    $join .= " LEFT JOIN $wpdb->terms t ON (tt.term_id = t.term_id) ";

    return $join;
}

//keeping id in cookies as
function woo_chatbot_track_product_view()
{
    if (!is_singular('product')) {
        return;
    }
    global $post;
    woo_chatbot_view_track_product_by_id($post->ID);
}

function woo_chatbot_view_track_product_by_id($post_id)
{
    if (empty($_COOKIE['woo_chatbot_woocommerce_recently_viewed']))
        $viewed_products = array();
    else
        $viewed_products = (array)explode('|', $_COOKIE['woo_chatbot_woocommerce_recently_viewed']);
    if (!in_array($post_id, $viewed_products)) {
        $viewed_products[] = $post_id;
    }
    if (sizeof($viewed_products) > 15) {
        array_shift($viewed_products);
    }
    // Store for session only
    wc_setcookie('woo_chatbot_woocommerce_recently_viewed', implode('|', $viewed_products));
}

add_action('template_redirect', 'woo_chatbot_track_product_view', 20);
//recent view product ajax
add_action('wp_ajax_qcld_woo_chatbot_recently_viewed_products', 'qcld_woo_chatbot_recently_viewed_products');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_recently_viewed_products', 'qcld_woo_chatbot_recently_viewed_products');
function qcld_woo_chatbot_recently_viewed_products()
{
    // Get WooCommerce Global
    $_pf = new WC_Product_Factory();
    //show post per page.
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $woo_chatbot_product_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_latest_product_welcome')));
    // Get recently viewed product cookies data
    $viewed_products = !empty($_COOKIE['woo_chatbot_woocommerce_recently_viewed']) ? (array)explode('|', $_COOKIE['woo_chatbot_woocommerce_recently_viewed']) : array();
    $viewed_products = array_filter(array_map('absint', $viewed_products));
    //get featured products if has.
    $featured_products = new WP_Query(array('post_status' => 'publish', 'posts_per_page' => $product_per_page, 'post_type' => 'product', 'tax_query' => array(array('taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured'))));
    //Getting recently vieew products.
    if (!empty($viewed_products)) {
        $woo_chatbot_product_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_viewed_product_welcome')));
        $product_query = new WP_Query(array(
            'posts_per_page' => $product_per_page,
            'no_found_rows' => 1,
            'post_status' => 'publish',
            'post_type' => 'product',
            'post__in' => $viewed_products,
        ));
        //Getting featured products
    } else if ($featured_products->post_count > 0) {
        $woo_chatbot_product_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_featured_product_welcome')));
        $product_query = $featured_products;
    } else {
        $woo_chatbot_product_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_latest_product_welcome')));
        //Getting recent products
        $product_query = new WP_Query(array('post_status' => 'publish', 'posts_per_page' => $product_per_page, 'post_type' => 'product', 'orderby' => 'date', 'order' => 'DESC'));
    }
    //$html = '<div class="woo-chatbot-widget-bubble">'.randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_viewed_product_welcome'))).'</div>';
    if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') == "custom-agent.png") {
        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_agent_path');
    } else if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') != "custom-agent.png") {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_agent_image');
    } else {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
    }
    $html = '<div class="woo-chatbot-agent-profile">
            <div class="woo-chatbot-widget-avatar"><img src="' . $woo_chatbot_custom_icon_path . '" alt=""></div>
            <div class="woo-chatbot-widget-agent">' . get_option('qlcd_woo_chatbot_agent') . '</div>
            <div class="woo-chatbot-bubble">' . $woo_chatbot_product_title . '</div>
            </div>';
    if ($product_query->post_count > 0) {
        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<ul class="woo-chatbot-products">';
        while ($product_query->have_posts()) : $product_query->the_post();
            $product = $_pf->get_product(get_the_ID());
            if ($product->is_visible()) { // Display if visible
                $html .= '<li class="woo-chatbot-product">';
                $html .= '<a target="_blank" href="' . get_permalink(get_the_ID()) . '" woo-chatbot-pid= "' . get_the_ID() . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
               <div class="woo-chatbot-product-summary">
               <div class="woo-chatbot-product-table">
               <div class="woo-chatbot-product-table-cell">
               <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
               <div class="price">' . $product->get_price_html() . '</div>';
                $html .= ' </div>
               </div>
               </div></a>
               </li>';
            }
        endwhile;
        wp_reset_query();
        wp_reset_postdata();
        $html .= '</ul></div>';
    } else {
        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p style="text-align: center">You have no products !';
        $html .= '</div>';
    }
    echo wp_send_json($html);
    die();
}

//Recently viewed product shortcode
add_shortcode('woowbot_products', 'qcld_woo_chatbot_recently_viewed_shortcode');
function qcld_woo_chatbot_recently_viewed_shortcode()
{
    // Get WooCommerce Global
    $_pf = new WC_Product_Factory();
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $woo_chatbot_product_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_latest_product_welcome')));
    // Get recently viewed product cookies data
    $viewed_products = !empty($_COOKIE['woo_chatbot_woocommerce_recently_viewed']) ? (array)explode('|', $_COOKIE['woo_chatbot_woocommerce_recently_viewed']) : array();
    $viewed_products = array_filter(array_map('absint', $viewed_products));
    //get featured products if has.
    $featured_products = new WP_Query(array('post_status' => 'publish', 'posts_per_page' => $product_per_page, 'post_type' => 'product', 'tax_query' => array(array('taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'featured'))));
    //Getting recently vieew products.
    if (!empty($viewed_products)) {
        $woo_chatbot_product_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_viewed_product_welcome')));
        $product_query = new WP_Query(array(
            'posts_per_page' => $product_per_page,
            'no_found_rows' => 1,
            'post_status' => 'publish',
            'post_type' => 'product',
            'post__in' => $viewed_products,
        ));
        //implementing featured products
    } else if ($featured_products->post_count > 0) {
        $woo_chatbot_product_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_featured_product_welcome')));
        $product_query = $featured_products;
    } else {
        $woo_chatbot_product_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_latest_product_welcome')));
        //Getting recent products
        $product_query = new WP_Query(array('post_status' => 'publish', 'posts_per_page' => $product_per_page, 'post_type' => 'product', 'orderby' => 'date', 'order' => 'DESC'));
    }
    //$html = '<div class="woo-chatbot-widget-bubble">'.randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_viewed_product_welcome'))).'</div>';
    if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') == "custom-agent.png") {
        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_agent_path');
    } else if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') != "custom-agent.png") {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_agent_image');
    } else {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
    }
    $html = '<div class="woo-chatbot-agent-profile">
            <div class="woo-chatbot-widget-avatar"><img src="' . $woo_chatbot_custom_icon_path . '" alt=""></div>
            <div class="woo-chatbot-widget-agent">' . get_option('qlcd_woo_chatbot_agent') . '</div>
            <div class="woo-chatbot-bubble">' . $woo_chatbot_product_title . '</div>
            </div>';
    if ($product_query->post_count > 0) {
        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<ul class="woo-chatbot-products">';
        while ($product_query->have_posts()) : $product_query->the_post();
            $product = $_pf->get_product(get_the_ID());
            if ($product->is_visible()) { // Display if visible
                $html .= '<li class="woo-chatbot-product">';
                $html .= '<a target="_blank" href="' . get_permalink(get_the_ID()) . '" woo-chatbot-pid= "' . get_the_ID() . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
               <div class="woo-chatbot-product-summary">
               <div class="woo-chatbot-product-table">
               <div class="woo-chatbot-product-table-cell">
               <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
               <div class="price">' . $product->get_price_html() . '</div>';
                $html .= ' </div>
               </div>
               </div></a>
               </li>';
            }
        endwhile;
        wp_reset_query();
        wp_reset_postdata();
        $html .= '</ul></div>';
    } else {
        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<p style="text-align: center">' . __('You have no products', 'woochatbot') . ' !';
        $html .= '</div>';
    }
    return $html;
}

//Show cart for Woo chatbot
add_action('wp_ajax_qcld_woo_chatbot_show_cart', 'qcld_woo_chatbot_show_cart');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_show_cart', 'qcld_woo_chatbot_show_cart');
function qcld_woo_chatbot_show_cart()
{
    global $woocommerce;
    // $cart_url = $woocommerce->cart->get_cart_url();
    $cart_url = wc_get_cart_url();
    $checkout_url = wc_get_checkout_url();
    //$checkout_url = $woocommerce->cart->get_checkout_url();
    $items = $woocommerce->cart->get_cart();
    $itemCount = $woocommerce->cart->cart_contents_count;
    $cart_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_shopping_cart')));
    $no_cart_item_msg = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_no_cart_items')));
    //$html = '<div class="woo-chatbot-widget-bubble">'.randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_welcome'))).'</div>';
    if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') == "custom-agent.png") {
        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_agent_path');
    } else if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') != "custom-agent.png") {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_agent_image');
    } else {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
    }
    $html = '<div class="woo-chatbot-agent-profile">
            <div class="woo-chatbot-widget-avatar"><img src="' . $woo_chatbot_custom_icon_path . '" alt=""></div>
            <div class="woo-chatbot-widget-agent">' . get_option('qlcd_woo_chatbot_agent') . '</div>
            <div class="woo-chatbot-bubble">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_welcome'))) . '</div>
            </div>';
    if ($itemCount >= 1) {
        $html .= '<div class ="woo-chatbot-cart-container">';
        $html .= '<div class="woo-chatbot-cart-header"><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_title'))) . '</div><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_quantity'))) . '</div><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_price'))) . '</div><div class="qcld-woo-chatbot-cell"></div></div>';
        $html .= '<div class ="woo-chatbot-cart-body">';
        foreach ($items as $item => $values) {
            $cart_item = apply_filters('woocommerce_cart_item_product', $values['data'], $values, $item);
            //product image
            $getProductDetail = wc_get_product($values['product_id']);
            $price = get_post_meta($values['product_id'], '_price', true);
            $html .= '<div class="woo-chatbot-cart-single">
                        <div class="qcld-woo-chatbot-cell"> <h3 class="woo-chatbot-title">' . $cart_item->get_title() . '</h3></div>';
            $html .= '<div class="qcld-woo-chatbot-cell">';
            $html .= '<input class="qcld-woo-chatbot-cart-item-qnty" data-cart-item="' . $item . '" type="number" min="1" value="' . $values['quantity'] . '"></div>';
            $html .= '<div class="qcld-woo-chatbot-cell"><span class="woo-chatbot-cart-price">' . apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($cart_item), $values, $item) . '</span> </div>';
            $html .= '<div class="qcld-woo-chatbot-cell"><span data-cart-item="' . $item . '" class="woo-chatbot-remove-cart-item">X</span></div> </div>';
        }
        $html .= ' </div>';//End of cart body
        $html .= '<div class="woo-chatbot-cart-single">
                            <div class="qcld-woo-chatbot-cell"></div>
                            <div class="qcld-woo-chatbot-cell"><strong>'. randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_total'))) .'</strong></div>
                            <div class="qcld-woo-chatbot-cell"><strong>' . $woocommerce->cart->get_cart_total() . '</strong></div>
                        </div>';
        $html .= '<div class="woo-chatbot-cart-footer"><div class="qcld-woo-chatbot-cart-page"><a class="woo-chatbot-cart-link" href="' . $cart_url . '" target="_blank"  >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_link'))) . '</a></div><div class="qcld-woo-chatbot-checkout"><a class="woo-chatbot-checkout-link" href="' . $checkout_url . '"   >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_checkout_link'))) . '</a></div></div>';
        $html .= ' </div>';
    } else {
        $html .= '<div class="woo-chatbot-cart-container">';
        $html .= '<div><p style="text-align:center">' . $no_cart_item_msg . '</p></div>';
        $html .= '</div>';
    }
    $response = array('html' => $html, 'items' => $itemCount);
    echo wp_send_json($response);
    wp_die();
}

//cart onley for Woo chatbot
add_action('wp_ajax_qcld_woo_chatbot_only_cart', 'qcld_woo_chatbot_only_cart');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_only_cart', 'qcld_woo_chatbot_only_cart');
function qcld_woo_chatbot_only_cart()
{
    global $woocommerce;
    // $cart_url = $woocommerce->cart->get_cart_url();
    $cart_url = wc_get_cart_url();
    $checkout_url = wc_get_checkout_url();
    //$checkout_url = $woocommerce->cart->get_checkout_url();
    $items = $woocommerce->cart->get_cart();
    $itemCount = $woocommerce->cart->cart_contents_count;
    $no_cart_item_msg = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_no_cart_items')));

    $html = '';
    if ($itemCount >= 1) {
        $html .= '<div class ="woo-chatbot-cart-container">';
        $html .= '<div class="woo-chatbot-cart-header"><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_title'))) . '</div><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_quantity'))) . '</div><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_price'))) . '</div><div class="qcld-woo-chatbot-cell"></div></div>';
        $html .= '<div class ="woo-chatbot-cart-body">';
        foreach ($items as $item => $values) {
            $cart_item = apply_filters('woocommerce_cart_item_product', $values['data'], $values, $item);
            //product image
            $getProductDetail = wc_get_product($values['product_id']);
            $price = get_post_meta($values['product_id'], '_price', true);
            $html .= '<div class="woo-chatbot-cart-single">
                        <div class="qcld-woo-chatbot-cell"> <h3 class="woo-chatbot-title">' . $cart_item->get_title() . '</h3></div>';
            $html .= '<div class="qcld-woo-chatbot-cell">';
            $html .= '<input class="qcld-woo-chatbot-cart-item-qnty" data-cart-item="' . $item . '" type="number" min="1" value="' . $values['quantity'] . '"></div>';
            $html .= '<div class="qcld-woo-chatbot-cell"><span class="woo-chatbot-cart-price">' . apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($cart_item), $values, $item) . '</span> </div>';
            $html .= '<div class="qcld-woo-chatbot-cell"><span data-cart-item="' . $item . '" class="woo-chatbot-remove-cart-item">X</span></div> </div>';
        }
        $html .= ' </div>';//End of cart body
        $html .= '<div class="woo-chatbot-cart-single">
                            <div class="qcld-woo-chatbot-cell"></div>
                            <div class="qcld-woo-chatbot-cell"><strong>'. randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_total'))) .'</strong></div>
                            <div class="qcld-woo-chatbot-cell"><strong>' . $woocommerce->cart->get_cart_total() . '</strong></div>
                        </div>';
        $html .= '<div class="woo-chatbot-cart-footer"><div class="qcld-woo-chatbot-cart-page"><a class="woo-chatbot-cart-link" href="' . $cart_url . '" target="_blank"  >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_link'))) . '</a></div><div class="qcld-woo-chatbot-checkout"><a class="woo-chatbot-checkout-link" href="' . $checkout_url . '" target="_blank"  >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_checkout_link'))) . '</a></div></div>';
        $html .= ' </div>';
    } else {
        $html .= '<div class="woo-chatbot-cart-container">';
        $html .= '<div><p style="text-align:center">' . $no_cart_item_msg . '</p></div>';
        $html .= '</div>';
    }
    $response = array('html' => $html, 'items' => $itemCount);
    echo wp_send_json($response);
    wp_die();
}

//Cart show Shortcode
add_shortcode('woowbot_cart', 'qcld_woo_chatbot_cart_shortcode');
function qcld_woo_chatbot_cart_shortcode()
{
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    $itemCount = $woocommerce->cart->cart_contents_count;
    $cart_title = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_shopping_cart')));
    $no_cart_item_msg = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_no_cart_items')));
    // $html = '<div class="woo-chatbot-widget-bubble">'.randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_welcome'))).'</div>';
    if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') == "custom-agent.png") {
        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_agent_path');
    } else if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') != "custom-agent.png") {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_agent_image');
    } else {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
    }
    $html = '<div class="woo-chatbot-agent-profile">
            <div class="woo-chatbot-widget-avatar"><img src="' . $woo_chatbot_custom_icon_path . '" alt=""></div>
            <div class="woo-chatbot-widget-agent">' . get_option('qlcd_woo_chatbot_agent') . '</div>
            <div class="woo-chatbot-bubble">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_welcome'))) . '</div>
            </div>';
    if ($itemCount >= 1) {
        $html .= '<div class ="woo-chatbot-cart-container">';
        $html .= '<div class="woo-chatbot-cart-header"><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_title'))) . '</div><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_quantity'))) . '</div><div class="qcld-woo-chatbot-cell">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_price'))) . '</div> <div class="qcld-woo-chatbot-cell"></div> </div>';
        $html .= '<div class ="woo-chatbot-cart-body">';
        foreach ($items as $item => $values) {
            $cart_item = apply_filters('woocommerce_cart_item_product', $values['data'], $values, $item);
            //product image
            $getProductDetail = wc_get_product($values['product_id']);
            $price = get_post_meta($values['product_id'], '_price', true);
            $html .= '<div class="woo-chatbot-cart-single">
                        <div class="qcld-woo-chatbot-cell"> <h3 class="woo-chatbot-title">' . $cart_item->get_title() . '</h3></div>';
            $html .= '<div class="qcld-woo-chatbot-cell">';
            $html .= '<input class="qcld-woo-chatbot-cart-item-qnty" data-cart-item="' . $item . '" type="number" min="1" value="' . $values['quantity'] . '"></div>';
            $html .= '<div class="qcld-woo-chatbot-cell"><span class="woo-chatbot-cart-price">' . apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($cart_item), $values, $item) . '</span> </div>';
            $html .= '<div class="qcld-woo-chatbot-cell"><span data-cart-item="' . $item . '" class="woo-chatbot-remove-cart-item">X</span></div> </div>';
        }
        $html .= ' </div>';//End of cart body
        $html .= '<div class="woo-chatbot-cart-single">
                            <div class="qcld-woo-chatbot-cell"></div>
                            <div class="qcld-woo-chatbot-cell"><strong>'. randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_total'))) .'</strong></div>
                            <div class="qcld-woo-chatbot-cell"><strong>' . $woocommerce->cart->get_cart_total() . '</strong></div>
                        </div>';
        $html .= '<div class="woo-chatbot-cart-footer"><div class="qcld-woo-chatbot-cart-page"><a href="' . site_url() . '/cart" target="_blank" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_cart_link'))) . '</a></div><div class="qcld-woo-chatbot-checkout"><a href="' . site_url() . '/checkout" target="_blank">' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_checkout_link'))) . '</a></div></div>';
        $html .= ' </div>';
    } else {
        $html .= '<div class="woo-chatbot-cart-container">';
        $html .= '<div><p style="text-align:center">' . $no_cart_item_msg . '</p></div>';
        $html .= '</div>';
    }
    // $response=array('html'=>$html,'items'=>$itemCount);
    return $html;
}

//Updating the cart items.
add_action('wp_ajax_qcld_woo_chatbot_update_cart_item_number', 'qcld_woo_chatbot_update_cart_item_number');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_update_cart_item_number', 'qcld_woo_chatbot_update_cart_item_number');
function qcld_woo_chatbot_update_cart_item_number()
{
    //getting cart items n
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $qnty = sanitize_text_field($_POST['qnty']);
    global $woocommerce;
    $result = $woocommerce->cart->set_quantity($cart_item_key, $qnty);
    echo wp_send_json($result);
    wp_die();
}

//Show item after removing from cart page.
add_action('wp_ajax_qcld_woo_chatbot_cart_item_remove', 'qcld_woo_chatbot_cart_item_remove');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_cart_item_remove', 'qcld_woo_chatbot_cart_item_remove');
function qcld_woo_chatbot_cart_item_remove()
{
    //getting cart items n
    $cart_item_key = sanitize_text_field($_POST['cart_item']);
    global $woocommerce;
    $result = $woocommerce->cart->remove_cart_item($cart_item_key);
    echo wp_send_json($result);
    wp_die();
}

//Show cart page by shortcode.
add_action('wp_ajax_qcld_woo_chatbot_cart_page', 'qcld_woo_chatbot_cart_page');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_cart_page', 'qcld_woo_chatbot_cart_page');
function qcld_woo_chatbot_cart_page()
{
    global $woocommerce;
    $itemCount = $woocommerce->cart->cart_contents_count;
    $html = "";
    if ($itemCount < 0) {
        $html .= randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_no_cart_items')));
    } else {
        $html .= do_shortcode("[woocommerce_cart]");
    }
    echo wp_send_json($html);
    wp_die();
}

//Show checkout page by shortcode.
add_action('wp_ajax_qcld_woo_chatbot_checkout_page', 'qcld_woo_chatbot_checkout_page');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_checkout_page', 'qcld_woo_chatbot_checkout_page');
function qcld_woo_chatbot_checkout_page()
{
    global $woocommerce;
    $itemCount = $woocommerce->cart->cart_contents_count;
    $html = "";
    if ($itemCount < 0) {
        $status = 'no';
        $html .= randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_no_cart_items')));
    } else {
        $status = 'yes';
        $checkout_page_id = get_option('woo_chatbot_app_checkout');
        $checkout_parmanlink = esc_url(get_permalink($checkout_page_id));
        $html .= $checkout_parmanlink;
    }
    $response = array('status' => $status, 'html' => $html);
    echo wp_send_json($response);
    wp_die();
}

//User login on Checkout page.
add_action('wp_ajax_qcld_woo_chatbot_checkout_user_login', 'qcld_woo_chatbot_checkout_user_login');
add_action('wp_ajax_nopriv_qcld_woo_chatbot_checkout_user_login', 'qcld_woo_chatbot_checkout_user_login');
function qcld_woo_chatbot_checkout_user_login()
{
    // Nonce is checked, get the POST data and sign user on
    $info = array();
    //$info['nonce'] = $_POST['nonce_val'];
    $info['user_login'] = trim(sanitize_text_field($_POST['user_name']));
    $info['user_password'] = trim(sanitize_text_field($_POST['user_pass']));
    $info['remember'] = true;
    $user_signon = wp_signon($info, false);
    // $response=$info;
    $response = array();
    if (is_wp_error($user_signon)) {
        // $response['status'] = "fail";
        // $response['message'] = randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_order_password_incorrect')));
        $response = "no";
    } else {
        $response = "yes";
    }
    echo wp_send_json($response);
    die();
}

// Load template for App Order Thank You page url
function woo_chatbot_load_app_template($template)
{
    if (is_page('woowbot-mobile-app')) {
        return dirname(__FILE__) . '/templates/app-templates/app.php';
    }
    return $template;
}

add_filter('template_include', 'woo_chatbot_load_app_template', 99);
// Load template for App Order Thank You page url
function woo_chatbot_load_app_order_thankyou_template($template)
{
    if (is_page('woowbot-app-order-thankyou')) {
        return dirname(__FILE__) . '/templates/app-templates/app-order-thankyou.php';
    }
    return $template;
}

add_filter('template_include', 'woo_chatbot_load_app_order_thankyou_template', 99);
// Load template for App checkout
function woo_chatbot_load_app_checkout_template($template)
{
    if (is_page('woowbot-app-checkout')) {
        return dirname(__FILE__) . '/templates/app-templates/app-checkout.php';
    }
    return $template;
}

add_filter('template_include', 'woo_chatbot_load_app_checkout_template', 99);
// Create embed page when plugin install or activate
//register_activation_hook(__FILE__, 'woo_chatbot_create_app_order_thankyou_page');
add_action('init', 'woo_chatbot_create_app_checkout_thankyou_page');
function woo_chatbot_create_app_checkout_thankyou_page()
{
    if (get_option('woo_chatbot_app_pages') == 1) {
        //Mobile App page create
        if (get_page_by_title('WoowBot Mobile App') == NULL) {
            //post status and options
            $app_page = array(
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_author' => get_current_user_id(),
                'post_date' => date('Y-m-d H:i:s'),
                'post_status' => 'publish',
                'post_title' => 'WoowBot Mobile App',
                'post_name' => 'woowbot-mobile-app',
                'post_type' => 'page',
            );
            //insert page and save the id
            $woowbot_app = wp_insert_post($app_page, false);
            //save the id in the database
            update_option('woo_chatbot_app_checkout', $woowbot_app);
        }
        //App checkout page create
        if (get_page_by_title('WoowBot App Checkout') == NULL) {
            //post status and options
            $checkout_page = array(
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_author' => get_current_user_id(),
                'post_date' => date('Y-m-d H:i:s'),
                'post_status' => 'publish',
                'post_title' => 'WoowBot App Checkout',
                'post_name' => 'woowbot-app-checkout',
                'post_type' => 'page',
            );
            //insert page and save the id
            $app_checkout = wp_insert_post($checkout_page, false);
            //save the id in the database
            update_option('woo_chatbot_app_checkout', $app_checkout);
        }
        //App Order thank you page create
        if (get_page_by_title('WoowBot App Order Thank You') == NULL) {
            //post status and options
            $thankyou_page = array(
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_author' => get_current_user_id(),
                'post_date' => date('Y-m-d H:i:s'),
                'post_status' => 'publish',
                'post_title' => 'WoowBot App Order Thank You',
                'post_name' => 'woowbot-app-order-thankyou',
                'post_type' => 'page',
            );
            //insert page and save the id
            $app_order_thankyou = wp_insert_post($thankyou_page, false);
            //save the id in the database
            update_option('woo_chatbot_app_order_thankyou', $app_order_thankyou);
        }
    }
    //Keep tracking from App by cookies
    if (isset($_GET['from']) && $_GET['from'] == 'app') {
        if (!isset($_COOKIE['from_app'])) {
            setcookie('from_app', 'yes', (time() + 3600), '/');
        }
    }
}

/***
 * Override order Thank page for mobile app
 */
add_action('woocommerce_thankyou', 'qcld_woo_chatbot__redirect_after_purchase', 10, 1);
function qcld_woo_chatbot__redirect_after_purchase($order_get_id)
{
    if (isset($_COOKIE['from_app']) && $_COOKIE['from_app'] == 'yes') {
        global $wp;
        if (is_checkout() && !empty($wp->query_vars['order-received'])) {
            $thanks_page_id = get_option('woo_chatbot_app_order_thankyou');
            $thanks_parmanlink = esc_url(get_permalink($thanks_page_id));
            wp_redirect($thanks_parmanlink . '?order_id=' . $order_get_id);
            exit;
        }
    } else {
        remove_action('woocommerce_thankyou', 'qcld_woo_chatbot__redirect_after_purchase');
        do_action('woocommerce_thankyou', $order_get_id);
    }
}

/*****
 * API Part functionalities
 */
function qcld_woochatbot_product_search_api($keyword)
{
    global $woocommerce;
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    if (get_option('qlcd_woo_chatbot_search_option') == 'standard') {
        $product_orderby = get_option('qlcd_woo_chatbot_product_orderby') != '' ? get_option('qlcd_woo_chatbot_product_orderby') : 'title';
        $product_order = get_option('qlcd_woo_chatbot_product_order') != '' ? get_option('qlcd_woo_chatbot_product_order') : 'ASC';
        //Merging all query together.
        $argu_params = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $product_per_page,
            'orderby' => $product_orderby,
            'order' => $product_order,
            's' => $keyword,
        );
        /******
         *WP Query Operation to get products.*
         *******/
        $product_query = new WP_Query($argu_params);
        $product_num = $product_query->post_count;
        //Getting total product number by string.
        $total_argu = array('post_type' => 'product', 's' => $keyword, 'posts_per_page' => 100);
        $total_query = new WP_Query($total_argu);
        $total_product_num = $total_query->post_count;
        $html = '<div class="woo-chatbot-products-area">';
        $_pf = new WC_Product_Factory();
        //repeating the products
        if ($product_num > 0) {
            $html .= '<ul class="woo-chatbot-products">';
            while ($product_query->have_posts()) : $product_query->the_post();
                $product = $_pf->get_product(get_the_ID());
                if ($product->is_visible()) { // Display if visible
                    $html .= '<li class="woo-chatbot-product">';
                    $html .= '<a class="woo-chatbot-product-url" woo-chatbot-pid= "' . get_the_ID() . '" target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                    $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
                    <div class="woo-chatbot-product-summary">
                    <div class="woo-chatbot-product-table">
                    <div class="woo-chatbot-product-table-cell">
                    <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                    <div class="price">' . $product->get_price_html() . '</div>';
                    $html .= ' </div>
                    </div>
                    </div></a>
                    </li>';
                }
            endwhile;
            wp_reset_postdata();
            $html .= '</ul>';
            if ($total_product_num > $product_per_page && $product_per_page > 0) {
                $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="product" data-search-term="' . $keyword . '" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
            }
        }
        $html .= '</div>';
    } else if (get_option('qlcd_woo_chatbot_search_option') == 'advanced') {
        $result = WoowBot_Search::factory()->search($keyword);
        $products = $result['products'];
        $product_num = count($result['products']);
        $total_product_num = $result['total_products'];
        $more_product_ids = implode(",", $result['more_ids']);
        $html = '<div class="woo-chatbot-products-area">';
        //$_pf = new WC_Product_Factory();
        //repeating the products
        if ($product_num > 0) {
            $html .= '<ul class="woo-chatbot-products">';
            foreach ($products as $product) {
                if (woo_chatbot_product_controlling($product->get_id()) == true && $product->is_visible()) {
                    $html .= '<li class="woo-chatbot-product">';
                    $html .= '<a target="_blank" href="' . get_permalink($product->get_id()) . '" woo-chatbot-pid= "' . $product->get_id() . '"  title="' . esc_attr($product->get_title()) . '">';
                    $html .= get_the_post_thumbnail($product->get_id(), 'shop_catalog') . '
                       <div class="woo-chatbot-product-summary">
                       <div class="woo-chatbot-product-table">
                       <div class="woo-chatbot-product-table-cell">
                       <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                       <div class="price">' . $product->get_price_html() . '</div>';
                    $html .= ' </div></div></div></a></li>';
                }
            }
            $html .= '</ul>';
            if ($total_product_num > $product_per_page && $product_per_page > 0) {
                $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="product" data-search-term="' . $more_product_ids . '" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
            }
        }
        $html .= '</div>';
    }
    $response = array('html' => $html, 'product_num' => $total_product_num, 'per_page' => $product_per_page);
    return $response;
}

function qcld_woochatbot_category_products_api($category_id)
{
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $product_orderby = get_option('qlcd_woo_chatbot_product_orderby') != '' ? get_option('qlcd_woo_chatbot_product_orderby') : 'title';
    $product_order = get_option('qlcd_woo_chatbot_product_order') != '' ? get_option('qlcd_woo_chatbot_product_order') : 'ASC';
    //Merging all query together.
    $argu_params = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'orderby' => $product_orderby,
        'order' => $product_order,
        'posts_per_page' => $product_per_page,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN'
            )
        )
    );
    /******
     *WP Query Operation to get products.*
     *******/
    $product_query = new WP_Query($argu_params);
    $product_num = $product_query->post_count;
    //Getting total product number by string.
    $total_argu = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 100,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'IN'
            )
        )
    );
    $total_query = new WP_Query($total_argu);
    $total_product_num = $total_query->post_count;
    $_pf = new WC_Product_Factory();
    //repeating the products
    $html = '';
    if ($product_num > 0) {
        $html .= '<div class="woo-chatbot-products-area">';
        $html .= '<ul class="woo-chatbot-products">';
        while ($product_query->have_posts()) : $product_query->the_post();
            $product = $_pf->get_product(get_the_ID());
            if ($product->is_visible()) { // Display if visible
                $html .= '<li class="woo-chatbot-product">';
                $html .= '<a class="woo-chatbot-product-url" woo-chatbot-pid= "' . get_the_ID() . '" target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
                <div class="woo-chatbot-product-summary">
                <div class="woo-chatbot-product-table">
                <div class="woo-chatbot-product-table-cell">
                <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                <div class="price">' . $product->get_price_html() . '</div>';
                $html .= ' </div>
                </div>
                </div></a>
                </li>';
            }
        endwhile;
        wp_reset_postdata();
        $html .= '</ul>';
        if ($total_product_num > $product_per_page && $product_per_page > 0) {
            $html .= '<p style="text-align: center"><button type="button" id="woo-chatbot-loadmore" data-offset="' . $product_per_page . '" data-search-type="category" data-search-term="' . $category_id . '" >' . randmom_message_handle(unserialize(get_option('qlcd_woo_chatbot_load_more'))) . ' <span id="woo-chatbot-loadmore-loader"></span></button> </p>';
        }
        $html .= '</div>';
    } else {
        $html = '';
    }
    $response = array('html' => $html, 'product_num' => $total_product_num, 'per_page' => $product_per_page);
    return $response;
}

function qcld_woochatbot_produt_category_api()
{
    $category_type = "common";
    if (get_option('woo_chatbot_show_parent_category') != "") {
        $terms = get_terms('product_cat', array('parent' => 0, 'hide_empty' => true, 'fields' => 'all'));

    } else {
        $terms = get_terms('product_cat', array('hide_empty' => true, 'fields' => 'all'));
    }
    $html = "";
    foreach ($terms as $term) {
        $child_terms = get_terms('product_cat', array('parent' => $term->term_id, 'hide_empty' => true, 'fields' => 'all'));
        if (get_option('woo_chatbot_show_sub_category') == 1 && count($child_terms) > 0) {
            $category_type = "hasChilds";
        }
        $html .= '<span class="qcld-chatbot-product-category" data-category-type="' . $category_type . '"  data-category-slug="' . $term->slug . '" data-category-id="' . $term->term_id . '">' . $term->name . '</span>';
    }
    $response = array('html' => $html, 'category_number' => count($terms));
    return $response;
}

function qcld_woochatbot_load_more_api($offset, $search_type, $search_term)
{
    $product_per_page = get_option('qlcd_woo_chatbot_ppp') != '' ? get_option('qlcd_woo_chatbot_ppp') : 10;
    $product_orderby = get_option('qlcd_woo_chatbot_product_orderby') != '' ? get_option('qlcd_woo_chatbot_product_orderby') : 'title';
    $product_order = get_option('qlcd_woo_chatbot_product_order') != '' ? get_option('qlcd_woo_chatbot_product_order') : 'ASC';
    $next_offset = intval($product_per_page + $offset);
    $response = array();
    if ($search_type == 'product' && $search_term != 'featured' && $search_term != 'sale' && get_option('qlcd_woo_chatbot_search_option') == 'advanced') {
        //if have multiple ids then explode else have single need to array push
        if (strpos($search_term, ',') !== false) {
            $product_ids = explode(',', $search_term);
        } else {
            $product_ids = array($search_term);
        }
        $result = WoowBot_Search::factory()->get_load_more_products($product_ids);
        $products = $result['products'];
        $product_num = count($result['products']);
        $total_product_num = $result['total_products'];
        $more_product_ids = implode(",", $result['more_ids']);
        $_pf = new WC_Product_Factory();
        //repeating the products
        $html = '';
        if ($product_num > 0) {
            foreach ($products as $product) {
                if ($product->is_visible()) { // Display if visible
                    $html .= '<li class="woo-chatbot-product">';
                    $html .= '<a target="_blank" href="' . get_permalink($product->get_id()) . '" woo-chatbot-pid= "' . $product->get_id() . '"  title="' . esc_attr($product->get_title()) . '">';
                    $html .= get_the_post_thumbnail($product->get_id(), 'shop_catalog') . '
                   <div class="woo-chatbot-product-summary">
                   <div class="woo-chatbot-product-table">
                   <div class="woo-chatbot-product-table-cell">
                   <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                   <div class="price">' . $product->get_price_html() . '</div>';
                    $html .= ' </div></div></div></a></li>';
                }
            }
        }
        $response = array('html' => $html, 'product_num' => $total_product_num, 'search_term' => $more_product_ids, 'offset' => $next_offset, 'per_page' => $product_per_page);
    } else {
        if ($search_type == 'product' && $search_term != 'featured' && $search_term != 'sale') {  //For Standard search
            $argu_params = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => $product_per_page,
                'offset' => $offset,
                'orderby' => $product_orderby,
                'order' => $product_order,
                's' => $search_term,
            );
        } else if ($search_type == 'category') {
            $argu_params = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $product_per_page,
                'orderby' => $product_orderby,
                'order' => $product_order,
                'offset' => $offset,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $search_term,
                        'operator' => 'IN'
                    )
                )
            );
        } else if ($search_type == 'product' && $search_term == 'featured') {
            $argu_params = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $product_per_page,
                'orderby' => $product_orderby,
                'order' => $product_order,
                'offset' => $offset,
                'meta_query' => array('key' => '_featured', 'value' => 'yes')
            );
        } else if ($search_type == 'product' && $search_term == 'sale') {
            $argu_params = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $product_per_page,
                'orderby' => $product_orderby,
                'order' => $product_order,
                'offset' => $offset,
                'meta_query' => array(
                    'relation' => 'OR',
                    array( // Simple products type
                        'key' => '_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'numeric'
                    ),
                    array( // Variable products type
                        'key' => '_min_variation_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'numeric'
                    )
                )
            );
        }
        $product_query = new WP_Query($argu_params);
        $product_num = $product_query->post_count;
        $_pf = new WC_Product_Factory();
        //repeating the products
        $html = '';
        if ($product_num > 0) {
            while ($product_query->have_posts()) : $product_query->the_post();
                $product = $_pf->get_product(get_the_ID());
                if ($product->is_visible()) { // Display if visible
                    $html .= '<li class="woo-chatbot-product">';
                    $html .= '<a class="woo-chatbot-product-url" woo-chatbot-pid= "' . get_the_ID() . '" target="_blank" href="' . get_permalink(get_the_ID()) . '" title="' . esc_attr($product->get_title() ? $product->get_title() : get_the_ID()) . '">';
                    $html .= get_the_post_thumbnail(get_the_ID(), 'shop_catalog') . '
                    <div class="woo-chatbot-product-summary">
                    <div class="woo-chatbot-product-table">
                    <div class="woo-chatbot-product-table-cell">
                    <h3 class="woo-chatbot-product-title">' . $product->get_title() . '</h3>
                    <div class="price">' . $product->get_price_html() . '</div>';
                    $html .= ' </div>
                    </div>
                    </div></a>
                    </li>';
                }
            endwhile;
            wp_reset_postdata();
        } else {
            $html .= '';
        }
        $response = array('html' => $html, 'product_num' => $product_num, 'search_term' => $search_term, 'offset' => $next_offset, 'per_page' => $product_per_page);
    }
    return $response;
}

function qcld_woochatbot_config_api()
{
    $woo_chatbot_obj = array(
        'woo_chatbot_position_x' => get_option('woo_chatbot_position_x'),
        'woo_chatbot_position_y' => get_option('woo_chatbot_position_y'),
        'woo_chatbot_position_x_mobile' => get_option('woo_chatbot_position_x_mobile'),
        'woo_chatbot_position_y_mobile' => get_option('woo_chatbot_position_y_mobile'),
        'disable_icon_animation' => get_option('disable_woo_chatbot_icon_animation'),
        'disable_featured_product' => get_option('disable_woo_chatbot_featured_product'),
        'disable_product_search' => get_option('disable_woo_chatbot_product_search'),
        'disable_catalog' => get_option('disable_woo_chatbot_catalog'),
        'disable_order_status' => get_option('disable_woo_chatbot_order_status'),
        'disable_sale_product' => get_option('disable_woo_chatbot_sale_product'),
        'open_product_detail' => get_option('woo_chatbot_open_product_detail'),
        'order_user' => get_option('qlcd_woo_chatbot_order_user'),
        'ajax_url' => admin_url('admin-ajax.php'),
        'image_path' => QCLD_WOOCHATBOT_IMG_URL,
        'yes' => str_replace('\\', '', get_option('qlcd_woo_chatbot_yes')),
        'no' => str_replace('\\', '', get_option('qlcd_woo_chatbot_no')),
        'or' => str_replace('\\', '', get_option('qlcd_woo_chatbot_or')),
        'host' => str_replace('\\', '', get_option('qlcd_woo_chatbot_host')),
        'agent' => str_replace('\\', '', get_option('qlcd_woo_chatbot_agent')),
        'agent_image' => get_option('woo_chatbot_agent_image'),
        'agent_image_path' => qcld_woo_chatbot_agent_icon_api(),
        'shopper_demo_name' => str_replace('\\', '', get_option('qlcd_woo_chatbot_shopper_demo_name')),
        'agent_join' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_agent_join'))),
        'welcome' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_welcome'))),
        'welcome_back' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_welcome_back'))),
        'hi_there' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_hi_there'))),
        'asking_name' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_asking_name'))),
        'i_am' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_i_am'))),
        'name_greeting' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_name_greeting'))),
        'wildcard_msg' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_wildcard_msg'))),
        'empty_filter_msg' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_empty_filter_msg'))),
        'is_typing' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_is_typing'))),
        'send_a_msg' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_send_a_msg'))),
        'viewed_products' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_viewed_products'))),
        'shopping_cart' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_shopping_cart'))),
        'cart_updating' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_cart_updating'))),
        'cart_removing' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_cart_removing'))),
        'sys_key_help' => get_option('qlcd_woo_chatbot_sys_key_help'),
        'sys_key_product' => get_option('qlcd_woo_chatbot_sys_key_product'),
        'sys_key_catalog' => get_option('qlcd_woo_chatbot_sys_key_catalog'),
        'sys_key_order' => get_option('qlcd_woo_chatbot_sys_key_order'),
        'sys_key_support' => get_option('qlcd_woo_chatbot_sys_key_support'),
        'sys_key_reset' => get_option('qlcd_woo_chatbot_sys_key_reset'),
        'help_welcome' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_help_welcome'))),
        'back_to_start' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_back_to_start'))),
        'help_msg' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_help_msg'))),
        'reset' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_reset'))),
        'wildcard_product' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_wildcard_product'))),
        'wildcard_catalog' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_wildcard_catalog'))),
        'featured_products' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_featured_products'))),
        'sale_products' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_sale_products'))),
        'wildcard_order' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_wildcard_order'))),
        'wildcard_support' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_wildcard_support'))),
        'product_asking' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_product_asking'))),
        'product_suggest' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_product_suggest'))),
        'product_infinite' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_product_infinite'))),
        'product_success' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_product_success'))),
        'product_fail' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_product_fail'))),
        'support_welcome' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_support_welcome'))),
        'support_email' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_support_email'))),
        'support_option_again' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_support_option_again'))),
        'asking_email' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_asking_email'))),
        'asking_msg' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_asking_msg'))),
        'support_phone' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_support_phone'))),
        'asking_phone' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_asking_phone'))),
        'thank_for_phone' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_thank_for_phone'))),
        'support_query' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('support_query'))),
        'support_ans' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('support_ans'))),
        'notification_interval' => get_option('qlcd_woo_chatbot_notification_interval'),
        'notifications' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_notifications'))),
        'order_welcome' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_order_welcome'))),
        'order_username_asking' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_order_username_asking'))),
        'order_username_password' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_order_username_password'))),
        'order_user' => get_option('qlcd_woo_chatbot_order_user'),
        'order_login' => is_user_logged_in(),
        'order_nonce' => wp_create_nonce("woowbot-order-nonce"),
        'order_email_support' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_order_email_support'))),
        'email_fail' => str_replace('\\', '', get_option('qlcd_woo_chatbot_email_fail')),
        'invalid_email' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_invalid_email'))),
        'stop_words' => str_replace('\\', '', get_option('qlcd_woo_chatbot_stop_words')),
        'currency_symbol' => get_woocommerce_currency_symbol(),
        'enable_messenger' => get_option('enable_woo_chatbot_messenger'),
        'messenger_label' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_messenger_label'))),
        'fb_page_id' => get_option('qlcd_woo_chatbot_fb_page_id'),
        'enable_skype' => get_option('enable_woo_chatbot_skype'),
        'enable_whats' => get_option('enable_woo_chatbot_whats'),
        'whats_label' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_whats_label'))),
        'whats_num' => get_option('qlcd_woo_chatbot_whats_num'),
        'ret_greet' => get_option('qlcd_woo_chatbot_ret_greet'),
        'enable_exit_intent' => get_option('enable_woo_chatbot_exit_intent'),
        'exit_intent_msg' => str_replace('\\', '', get_option('woo_chatbot_exit_intent_msg')),
        'exit_intent_once' => get_option('woo_chatbot_exit_intent_once'),
        'enable_scroll_open' => get_option('enable_woo_chatbot_scroll_open'),
        'scroll_open_msg' => str_replace('\\', '', get_option('woo_chatbot_scroll_open_msg')),
        'scroll_open_percent' => get_option('woo_chatbot_scroll_percent'),
        'scroll_open_once' => get_option('woo_chatbot_scroll_once'),
        'enable_auto_open' => get_option('enable_woo_chatbot_auto_open'),
        'auto_open_msg' => str_replace('\\', '', get_option('woo_chatbot_auto_open_msg')),
        'auto_open_time' => get_option('woo_chatbot_auto_open_time'),
        'auto_open_once' => get_option('woo_chatbot_auto_open_once'),
        'proactive_bg_color' => get_option('woo_chatbot_proactive_bg_color'),
        'disable_feedback' => get_option('disable_woo_chatbot_feedback'),
        'feedback_label' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('qlcd_woo_chatbot_feedback_label'))),
        'enable_meta_title' => get_option('enable_woo_chatbot_meta_title'),
        'meta_label' => str_replace('\\', '', get_option('qlcd_woo_chatbot_meta_label')),
        'phone_number' => get_option('qlcd_woo_chatbot_phone'),
        'call_gen' => get_option('disable_woo_chatbot_call_gen'),
        'call_sup' => get_option('disable_woo_chatbot_call_sup'),
        'enable_ret_sound' => get_option('enable_woo_chatbot_ret_sound'),
        'enable_ret_user_show' => get_option('enable_woo_chatbot_ret_user_show'),
        'enable_inactive_time_show' => get_option('enable_woo_chatbot_inactive_time_show'),
        'ret_inactive_user_once' => get_option('woo_chatbot_inactive_once'),
        'mobile_full_screen' => get_option('enable_woo_chatbot_mobile_full_screen'),
        'inactive_time' => get_option('woo_chatbot_inactive_time'),
        'checkout_msg' => str_replace('\\', '', get_option('woo_chatbot_checkout_msg')),
        'ai_df_enable' => get_option('enable_woo_chatbot_dailogflow'),

        'ai_df_token' => get_option('qlcd_woo_chatbot_dialogflow_client_token'),
        'df_defualt_reply' => str_replace('\\', '', get_option('qlcd_woo_chatbot_dialogflow_defualt_reply')),
        'custom_search_enable' => get_option('enable_woo_chatbot_custom_search'),
        'custom_intent_labels' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('custom_intent_label'))),
        'custom_intent_names' => qcld_woo_chatbot_str_replace_api(unserialize(get_option('custom_intent_name'))),
    );
    return $woo_chatbot_obj;
}

function qcld_woo_chatbot_str_replace_api($messages = array())
{
    $refined_mesgses = array();
    if (!empty($messages)) {
        foreach ($messages as $message) {
            $refined_msg = str_replace('\\', '', $message);
            array_push($refined_mesgses, $refined_msg);
        }
    }
    return $refined_mesgses;
}

function qcld_woo_chatbot_agent_icon_api()
{
    if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') == "custom-agent.png") {
        $woo_chatbot_custom_icon_path = get_option('woo_chatbot_custom_agent_path');
    } else if (get_option('woo_chatbot_custom_agent_path') != "" && get_option('woo_chatbot_agent_image') != "custom-agent.png") {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . get_option('woo_chatbot_agent_image');
    } else {
        $woo_chatbot_custom_icon_path = QCLD_WOOCHATBOT_IMG_URL . 'custom-agent.png';
    }
    return $woo_chatbot_custom_icon_path;
}