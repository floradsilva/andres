<?php
/**
 * Plugin Name: WoowBot WooCommerce Chatbot Pro
 * Plugin URI: https://wordpress.org/plugins/woowbot-woocommerce-chatbot/
 * Description: WooCommerce Chatbot by QuantumCloud.
 * Donate link: http://www.quantumcloud.com
 * Version: 10.9.6
 * @author    QuantumCloud
 * @category  WooCommerce
 * Author: QunatumCloud
 * Author URI: https://www.quantumcloud.com/
 * Requires at least: 4.0
 * Tested up to: 5.0
 * Text Domain: woochatbot
 * Domain Path: /languages
 * License: GPL2
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly
define('QCLD_WOOCHATBOT_VERSION', '1.0');
define('QCLD_WOOCHATBOT_REQUIRED_WOOCOMMERCE_VERSION', 2.2);
define('QCLD_WOOCHATBOT_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('QCLD_WOOCHATBOT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('QCLD_WOOCHATBOT_IMG_URL', QCLD_WOOCHATBOT_PLUGIN_URL . "images/");
define('QCLD_WOOCHATBOT_IMG_ABSOLUTE_PATH', plugin_dir_path(__FILE__) . "images");
define('QCLD_WOOCHATBOT_INDEX_TABLE', 'woowbot_index');
//define('QCLD_WOOCHATBOT_CACHE_TABLE', 'woowbot_cache');
require_once("qcld-woowbot-search.php");
require_once("functions.php");
if(file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH. "/conversion-tracker/class-qc-conversion-tracker.php")){
    require_once (QCLD_WOOCHATBOT_PLUGIN_DIR_PATH. "/conversion-tracker/class-qc-conversion-tracker.php");
}
if(file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH. "/includes/WoowBot_api.php")){
    require_once (QCLD_WOOCHATBOT_PLUGIN_DIR_PATH. "/includes/WoowBot_api.php");
}
/**
 * Main Class.
 */
class QCLD_Woo_Chatbot
{
    private $id = 'woowbot';
    private static $instance;
    /**
     *  Get Instance creates a singleton class that's cached to stop duplicate instances
     */
    public static function qcld_woo_chatbot_get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->qcld_woo_chatbot_init();
        }
        return self::$instance;
    }
    /**
     *  Construct empty on purpose
     */
    private function __construct()
    {
    }
    /**
     *  Init behaves like, and replaces, construct
     */
    public function qcld_woo_chatbot_init()
    {
        // Check if WooCommerce is active, and is required WooCommerce version.
        if (!class_exists('WooCommerce') || version_compare(get_option('woocommerce_db_version'), QCLD_WOOCHATBOT_REQUIRED_WOOCOMMERCE_VERSION, '<')) {
            add_action('admin_notices', array($this, 'woocommerce_inactive_notice_for_woo_chatbot'));
            return;
        }
        add_action('admin_menu', array($this, 'qcld_woo_chatbot_admin_menu'));
        if ((!empty($_GET["page"])) && ($_GET["page"] == "woowbot")) {
            add_action('admin_init', array($this, 'qcld_woo_chatbot_save_options'));
        }
        if (is_admin() && !empty($_GET["page"]) && ($_GET["page"] == "woowbot")) {
            add_action('admin_enqueue_scripts', array($this, 'qcld_woo_chatbot_admin_scripts'));
            if( get_option('woo_chatbot_index_count')<=0 && get_option('qlcd_woo_chatbot_search_option')=='advanced'){
                add_action( 'admin_notices', array( $this, 'admin_notice_reindex' ) );
            }
        }
        if (!is_admin()) {
            add_action('wp_enqueue_scripts', array($this, 'qcld_woo_chatbot_frontend_scripts'));
        }
    }
    /**
     * Add a submenu item to the WooCommerce menu
     */
    public function qcld_woo_chatbot_admin_menu()
    {
       /* add_submenu_page('woocommerce',
            __('WoowBot Pro', 'woochatbot'),
            __('WoowBot Pro', 'woochatbot'),
            'manage_woocommerce',
            $this->id,
            array($this, 'qcld_woo_chatbot_admin_page'));*/
        add_menu_page( 'WoowBot Pro', 'WoowBot Pro', 'manage_options','woowbot', array($this, 'qcld_woo_chatbot_admin_page'),'dashicons-format-status', 6 );
    }
    /**
     * Include admin scripts
     */
    public function qcld_woo_chatbot_admin_scripts($hook)
    {
        global $woocommerce, $wp_scripts;
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        if (((!empty($_GET["page"])) && ($_GET["page"] == "woowbot")) || ($hook == "widgets.php")) {
            wp_enqueue_script('jquery');
            wp_enqueue_style('woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css');
            wp_register_style('qlcd-woo-chatbot-admin-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/admin-style.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qlcd-woo-chatbot-admin-style');
            wp_register_style('qlcd-woo-chatbot-font-awesome', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/font-awesome.min.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qlcd-woo-chatbot-font-awesome');
            wp_register_style('qlcd-woo-chatbot-tabs-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/woo-chatbot-tabs.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qlcd-woo-chatbot-tabs-style');
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_style( 'wp-color-picker');
            wp_enqueue_script( 'wp-color-picker');
            wp_enqueue_script( 'jquery-ui-sortable');
            wp_register_script('qcld-woo-chatbot-cbpFWTabs', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/cbpFWTabs.js', basename(__FILE__)), array(), true);
            wp_enqueue_script('qcld-woo-chatbot-cbpFWTabs');
            wp_register_script('qcld-woo-chatbot-modernizr-custom', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/modernizr.custom.js', basename(__FILE__)), array(), true);
            wp_enqueue_script('qcld-woo-chatbot-modernizr-custom');
            wp_register_script('qcld-woo-chatbot-bootstrap-js', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/bootstrap.js', basename(__FILE__)), array('jquery'), true);
            wp_enqueue_script('qcld-woo-chatbot-bootstrap-js');
            wp_register_style('qcld-woo-chatbot-bootstrap-css', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/bootstrap.min.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qcld-woo-chatbot-bootstrap-css');
            //jquery time picker
            wp_register_script('qcld-woo-chatbot-timepicker-js', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/jquery.timepicker.js', basename(__FILE__)), array('jquery'), true);
            wp_enqueue_script('qcld-woo-chatbot-timepicker-js');
            wp_register_style('qcld-woo-chatbot-timepicker-css', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/jquery.timepicker.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qcld-woo-chatbot-timepicker-css');
            wp_register_script('qcld-woo-chatbot-admin-js', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/qcld-woo-chatbot-admin.js', basename(__FILE__)), array('jquery', 'jquery-ui-core','jquery-ui-sortable','wp-color-picker','qcld-woo-chatbot-timepicker-js'), true);
            wp_enqueue_script('qcld-woo-chatbot-admin-js');
            wp_localize_script('qcld-woo-chatbot-admin-js', 'ajax_object',
                array('ajax_url' => admin_url('admin-ajax.php'),'image_path' => QCLD_WOOCHATBOT_IMG_URL));
            // WordPress  Media library
            wp_enqueue_media();
        }
    }
    public function qcld_woo_chatbot_frontend_scripts()
    {
        global $woocommerce, $wp_scripts;
        $woo_chatbot_obj = array(
            'woo_chatbot_position_x' => get_option('woo_chatbot_position_x'),
            'woo_chatbot_position_y' => get_option('woo_chatbot_position_y'),
            'woo_chatbot_position_x_mobile' => get_option('woo_chatbot_position_x_mobile'),
            'woo_chatbot_position_y_mobile' => get_option('woo_chatbot_position_y_mobile'),
            'disable_icon_animation' => get_option('disable_woo_chatbot_icon_animation'),
            'disable_featured_product' => get_option('disable_woo_chatbot_featured_product'),
            'disable_product_search' => get_option('disable_woo_chatbot_product_search'),
            'disable_catalog' => get_option('disable_woo_chatbot_catalog'),
			'skip_woo_greetings' => get_option('skip_woo_greetings'),
			'limit_msg_show' => get_option('limit_msg_show'),
            'disable_order_status' => get_option('disable_woo_chatbot_order_status'),
            'disable_support' => get_option('disable_woo_chatbot_support'),
            'disable_sale_product' => get_option('disable_woo_chatbot_sale_product'),
            'open_product_detail' => get_option('woo_chatbot_open_product_detail'),
            'order_user' => get_option('qlcd_woo_chatbot_order_user'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'image_path' => QCLD_WOOCHATBOT_IMG_URL,
            'yes' => str_replace('\\', '',get_option('qlcd_woo_chatbot_yes')),
            'no' => str_replace('\\', '',get_option('qlcd_woo_chatbot_no')),
            'hello' => str_replace('\\', '',get_option('qlcd_woo_chatbot_hello')),
            'or' => str_replace('\\', '',get_option('qlcd_woo_chatbot_or')),
            'host' => str_replace('\\', '',get_option('qlcd_woo_chatbot_host')),
            'agent' => str_replace('\\', '',get_option('qlcd_woo_chatbot_agent')),
            'agent_image' => get_option('woo_chatbot_agent_image'),
            'agent_image_path' => $this->qcld_woo_chatbot_agent_icon(),
            'shopper_demo_name' => str_replace('\\', '',get_option('qlcd_woo_chatbot_shopper_demo_name')),
            'agent_join' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_agent_join'))),
            'welcome' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_welcome'))),
            'welcome_back' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_welcome_back'))),
            'hi_there' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_hi_there'))),
            'asking_name' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_asking_name'))),
            'i_am' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_i_am'))),
            'name_greeting' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_name_greeting'))),
            'wildcard_msg' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_wildcard_msg'))),
            'empty_filter_msg' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_empty_filter_msg'))),
            'is_typing' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_is_typing'))),
            'send_a_msg' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_send_a_msg'))),
            'viewed_products' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_viewed_products'))),
            'shopping_cart' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_shopping_cart'))),
            'cart_updating' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_cart_updating'))),
            'cart_removing' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_cart_removing'))),
            'sys_key_help' => get_option('qlcd_woo_chatbot_sys_key_help'),
            'sys_key_product' => get_option('qlcd_woo_chatbot_sys_key_product'),
            'sys_key_catalog' => get_option('qlcd_woo_chatbot_sys_key_catalog'),
            'sys_key_order' => get_option('qlcd_woo_chatbot_sys_key_order'),
            'sys_key_support' => get_option('qlcd_woo_chatbot_sys_key_support'),
            'sys_key_reset' => get_option('qlcd_woo_chatbot_sys_key_reset'),
            'help_welcome' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_help_welcome'))),
            'back_to_start' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_back_to_start'))),
            'help_msg' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_help_msg'))),
            'reset' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_reset'))),
            'wildcard_product' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_wildcard_product'))),
            'wildcard_catalog' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_wildcard_catalog'))),
            'featured_products' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_featured_products'))),
            'sale_products' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_sale_products'))),
            'wildcard_order' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_wildcard_order'))),
            'wildcard_support' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_wildcard_support'))),
            'product_asking' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_product_asking'))),
            'product_suggest' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_product_suggest'))),
            'product_infinite' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_product_infinite'))),
            'product_success' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_product_success'))),
            'product_fail' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_product_fail'))),
            'support_welcome' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_support_welcome'))),
            'support_email' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_support_email'))),
            'support_option_again' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_support_option_again'))),
            'asking_email' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_asking_email'))),
            'asking_msg' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_asking_msg'))),
            'support_phone' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_support_phone'))),
            'asking_phone' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_asking_phone'))),
            'thank_for_phone' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_thank_for_phone'))),
            'support_query' =>$this->qcld_woo_chatbot_str_replace(unserialize( get_option('support_query'))),
            'support_ans' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('support_ans'))),
            'notification_interval' => get_option('qlcd_woo_chatbot_notification_interval'),
            'notifications' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_notifications'))),
            'order_welcome' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_order_welcome'))),
            'order_username_asking' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_order_username_asking'))),
            'order_username_password' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_order_username_password'))),
            'order_user' => get_option('qlcd_woo_chatbot_order_user'),
            'order_login' => is_user_logged_in(),
            'order_nonce' => wp_create_nonce("woowbot-order-nonce"),
            'order_email_support' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_order_email_support'))),
            'email_fail' => str_replace('\\', '', get_option('qlcd_woo_chatbot_email_fail')),
            'invalid_email' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_invalid_email'))),
            'language' => str_replace('\\', '', get_option('qlcd_woo_chatbot_stop_words_name')),
            'stop_words' => str_replace('\\', '', get_option('qlcd_woo_chatbot_stop_words')),
            'currency_symbol' => get_woocommerce_currency_symbol(),
            'hide_add_to_cart' => get_option('woo_chatbot_hide_product_details_add_to_cart'),
            'enable_messenger' => get_option('enable_woo_chatbot_messenger'),
            'messenger_label' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_messenger_label'))),
            'fb_page_id' => get_option('qlcd_woo_chatbot_fb_page_id'),
            'enable_skype' => get_option('enable_woo_chatbot_skype'),
            'enable_whats' => get_option('enable_woo_chatbot_whats'),
            'whats_label' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_whats_label'))),
            'whats_num' => get_option('qlcd_woo_chatbot_whats_num'),
            'livechat' => get_option('enable_woo_custom_intent_livechat_button'),
            'livechatlink' => get_option('qlcd_woo_chatbot_livechatlink'),
            'livechat_button_label' => get_option('qlcd_woo_livechat_button_label'),
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
            'proactive_text_color' => get_option('woo_chatbot_proactive_text_color'),
            'disable_feedback' => get_option('disable_woo_chatbot_feedback'),
            'feedback_label' =>$this->qcld_woo_chatbot_str_replace(unserialize(get_option('qlcd_woo_chatbot_feedback_label'))),
            'enable_meta_title' =>get_option('enable_woo_chatbot_meta_title'),
            'meta_label' =>str_replace('\\', '', get_option('qlcd_woo_chatbot_meta_label')),
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
            'custom_intent_enable' => get_option('enable_woo_chatbot_custom_intent'),
			'template_name' => get_option('qcld_woo_chatbot_theme'),
            'rich_response_enable' => get_option('enable_woo_chatbot_rich_response'),
            'custom_intent_labels' =>$this->qcld_woo_chatbot_str_replace(unserialize( get_option('custom_intent_labels'))),
            'custom_intent_kewords' =>$this->qcld_woo_chatbot_str_replace(unserialize( get_option('custom_intent_kewords'))),
            'custom_intent_names' => $this->qcld_woo_chatbot_str_replace(unserialize(get_option('custom_intent_names'))),
			'imgurl' => QCLD_WOOCHATBOT_IMG_URL,
        );
        wp_register_script('qcld-woo-chatbot-slimscroll-js', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/jquery.slimscroll.min.js', basename(__FILE__)), array('jquery'), QCLD_WOOCHATBOT_VERSION, true);
        wp_enqueue_script('qcld-woo-chatbot-slimscroll-js');
        wp_register_script('qcld-woo-chatbot-jquery-cookie', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/jquery.cookie.js', basename(__FILE__)), array('jquery'), QCLD_WOOCHATBOT_VERSION, true);
        wp_enqueue_script('qcld-woo-chatbot-jquery-cookie');
        wp_register_script('qcld-woo-chatbot-magnify-popup', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/jquery.magnific-popup.min.js', basename(__FILE__)), array('jquery'), QCLD_WOOCHATBOT_VERSION, true);
        wp_enqueue_script('qcld-woo-chatbot-magnify-popup');
        wp_register_script('qcld-woo-chatbot-plugin', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/qcld-woo-chatbot-plugin.js', basename(__FILE__)), array('jquery', 'qcld-woo-chatbot-jquery-cookie','qcld-woo-chatbot-magnify-popup'), QCLD_WOOCHATBOT_VERSION, true);
        wp_enqueue_script('qcld-woo-chatbot-plugin');
        wp_register_script('qcld-woo-chatbot-front-js', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/qcld-woo-chatbot-front.js', basename(__FILE__)), array('jquery', 'qcld-woo-chatbot-jquery-cookie'), QCLD_WOOCHATBOT_VERSION, true);
        wp_enqueue_script('qcld-woo-chatbot-front-js');
        wp_localize_script('qcld-woo-chatbot-front-js', 'woo_chatbot_obj', $woo_chatbot_obj);
        //wp_register_script('qcld-woo-chatbot-frontend', plugins_url(basename(plugin_dir_path(__FILE__)) . '/js/qcld-woo-chatbot-frontend.js', basename(__FILE__)), array('jquery','qcld-woo-chatbot-jquery-cookie'), QCLD_WOOCHATBOT_VERSION, true);
        //wp_enqueue_script('qcld-woo-chatbot-frontend');
        //wp_localize_script('qcld-woo-chatbot-frontend', 'woo_chatbot_obj', $woo_chatbot_obj);
        wp_localize_script('qcld-woo-chatbot-frontend', 'woo_chatbot_obj', $woo_chatbot_obj);
        wp_register_style('qcld-woo-chatbot-common-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/common-style.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
        wp_enqueue_style('qcld-woo-chatbot-common-style');
        wp_register_style('qcld-woo-chatbot-magnific-popup', plugins_url(basename(plugin_dir_path(__FILE__)) . '/css/magnific-popup.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
        wp_enqueue_style('qcld-woo-chatbot-magnific-popup');
        $qcld_woo_chatbot_theme = get_option('qcld_woo_chatbot_theme');
        /* if (file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/style.css')) {
             wp_register_style('qcld-woo-chatbot-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/templates/' . $qcld_woo_chatbot_theme . '/style.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
             wp_enqueue_style('qcld-woo-chatbot-style');
         }*/
        //Loading shortcode style
        if (file_exists(QCLD_WOOCHATBOT_PLUGIN_DIR_PATH . '/templates/' . $qcld_woo_chatbot_theme . '/shortcode.css')) {
            wp_register_style('qcld-woo-chatbot-shortcode-style', plugins_url(basename(plugin_dir_path(__FILE__)) . '/templates/' . $qcld_woo_chatbot_theme . '/shortcode.css', basename(__FILE__)), '', QCLD_WOOCHATBOT_VERSION, 'screen');
            wp_enqueue_style('qcld-woo-chatbot-shortcode-style');
        }
    }
    public function qcld_woo_chatbot_str_replace($messages=array()){
        $refined_mesgses=array();
        if(!empty($messages)){
            foreach ($messages as $message){
                $refined_msg=str_replace('\\', '', $message);
                array_push($refined_mesgses,$refined_msg);
            }
        }
        return $refined_mesgses;
    }
    //getting exact agent icon path
    public  function qcld_woo_chatbot_agent_icon(){
        if(get_option('woo_chatbot_custom_agent_path')!="" && get_option('woo_chatbot_agent_image')=="custom-agent.png"  ){
            $woo_chatbot_custom_icon_path=get_option('woo_chatbot_custom_agent_path');
        }else if(get_option('woo_chatbot_custom_agent_path')!="" && get_option('woo_chatbot_agent_image')!="custom-agent.png"){
            $woo_chatbot_custom_icon_path=QCLD_WOOCHATBOT_IMG_URL.get_option('woo_chatbot_agent_image');
        }else{
            $woo_chatbot_custom_icon_path=QCLD_WOOCHATBOT_IMG_URL.'custom-agent.png';
        }
        return $woo_chatbot_custom_icon_path;
    }
    /**
     * Render the admin page
     */
    public function qcld_woo_chatbot_admin_page()
    {
        global $woocommerce;
        $action = 'admin.php?page=woowbot';
        require_once("admin_ui.php");
    }
    public function qcld_woo_chatbot_dynamic_multi_option($options = array(), $option_name = "", $option_text = "")
    {
        ?>
        <h4 class="qc-opt-title"><?php _e($option_text, 'woochatbot'); ?></h4>
        <div class="woo-chatbot-lng-items">
            <?php
            if (is_array($options) && count($options) > 0) {
                foreach ($options as $key => $value) {
                    ?>
                    <div class="row" class="woo-chatbot-lng-item">
                        <div class="col-xs-10">
                            <input type="text"
                                   class="form-control qc-opt-dcs-font"
                                   name="<?php echo $option_name; ?>[]"
                                   value="<?php echo str_replace('\\', '', $value); ?>">
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-danger btn-sm woo-chatbot-lng-item-remove">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </div>
                    </div>
                    <?php
                }
            } else { ?>
                <div class="row" class="woo-chatbot-lng-item">
                    <div class="col-xs-10">
                        <input type="text"
                               class="form-control qc-opt-dcs-font"
                               name="<?php echo $option_name; ?>[]"
                               value="<?php echo $option_text; ?>">
                    </div>
                    <div class="col-xs-2">
                        <span class="woo-chatbot-lng-item-remove"><?php _e('X', 'woochatbot'); ?></span>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-sm-2 col-sm-offset-10">
                <button type="button" class="btn btn-success btn-sm woo-chatbot-lng-item-add"> <span class="glyphicon glyphicon-plus"></span> </button>
            </div>
        </div>
        <?php
    }
    public function woo_chatbot_opening_hours($day_name,$woowbot_times){
        if(!empty($woowbot_times) && isset($woowbot_times[$day_name])){
            $day_times=$woowbot_times[$day_name];
            if(!empty($day_times)){
                $segment=0;
                foreach ($day_times as $day_time ){
        ?>
            <div class="woo-chatbot-hours-container">
                <div class="woo-chatbot-hours">
                    <input type="text" class="woo-chatbot-hour" name="woowbot_hours[<?php echo $day_name; ?>][<?php echo $segment; ?>][]" value="<?php if(isset($day_time[0])){echo $day_time[0];}else{ echo "00:00";}  ?>" >
                    <input type="text" class="woo-chatbot-hour" name="woowbot_hours[<?php echo $day_name; ?>][<?php echo $segment; ?>][]" value="<?php if(isset($day_time[1])){echo $day_time[1];}else{ echo "00:00";}  ?>" >
                </div>
                <div class="woo-chatbot-hours-remove">
                    <button type="button" class="btn btn-danger btn-sm woo-chatbot-hours-remove-btn">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

        <?php
            $segment++;
            }
          }
        }else{
        ?>
            <div class="woo-chatbot-hours-container">
                <div class="woo-chatbot-hours">
                    <input type="text" class="woo-chatbot-hour" name="woowbot_hours[<?php echo $day_name; ?>][0][]" value="00:00" > <input type="text" name="woowbot_hours[<?php echo $day_name; ?>][0][]" value="00:00">
                </div>
                <div class="woo-chatbot-hours-remove">
                    <button type="button" class="btn btn-danger btn-sm woo-chatbot-hours-remove-btn">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        <?php
        }
    }
    function qcld_woo_chatbot_save_options()
    {
        global $woocommerce;
        if (isset($_POST['_wpnonce']) && $_POST['_wpnonce']) {
            wp_verify_nonce($_POST['_wpnonce'], 'woo_chatbot');
            // Check if the form is submitted or not
            if (isset($_POST['submit'])) {
                //Woowboticon position settings.
                if (isset($_POST["woo_chatbot_position_x"])) {
                    $woo_chatbot_position_x = stripslashes(($_POST["woo_chatbot_position_x"]));
                    update_option('woo_chatbot_position_x', $woo_chatbot_position_x);
                }
                if (isset($_POST["woo_chatbot_position_y"])) {
                    $woo_chatbot_position_y = stripslashes(($_POST["woo_chatbot_position_y"]));
                    update_option('woo_chatbot_position_y', $woo_chatbot_position_y);
                }
                if (isset($_POST["woo_chatbot_position_x_mobile"])) {
                    $woo_chatbot_position_x_mobile = stripslashes(($_POST["woo_chatbot_position_x_mobile"]));
                    update_option('woo_chatbot_position_x_mobile', $woo_chatbot_position_x_mobile);
                }
                if (isset($_POST["woo_chatbot_position_y_mobile"])) {
                    $woo_chatbot_position_y_mobile = stripslashes(($_POST["woo_chatbot_position_y_mobile"]));
                    update_option('woo_chatbot_position_y_mobile', $woo_chatbot_position_y_mobile);
                }

                //product search options
                $qlcd_woo_chatbot_search_option = $_POST['qlcd_woo_chatbot_search_option'];
                update_option('qlcd_woo_chatbot_search_option', sanitize_text_field($qlcd_woo_chatbot_search_option));
                //Enable /disable Woowbot
               if(isset( $_POST["disable_woo_chatbot"])){
                   $disable_woo_chatbot = $_POST["disable_woo_chatbot"];
               }else{ $disable_woo_chatbot='';}
                update_option('disable_woo_chatbot', stripslashes($disable_woo_chatbot));
                
				if(isset( $_POST["disable_woo_chatbot_on_mobile"])) {
                    $disable_woo_chatbot_on_mobile = $_POST["disable_woo_chatbot_on_mobile"];
                }else{ $disable_woo_chatbot_on_mobile='';}
                update_option('disable_woo_chatbot_on_mobile', stripslashes($disable_woo_chatbot_on_mobile));
				
				
				if(isset( $_POST["skip_woo_greetings"])) {
                    $skip_woo_greetings = $_POST["skip_woo_greetings"];
                }else{ $skip_woo_greetings='';}
                update_option('skip_woo_greetings', stripslashes($skip_woo_greetings));
				
				
				if(isset( $_POST["limit_msg_show"])) {
                    $limit_msg_show = $_POST["limit_msg_show"];
                }else{ $limit_msg_show='';}
                update_option('limit_msg_show', stripslashes($limit_msg_show));
				
                
				
				if(isset( $_POST["disable_woo_chatbot_product_search"])) {
                $disable_woo_chatbot_product_search = $_POST["disable_woo_chatbot_product_search"];
                }else{ $disable_woo_chatbot_product_search='';}
                update_option('disable_woo_chatbot_product_search', stripslashes($disable_woo_chatbot_product_search));
                if(isset( $_POST["disable_woo_chatbot_catalog"])) {
                $disable_woo_chatbot_catalog= $_POST["disable_woo_chatbot_catalog"];
                }else{ $disable_woo_chatbot_catalog='';}
                update_option('disable_woo_chatbot_catalog', stripslashes($disable_woo_chatbot_catalog));

                if(isset( $_POST["disable_woo_chatbot_order_status"])) {
                    $disable_woo_chatbot_order_status = $_POST["disable_woo_chatbot_order_status"];
                }else{ $disable_woo_chatbot_order_status='';}
                update_option('disable_woo_chatbot_order_status', stripslashes($disable_woo_chatbot_order_status));

                if(isset( $_POST["disable_woo_chatbot_support"])) {
                    $disable_woo_chatbot_support = $_POST["disable_woo_chatbot_support"];
                }else{ $disable_woo_chatbot_support='';}
                update_option('disable_woo_chatbot_support', stripslashes($disable_woo_chatbot_support));


                if(isset( $_POST["disable_woo_chatbot_notification"])) {
                    $disable_woo_chatbot_notification = $_POST["disable_woo_chatbot_notification"];
                }else{ $disable_woo_chatbot_notification='';}
                update_option('disable_woo_chatbot_notification', stripslashes($disable_woo_chatbot_notification));

                if(isset( $_POST["disable_woo_chatbot_notification_mobile"])) {
                    $disable_woo_chatbot_notification_mobile= $_POST["disable_woo_chatbot_notification_mobile"];
                }else{ $disable_woo_chatbot_notification_mobile='';}
                update_option('disable_woo_chatbot_notification_mobile', stripslashes($disable_woo_chatbot_notification_mobile));

                if(isset( $_POST["enable_woo_chatbot_rtl"])) {
                    $enable_woo_chatbot_rtl = $_POST["enable_woo_chatbot_rtl"];
                }else{ $enable_woo_chatbot_rtl='';}
                update_option('enable_woo_chatbot_rtl', stripslashes($enable_woo_chatbot_rtl));

               if(isset( $_POST["enable_woo_chatbot_mobile_full_screen"])) {
                    $enable_woo_chatbot_mobile_full_screen = $_POST["enable_woo_chatbot_mobile_full_screen"];
                }else{ $enable_woo_chatbot_mobile_full_screen='';}
                update_option('enable_woo_chatbot_mobile_full_screen', stripslashes($enable_woo_chatbot_mobile_full_screen));

                if(isset( $_POST["disable_woo_chatbot_icon_animation"])) {
                    $disable_woo_chatbot_icon_animation = $_POST["disable_woo_chatbot_icon_animation"];
                }else{ $disable_woo_chatbot_icon_animation='';}
                update_option('disable_woo_chatbot_icon_animation', stripslashes($disable_woo_chatbot_icon_animation));
                //Enable /disable Cart Item Number
                if(isset( $_POST["disable_woo_chatbot_cart_item_number"])) {
                    $disable_woo_chatbot_cart_item_number = $_POST["disable_woo_chatbot_cart_item_number"];
                }else{ $disable_woo_chatbot_cart_item_number='';}
                update_option('disable_woo_chatbot_cart_item_number', stripslashes($disable_woo_chatbot_cart_item_number));
                //Enable /disable featured products button.
                if(isset( $_POST["disable_woo_chatbot_featured_product"])) {
                    $disable_woo_chatbot_featured_product = $_POST["disable_woo_chatbot_featured_product"];
                }else{ $disable_woo_chatbot_featured_product='';}
                update_option('disable_woo_chatbot_featured_product', stripslashes($disable_woo_chatbot_featured_product));
                //Enable /disable sale products button
                if(isset( $_POST["disable_woo_chatbot_sale_product"])) {
                    $disable_woo_chatbot_sale_product = $_POST["disable_woo_chatbot_sale_product"];
                }else{ $disable_woo_chatbot_sale_product='';}
                update_option('disable_woo_chatbot_sale_product', stripslashes($disable_woo_chatbot_sale_product));
                //Enable Product details page.
                if(isset( $_POST["woo_chatbot_open_product_detail"])) {
                    $woo_chatbot_open_product_detail = $_POST["woo_chatbot_open_product_detail"];
                }else{ $woo_chatbot_open_product_detail='';}
                update_option('woo_chatbot_open_product_detail', stripslashes($woo_chatbot_open_product_detail));
                //product order and order by
                $qlcd_woo_chatbot_product_orderby = $_POST['qlcd_woo_chatbot_product_orderby'];
                update_option('qlcd_woo_chatbot_product_orderby', sanitize_text_field($qlcd_woo_chatbot_product_orderby));
                $qlcd_woo_chatbot_product_order = $_POST['qlcd_woo_chatbot_product_order'];
                update_option('qlcd_woo_chatbot_product_order', sanitize_text_field($qlcd_woo_chatbot_product_order));
                //Product per page settings.
                if (isset($_POST["qlcd_woo_chatbot_ppp"])) {
                    $qlcd_woo_chatbot_ppp = $_POST["qlcd_woo_chatbot_ppp"];
                    update_option('qlcd_woo_chatbot_ppp', intval($qlcd_woo_chatbot_ppp));
                }
                if(isset( $_POST["woo_chatbot_exclude_stock_out_product"])) {
                $woo_chatbot_exclude_stock_out_product = $_POST['woo_chatbot_exclude_stock_out_product'];
                }else{ $woo_chatbot_exclude_stock_out_product='';}
                update_option('woo_chatbot_exclude_stock_out_product', stripslashes($woo_chatbot_exclude_stock_out_product));

                if(isset( $_POST["woo_chatbot_hide_product_details_add_to_cart"])) {
                    $woo_chatbot_hide_product_details_add_to_cart = $_POST['woo_chatbot_hide_product_details_add_to_cart'];
                }else{ $woo_chatbot_hide_product_details_add_to_cart='';}
                update_option('woo_chatbot_hide_product_details_add_to_cart', stripslashes($woo_chatbot_hide_product_details_add_to_cart));

                if(isset( $_POST["woo_chatbot_show_parent_category"])) {
                    $woo_chatbot_show_parent_category = $_POST['woo_chatbot_show_parent_category'];
                }else{ $woo_chatbot_show_parent_category='';}
                update_option('woo_chatbot_show_parent_category', stripslashes($woo_chatbot_show_parent_category));
                if(isset( $_POST["woo_chatbot_show_sub_category"])) {
                    $woo_chatbot_show_sub_category = $_POST['woo_chatbot_show_sub_category'];
                }else{ $woo_chatbot_show_sub_category='';}
                update_option('woo_chatbot_show_sub_category', stripslashes($woo_chatbot_show_sub_category));
                if (isset($_POST["qlcd_woo_chatbot_order_user"])) {
                    $qlcd_woo_chatbot_order_user = $_POST["qlcd_woo_chatbot_order_user"];
                    update_option('qlcd_woo_chatbot_order_user', sanitize_text_field($qlcd_woo_chatbot_order_user));
                }
                //WoowBot Load control
                $woo_chatbot_show_home_page = sanitize_key(($_POST["woo_chatbot_show_home_page"]));
                update_option('woo_chatbot_show_home_page', $woo_chatbot_show_home_page);
                $woo_chatbot_show_posts = sanitize_key(($_POST["woo_chatbot_show_posts"]));
                update_option('woo_chatbot_show_posts', $woo_chatbot_show_posts);
                $woo_chatbot_show_pages = sanitize_key(($_POST["woo_chatbot_show_pages"]));
                update_option('woo_chatbot_show_pages', $woo_chatbot_show_pages);
                if(isset( $_POST["woo_chatbot_show_pages_list"])) {
                    $woo_chatbot_show_pages_list = $_POST["woo_chatbot_show_pages_list"];
                }else{ $woo_chatbot_show_pages_list='';}
                update_option('woo_chatbot_show_pages_list', serialize($woo_chatbot_show_pages_list));
                $woo_chatbot_show_woocommerce = sanitize_key(($_POST["woo_chatbot_show_woocommerce"]));
                update_option('woo_chatbot_show_woocommerce', $woo_chatbot_show_woocommerce);
                //Stop Words Settings
                if (isset($_POST["qlcd_woo_chatbot_stop_words_name"])) {
                    $qlcd_woo_chatbot_stop_words_name = $_POST["qlcd_woo_chatbot_stop_words_name"];
                    update_option('qlcd_woo_chatbot_stop_words_name', sanitize_text_field($qlcd_woo_chatbot_stop_words_name));
                }
                if (isset($_POST["qlcd_woo_chatbot_stop_words"])) {
                    $qlcd_woo_chatbot_stop_words = $_POST["qlcd_woo_chatbot_stop_words"];
                    update_option('qlcd_woo_chatbot_stop_words', sanitize_text_field($qlcd_woo_chatbot_stop_words));
                }
                //Woowbot icon settings.
                $woo_chatbot_icon = $_POST['woo_chatbot_icon'] ? $_POST['woo_chatbot_icon'] : 'icon-1.png';
                update_option('woo_chatbot_icon', sanitize_text_field($woo_chatbot_icon));
                // upload custom Woowbot icon path
                 $woo_chatbot_custom_icon_path = $_POST['woo_chatbot_custom_icon_path'];
                 update_option('woo_chatbot_custom_icon_path', sanitize_text_field($woo_chatbot_custom_icon_path));
                 //Agent image
                //Woowbot icon settings.
                $woo_chatbot_icon = $_POST['woo_chatbot_agent_image'] ? $_POST['woo_chatbot_agent_image'] : 'agent-0.png';
                 update_option('woo_chatbot_agent_image', sanitize_text_field($woo_chatbot_icon));
                // upload custom Woowbot icon
                $woo_chatbot_custom_agent_path = $_POST['woo_chatbot_custom_agent_path'];
                update_option('woo_chatbot_custom_agent_path', sanitize_text_field($woo_chatbot_custom_agent_path));
                //Theming
                $qcld_woo_chatbot_theme = $_POST['qcld_woo_chatbot_theme'] ? $_POST['qcld_woo_chatbot_theme'] : 'template-01';
                 update_option('qcld_woo_chatbot_theme', sanitize_text_field($qcld_woo_chatbot_theme));
                
				//custom icons
				if(isset( $_POST["qcld_woo_chatbot_custom_icons"])) {
                    $qcld_woo_chatbot_custom_icons = $_POST["qcld_woo_chatbot_custom_icons"];
                }else{$qcld_woo_chatbot_custom_icons='';}
                update_option('qcld_woo_chatbot_custom_icons', stripslashes($qcld_woo_chatbot_custom_icons));
				
				
				if(isset( $_POST["qcld_woo_chatbot_custom_icon_help"])) {
                    $qcld_woo_chatbot_custom_icon_help = $_POST["qcld_woo_chatbot_custom_icon_help"];
                }else{$qcld_woo_chatbot_custom_icon_help='';}
                update_option('qcld_woo_chatbot_custom_icon_help', stripslashes($qcld_woo_chatbot_custom_icon_help));
				
				
				if(isset( $_POST["qcld_woo_chatbot_custom_icon_support"])) {
					$qcld_woo_chatbot_custom_icon_support = $_POST["qcld_woo_chatbot_custom_icon_support"];
				}else{$qcld_woo_chatbot_custom_icon_support='';}
				update_option('qcld_woo_chatbot_custom_icon_support', stripslashes($qcld_woo_chatbot_custom_icon_support));
				
				
				if(isset( $_POST["qcld_woo_chatbot_custom_icon_recent"])) {
					$qcld_woo_chatbot_custom_icon_recent = $_POST["qcld_woo_chatbot_custom_icon_recent"];
				}else{$qcld_woo_chatbot_custom_icon_recent='';}
				update_option('qcld_woo_chatbot_custom_icon_recent', stripslashes($qcld_woo_chatbot_custom_icon_recent));
				
				
				if(isset( $_POST["qcld_woo_chatbot_custom_icon_cart"])) {
					$qcld_woo_chatbot_custom_icon_cart = $_POST["qcld_woo_chatbot_custom_icon_cart"];
				}else{$qcld_woo_chatbot_custom_icon_cart='';}
				update_option('qcld_woo_chatbot_custom_icon_cart', stripslashes($qcld_woo_chatbot_custom_icon_cart));
				
				
				if(isset( $_POST["qcld_woo_chatbot_custom_icon_chat"])) {
					$qcld_woo_chatbot_custom_icon_chat = $_POST["qcld_woo_chatbot_custom_icon_chat"];
				}else{$qcld_woo_chatbot_custom_icon_chat='';}
				update_option('qcld_woo_chatbot_custom_icon_chat', stripslashes($qcld_woo_chatbot_custom_icon_chat));
				
				
				
				//Theme custom background option
                if(isset( $_POST["qcld_woo_chatbot_change_bg"])) {
                    $qcld_woo_chatbot_change_bg = $_POST["qcld_woo_chatbot_change_bg"];
                }else{$qcld_woo_chatbot_change_bg='';}
                update_option('qcld_woo_chatbot_change_bg', stripslashes($qcld_woo_chatbot_change_bg));
                $qcld_woo_chatbot_board_bg_path = $_POST["qcld_woo_chatbot_board_bg_path"];
                update_option('qcld_woo_chatbot_board_bg_path', stripslashes($qcld_woo_chatbot_board_bg_path));
                //To override style use custom css.
                $woo_chatbot_custom_css = $_POST["woo_chatbot_custom_css"];
                update_option('woo_chatbot_custom_css', stripslashes($woo_chatbot_custom_css));
                /****Language center settings.   ****/
                //identity
                $qlcd_woo_chatbot_host = $_POST["qlcd_woo_chatbot_host"];
                update_option('qlcd_woo_chatbot_host', sanitize_text_field($qlcd_woo_chatbot_host));
                $qlcd_woo_chatbot_agent = $_POST["qlcd_woo_chatbot_agent"];
                update_option('qlcd_woo_chatbot_agent', sanitize_text_field($qlcd_woo_chatbot_agent));
                $qlcd_woo_chatbot_shopper_demo_name = $_POST["qlcd_woo_chatbot_shopper_demo_name"];
                update_option('qlcd_woo_chatbot_shopper_demo_name', sanitize_text_field($qlcd_woo_chatbot_shopper_demo_name));
                $qlcd_woo_chatbot_yes = $_POST["qlcd_woo_chatbot_yes"];
                update_option('qlcd_woo_chatbot_yes', sanitize_text_field($qlcd_woo_chatbot_yes));
                $qlcd_woo_chatbot_no = $_POST["qlcd_woo_chatbot_no"];
                update_option('qlcd_woo_chatbot_no', sanitize_text_field($qlcd_woo_chatbot_no));

                $qlcd_woo_chatbot_or = $_POST["qlcd_woo_chatbot_or"];
                update_option('qlcd_woo_chatbot_or', sanitize_text_field($qlcd_woo_chatbot_or));
                $qlcd_woo_chatbot_hello = $_POST["qlcd_woo_chatbot_hello"];
                update_option('qlcd_woo_chatbot_hello', sanitize_text_field($qlcd_woo_chatbot_hello));

                $qlcd_woo_chatbot_sorry = $_POST["qlcd_woo_chatbot_sorry"];
                update_option('qlcd_woo_chatbot_sorry', sanitize_text_field($qlcd_woo_chatbot_sorry));
				
				$qlcd_woo_chatbot_chat = $_POST["qlcd_woo_chatbot_chat"];
                update_option('qlcd_woo_chatbot_chat', sanitize_text_field($qlcd_woo_chatbot_chat));
				
				
                $qlcd_woo_chatbot_agent_join = $_POST["qlcd_woo_chatbot_agent_join"];
                update_option('qlcd_woo_chatbot_agent_join', serialize($qlcd_woo_chatbot_agent_join));
                //Greeting.
                $qlcd_woo_chatbot_welcome = $_POST["qlcd_woo_chatbot_welcome"];
                update_option('qlcd_woo_chatbot_welcome', serialize($qlcd_woo_chatbot_welcome));
                $qlcd_woo_chatbot_back_to_start = $_POST["qlcd_woo_chatbot_back_to_start"];
                update_option('qlcd_woo_chatbot_back_to_start', serialize($qlcd_woo_chatbot_back_to_start));
                $qlcd_woo_chatbot_hi_there = $_POST["qlcd_woo_chatbot_hi_there"];
                update_option('qlcd_woo_chatbot_hi_there', serialize($qlcd_woo_chatbot_hi_there));
                $qlcd_woo_chatbot_welcome_back = $_POST["qlcd_woo_chatbot_welcome_back"];
                update_option('qlcd_woo_chatbot_welcome_back', serialize($qlcd_woo_chatbot_welcome_back));
                $qlcd_woo_chatbot_asking_name = $_POST["qlcd_woo_chatbot_asking_name"];
                update_option('qlcd_woo_chatbot_asking_name', serialize($qlcd_woo_chatbot_asking_name));
                $qlcd_woo_chatbot_name_greeting = $_POST["qlcd_woo_chatbot_name_greeting"];
                update_option('qlcd_woo_chatbot_name_greeting', serialize($qlcd_woo_chatbot_name_greeting));
                $qlcd_woo_chatbot_i_am = $_POST["qlcd_woo_chatbot_i_am"];
                update_option('qlcd_woo_chatbot_i_am', serialize($qlcd_woo_chatbot_i_am));
                $qlcd_woo_chatbot_is_typing = $_POST["qlcd_woo_chatbot_is_typing"];
                update_option('qlcd_woo_chatbot_is_typing', serialize($qlcd_woo_chatbot_is_typing));
                $qlcd_woo_chatbot_send_a_msg= $_POST["qlcd_woo_chatbot_send_a_msg"];
                update_option('qlcd_woo_chatbot_send_a_msg', serialize($qlcd_woo_chatbot_send_a_msg));
                $qlcd_woo_chatbot_choose_option= $_POST["qlcd_woo_chatbot_choose_option"];
                update_option('qlcd_woo_chatbot_choose_option', serialize($qlcd_woo_chatbot_choose_option));
                $qlcd_woo_chatbot_viewed_products= $_POST["qlcd_woo_chatbot_viewed_products"];
                update_option('qlcd_woo_chatbot_viewed_products', serialize($qlcd_woo_chatbot_viewed_products));
                $qlcd_woo_chatbot_shopping_cart= $_POST["qlcd_woo_chatbot_shopping_cart"];
                update_option('qlcd_woo_chatbot_shopping_cart', serialize($qlcd_woo_chatbot_shopping_cart));
                $qlcd_woo_chatbot_add_to_cart= $_POST["qlcd_woo_chatbot_add_to_cart"];
                update_option('qlcd_woo_chatbot_add_to_cart', serialize($qlcd_woo_chatbot_add_to_cart));
                $qlcd_woo_chatbot_cart_link= $_POST["qlcd_woo_chatbot_cart_link"];
                update_option('qlcd_woo_chatbot_cart_link', serialize($qlcd_woo_chatbot_cart_link));
                $qlcd_woo_chatbot_checkout_link= $_POST["qlcd_woo_chatbot_checkout_link"];
                update_option('qlcd_woo_chatbot_checkout_link', serialize($qlcd_woo_chatbot_checkout_link));
                $qlcd_woo_chatbot_cart_welcome= $_POST["qlcd_woo_chatbot_cart_welcome"];
                update_option('qlcd_woo_chatbot_cart_welcome', serialize($qlcd_woo_chatbot_cart_welcome));
                $qlcd_woo_chatbot_featured_product_welcome= $_POST["qlcd_woo_chatbot_featured_product_welcome"];
                update_option('qlcd_woo_chatbot_featured_product_welcome', serialize($qlcd_woo_chatbot_featured_product_welcome));
                $qlcd_woo_chatbot_viewed_product_welcome= $_POST["qlcd_woo_chatbot_viewed_product_welcome"];
                update_option('qlcd_woo_chatbot_viewed_product_welcome', serialize($qlcd_woo_chatbot_viewed_product_welcome));
                $qlcd_woo_chatbot_latest_product_welcome= $_POST["qlcd_woo_chatbot_latest_product_welcome"];
                update_option('qlcd_woo_chatbot_latest_product_welcome', serialize($qlcd_woo_chatbot_latest_product_welcome));
                $qlcd_woo_chatbot_cart_title= $_POST["qlcd_woo_chatbot_cart_title"];
                update_option('qlcd_woo_chatbot_cart_title', serialize($qlcd_woo_chatbot_cart_title));
                $qlcd_woo_chatbot_cart_quantity= $_POST["qlcd_woo_chatbot_cart_quantity"];
                update_option('qlcd_woo_chatbot_cart_quantity', serialize($qlcd_woo_chatbot_cart_quantity));
                $qlcd_woo_chatbot_cart_price= $_POST["qlcd_woo_chatbot_cart_price"];
                update_option('qlcd_woo_chatbot_cart_price', serialize($qlcd_woo_chatbot_cart_price));
				
				$qlcd_woo_chatbot_cart_total= $_POST["qlcd_woo_chatbot_cart_total"];
                update_option('qlcd_woo_chatbot_cart_total', serialize($qlcd_woo_chatbot_cart_total));
				
                $qlcd_woo_chatbot_no_cart_items= $_POST["qlcd_woo_chatbot_no_cart_items"];
                update_option('qlcd_woo_chatbot_no_cart_items', serialize($qlcd_woo_chatbot_no_cart_items));
                $qlcd_woo_chatbot_cart_updating= $_POST["qlcd_woo_chatbot_cart_updating"];
                update_option('qlcd_woo_chatbot_cart_updating', serialize($qlcd_woo_chatbot_cart_updating));
                $qlcd_woo_chatbot_cart_removing= $_POST["qlcd_woo_chatbot_cart_removing"];
                update_option('qlcd_woo_chatbot_cart_removing', serialize($qlcd_woo_chatbot_cart_removing));
                //WoowBot wildcard  settings
                $qlcd_woo_chatbot_wildcard_msg = $_POST["qlcd_woo_chatbot_wildcard_msg"];
                update_option('qlcd_woo_chatbot_wildcard_msg', serialize($qlcd_woo_chatbot_wildcard_msg));
                //empty filter message repeat.
                $qlcd_woo_chatbot_empty_filter_msg = $_POST["qlcd_woo_chatbot_empty_filter_msg"];
                update_option('qlcd_woo_chatbot_empty_filter_msg', serialize($qlcd_woo_chatbot_empty_filter_msg));
               //help welcome and message
                $qlcd_woo_chatbot_help_welcome = $_POST["qlcd_woo_chatbot_help_welcome"];
                update_option('qlcd_woo_chatbot_help_welcome', serialize($qlcd_woo_chatbot_help_welcome));
                $qlcd_woo_chatbot_help_msg = $_POST["qlcd_woo_chatbot_help_msg"];
                update_option('qlcd_woo_chatbot_help_msg', serialize($qlcd_woo_chatbot_help_msg));
                //To clear Conversation history.
                $qlcd_woo_chatbot_reset = $_POST["qlcd_woo_chatbot_reset"];
                update_option('qlcd_woo_chatbot_reset', serialize($qlcd_woo_chatbot_reset));
                //systems keyword.
                $qlcd_woo_chatbot_sys_key_help = $_POST["qlcd_woo_chatbot_sys_key_help"];
                update_option('qlcd_woo_chatbot_sys_key_help', sanitize_text_field($qlcd_woo_chatbot_sys_key_help));
                $qlcd_woo_chatbot_sys_key_product = $_POST["qlcd_woo_chatbot_sys_key_product"];
                update_option('qlcd_woo_chatbot_sys_key_product', sanitize_text_field($qlcd_woo_chatbot_sys_key_product));
                $qlcd_woo_chatbot_sys_key_catalog = $_POST["qlcd_woo_chatbot_sys_key_catalog"];
                update_option('qlcd_woo_chatbot_sys_key_catalog', sanitize_text_field($qlcd_woo_chatbot_sys_key_catalog));
                $qlcd_woo_chatbot_sys_key_order = $_POST["qlcd_woo_chatbot_sys_key_order"];
                update_option('qlcd_woo_chatbot_sys_key_order', sanitize_text_field($qlcd_woo_chatbot_sys_key_order));
                $qlcd_woo_chatbot_sys_key_support = $_POST["qlcd_woo_chatbot_sys_key_support"];
                update_option('qlcd_woo_chatbot_sys_key_support', sanitize_text_field($qlcd_woo_chatbot_sys_key_support));
                $qlcd_woo_chatbot_sys_key_reset = $_POST["qlcd_woo_chatbot_sys_key_reset"];
                update_option('qlcd_woo_chatbot_sys_key_reset', sanitize_text_field($qlcd_woo_chatbot_sys_key_reset));
                $qlcd_woo_chatbot_wildcard_product = $_POST["qlcd_woo_chatbot_wildcard_product"];
                update_option('qlcd_woo_chatbot_wildcard_product', serialize($qlcd_woo_chatbot_wildcard_product));
                $qlcd_woo_chatbot_wildcard_catalog = $_POST["qlcd_woo_chatbot_wildcard_catalog"];
                update_option('qlcd_woo_chatbot_wildcard_catalog', serialize($qlcd_woo_chatbot_wildcard_catalog));
                $qlcd_woo_chatbot_featured_products = $_POST["qlcd_woo_chatbot_featured_products"];
                update_option('qlcd_woo_chatbot_featured_products', serialize($qlcd_woo_chatbot_featured_products));
                $qlcd_woo_chatbot_sale_products = $_POST["qlcd_woo_chatbot_sale_products"];
                update_option('qlcd_woo_chatbot_sale_products', serialize($qlcd_woo_chatbot_sale_products));
                $qlcd_woo_chatbot_wildcard_support = $_POST["qlcd_woo_chatbot_wildcard_support"];
                update_option('qlcd_woo_chatbot_wildcard_support', serialize($qlcd_woo_chatbot_wildcard_support));
                $qlcd_woo_chatbot_messenger_label = $_POST["qlcd_woo_chatbot_messenger_label"];
                update_option('qlcd_woo_chatbot_messenger_label', serialize($qlcd_woo_chatbot_messenger_label));
                //Products search .
                if (isset($_POST["qlcd_woo_chatbot_product_success"])) {
                    $qlcd_woo_chatbot_product_success = $_POST["qlcd_woo_chatbot_product_success"];
                    update_option('qlcd_woo_chatbot_product_success', serialize($qlcd_woo_chatbot_product_success));
                }
                if (isset($_POST["qlcd_woo_chatbot_product_fail"])) {
                    $qlcd_woo_chatbot_product_fail = $_POST["qlcd_woo_chatbot_product_fail"];
                    update_option('qlcd_woo_chatbot_product_fail', serialize($qlcd_woo_chatbot_product_fail));
                }
                $qlcd_woo_chatbot_product_asking = $_POST["qlcd_woo_chatbot_product_asking"];
                update_option('qlcd_woo_chatbot_product_asking', serialize($qlcd_woo_chatbot_product_asking));
                $qlcd_woo_chatbot_product_suggest = $_POST["qlcd_woo_chatbot_product_suggest"];
                update_option('qlcd_woo_chatbot_product_suggest', serialize($qlcd_woo_chatbot_product_suggest));
                $qlcd_woo_chatbot_product_infinite = $_POST["qlcd_woo_chatbot_product_infinite"];
                update_option('qlcd_woo_chatbot_product_infinite', serialize($qlcd_woo_chatbot_product_infinite));
                $qlcd_woo_chatbot_load_more = $_POST["qlcd_woo_chatbot_load_more"];
                update_option('qlcd_woo_chatbot_load_more', serialize($qlcd_woo_chatbot_load_more));
                //Order
                $qlcd_woo_chatbot_wildcard_order = $_POST["qlcd_woo_chatbot_wildcard_order"];
                update_option('qlcd_woo_chatbot_wildcard_order', serialize($qlcd_woo_chatbot_wildcard_order));
                $qlcd_woo_chatbot_order_welcome = $_POST["qlcd_woo_chatbot_order_welcome"];
                update_option('qlcd_woo_chatbot_order_welcome', serialize($qlcd_woo_chatbot_order_welcome));
                $qlcd_woo_chatbot_order_username_asking = $_POST["qlcd_woo_chatbot_order_username_asking"];
                update_option('qlcd_woo_chatbot_order_username_asking', serialize($qlcd_woo_chatbot_order_username_asking));
                $qlcd_woo_chatbot_order_username_not_exist = $_POST["qlcd_woo_chatbot_order_username_not_exist"];
                update_option('qlcd_woo_chatbot_order_username_not_exist', serialize($qlcd_woo_chatbot_order_username_not_exist));
                $qlcd_woo_chatbot_order_username_thanks = $_POST["qlcd_woo_chatbot_order_username_thanks"];
                update_option('qlcd_woo_chatbot_order_username_thanks', serialize($qlcd_woo_chatbot_order_username_thanks));
                $qlcd_woo_chatbot_order_username_password = $_POST["qlcd_woo_chatbot_order_username_password"];
                update_option('qlcd_woo_chatbot_order_username_password', serialize($qlcd_woo_chatbot_order_username_password));
                $qlcd_woo_chatbot_order_password_incorrect= $_POST["qlcd_woo_chatbot_order_password_incorrect"];
                update_option('qlcd_woo_chatbot_order_password_incorrect', serialize($qlcd_woo_chatbot_order_password_incorrect));
                $qlcd_woo_chatbot_order_not_found= $_POST["qlcd_woo_chatbot_order_not_found"];
                update_option('qlcd_woo_chatbot_order_not_found', serialize($qlcd_woo_chatbot_order_not_found));
                $qlcd_woo_chatbot_order_found= $_POST["qlcd_woo_chatbot_order_found"];
                update_option('qlcd_woo_chatbot_order_found', serialize($qlcd_woo_chatbot_order_found));
                $qlcd_woo_chatbot_order_email_support= $_POST["qlcd_woo_chatbot_order_email_support"];
                update_option('qlcd_woo_chatbot_order_email_support', serialize($qlcd_woo_chatbot_order_email_support));
                //Support
                $qlcd_woo_chatbot_support_welcome = $_POST["qlcd_woo_chatbot_support_welcome"];
                update_option('qlcd_woo_chatbot_support_welcome', serialize($qlcd_woo_chatbot_support_welcome));
                $qlcd_woo_chatbot_support_email = $_POST["qlcd_woo_chatbot_support_email"];
                update_option('qlcd_woo_chatbot_support_email', serialize($qlcd_woo_chatbot_support_email));
                $qlcd_woo_chatbot_asking_email = $_POST["qlcd_woo_chatbot_asking_email"];
                update_option('qlcd_woo_chatbot_asking_email', serialize($qlcd_woo_chatbot_asking_email));
                $qlcd_woo_chatbot_asking_msg = $_POST["qlcd_woo_chatbot_asking_msg"];
                update_option('qlcd_woo_chatbot_asking_msg', serialize($qlcd_woo_chatbot_asking_msg));
                $qlcd_woo_chatbot_support_option_again = $_POST["qlcd_woo_chatbot_support_option_again"];
                update_option('qlcd_woo_chatbot_support_option_again', serialize($qlcd_woo_chatbot_support_option_again));
                $qlcd_woo_chatbot_invalid_email = $_POST["qlcd_woo_chatbot_invalid_email"];
                update_option('qlcd_woo_chatbot_invalid_email', serialize($qlcd_woo_chatbot_invalid_email));
                $qlcd_woo_chatbot_support_phone= $_POST["qlcd_woo_chatbot_support_phone"];
                update_option('qlcd_woo_chatbot_support_phone', serialize($qlcd_woo_chatbot_support_phone));
                $qlcd_woo_chatbot_asking_phone= $_POST["qlcd_woo_chatbot_asking_phone"];
                update_option('qlcd_woo_chatbot_asking_phone', serialize($qlcd_woo_chatbot_asking_phone));
                $qlcd_woo_chatbot_thank_for_phone= $_POST["qlcd_woo_chatbot_thank_for_phone"];
                update_option('qlcd_woo_chatbot_thank_for_phone', serialize($qlcd_woo_chatbot_thank_for_phone));
                $qlcd_woo_chatbot_admin_email = $_POST["qlcd_woo_chatbot_admin_email"];
                update_option('qlcd_woo_chatbot_admin_email', sanitize_text_field($qlcd_woo_chatbot_admin_email));
                $qlcd_woo_chatbot_email_sub = $_POST["qlcd_woo_chatbot_email_sub"];
                update_option('qlcd_woo_chatbot_email_sub', sanitize_text_field($qlcd_woo_chatbot_email_sub));
                $qlcd_woo_chatbot_email_sent = $_POST["qlcd_woo_chatbot_email_sent"];
                update_option('qlcd_woo_chatbot_email_sent', sanitize_text_field($qlcd_woo_chatbot_email_sent));
                $qlcd_woo_chatbot_email_fail = $_POST["qlcd_woo_chatbot_email_fail"];
                update_option('qlcd_woo_chatbot_email_fail', sanitize_text_field($qlcd_woo_chatbot_email_fail));
                //Notifications messages building.
                $qlcd_woo_chatbot_notification_interval = $_POST["qlcd_woo_chatbot_notification_interval"];
                update_option('qlcd_woo_chatbot_notification_interval', sanitize_text_field($qlcd_woo_chatbot_notification_interval));
                $qlcd_woo_chatbot_notifications = $_POST["qlcd_woo_chatbot_notifications"];
                update_option('qlcd_woo_chatbot_notifications', serialize($qlcd_woo_chatbot_notifications));
                //Support building part.
                $support_query = $_POST["support_query"];
                update_option('support_query', serialize($support_query));
                $support_ans = $_POST["support_ans"];
                update_option('support_ans', serialize($support_ans));
                //Custom intent
                $custom_intent_labels= $_POST["custom_intent_labels"];
                update_option('custom_intent_labels', serialize($custom_intent_labels));

                $custom_intent_names= $_POST["custom_intent_names"];
                update_option('custom_intent_names', serialize($custom_intent_names));

                $custom_intent_kewords = $_POST["custom_intent_kewords"];
                update_option('custom_intent_kewords', serialize($custom_intent_kewords));
                //Create Mobile app pages.
                if(isset( $_POST["woo_chatbot_app_pages"])) {
                    $woo_chatbot_app_pages = $_POST["woo_chatbot_app_pages"];
                }else{ $woo_chatbot_app_pages='';}
                update_option('woo_chatbot_app_pages', stripslashes($woo_chatbot_app_pages));
                //Step by step search
                if(isset( $_POST["enable_woo_chatbot_custom_search"])) {
                    $enable_woo_chatbot_custom_search = $_POST["enable_woo_chatbot_custom_search"];
                }else{ $enable_woo_chatbot_custom_search='';}
                update_option('enable_woo_chatbot_custom_search', stripslashes($enable_woo_chatbot_custom_search));

                //custom Intends
                if(isset( $_POST["enable_woo_chatbot_custom_intent"])) {
                    $enable_woo_chatbot_custom_intent = $_POST["enable_woo_chatbot_custom_intent"];
                }else{ $enable_woo_chatbot_custom_intent='';}
                update_option('enable_woo_chatbot_custom_intent', stripslashes($enable_woo_chatbot_custom_intent));

                //custom Intends
                if(isset( $_POST["enable_woo_chatbot_rich_response"])) {
                    $enable_woo_chatbot_rich_response = $_POST["enable_woo_chatbot_rich_response"];
                }else{ $enable_woo_chatbot_rich_response='';}
                update_option('enable_woo_chatbot_rich_response', stripslashes($enable_woo_chatbot_rich_response));

                //Messenger Options
                if(isset( $_POST["enable_woo_chatbot_messenger"])) {
                    $enable_woo_chatbot_messenger = $_POST["enable_woo_chatbot_messenger"];
                }else{ $enable_woo_chatbot_messenger='';}
                update_option('enable_woo_chatbot_messenger', stripslashes($enable_woo_chatbot_messenger));
                if(isset( $_POST["enable_woo_chatbot_messenger_floating_icon"])) {
                    $enable_woo_chatbot_messenger_floating_icon = $_POST["enable_woo_chatbot_messenger_floating_icon"];
                }else{ $enable_woo_chatbot_messenger_floating_icon='';}
                update_option('enable_woo_chatbot_messenger_floating_icon', stripslashes($enable_woo_chatbot_messenger_floating_icon));
                $qlcd_woo_chatbot_fb_app_id = $_POST["qlcd_woo_chatbot_fb_app_id"];
                update_option('qlcd_woo_chatbot_fb_app_id', sanitize_text_field($qlcd_woo_chatbot_fb_app_id));
                $qlcd_woo_chatbot_fb_page_id = $_POST["qlcd_woo_chatbot_fb_page_id"];
                update_option('qlcd_woo_chatbot_fb_page_id', sanitize_text_field($qlcd_woo_chatbot_fb_page_id));
                $qlcd_woo_chatbot_fb_color= $_POST["qlcd_woo_chatbot_fb_color"];
                update_option('qlcd_woo_chatbot_fb_color', stripslashes($qlcd_woo_chatbot_fb_color));
                $qlcd_woo_chatbot_fb_in_msg = $_POST["qlcd_woo_chatbot_fb_in_msg"];
                update_option('qlcd_woo_chatbot_fb_in_msg', sanitize_text_field($qlcd_woo_chatbot_fb_in_msg));
                $qlcd_woo_chatbot_fb_out_msg = $_POST["qlcd_woo_chatbot_fb_out_msg"];
                update_option('qlcd_woo_chatbot_fb_out_msg', sanitize_text_field($qlcd_woo_chatbot_fb_out_msg));
                //Skype option
                if(isset( $_POST["enable_woo_chatbot_skype_floating_icon"])) {
                $enable_woo_chatbot_skype_floating_icon = $_POST["enable_woo_chatbot_skype_floating_icon"];
                }else{ $enable_woo_chatbot_skype_floating_icon='';}
                update_option('enable_woo_chatbot_skype_floating_icon', sanitize_text_field($enable_woo_chatbot_skype_floating_icon));
                if(isset( $_POST["enable_woo_chatbot_skype_id"])) {
                    $enable_woo_chatbot_skype_id = $_POST["enable_woo_chatbot_skype_id"];
                }else{ $enable_woo_chatbot_skype_id='';}
                update_option('enable_woo_chatbot_skype_id', sanitize_text_field($enable_woo_chatbot_skype_id));
                //WhatsApp
                if(isset( $_POST["enable_woo_chatbot_whats"])) {
                    $enable_woo_chatbot_whats= $_POST["enable_woo_chatbot_whats"];
                }else{ $enable_woo_chatbot_whats='';}
                update_option('enable_woo_chatbot_whats', sanitize_text_field($enable_woo_chatbot_whats));
                $qlcd_woo_chatbot_whats_label = $_POST["qlcd_woo_chatbot_whats_label"];
                update_option('qlcd_woo_chatbot_whats_label', serialize($qlcd_woo_chatbot_whats_label));
                if(isset( $_POST["enable_woo_chatbot_floating_whats"])) {
                    $enable_woo_chatbot_floating_whats= $_POST["enable_woo_chatbot_floating_whats"];
                }else{ $enable_woo_chatbot_floating_whats='';}
                update_option('enable_woo_chatbot_floating_whats', sanitize_text_field($enable_woo_chatbot_floating_whats));
                $qlcd_woo_chatbot_whats_num = $_POST["qlcd_woo_chatbot_whats_num"];
                update_option('qlcd_woo_chatbot_whats_num', sanitize_text_field($qlcd_woo_chatbot_whats_num));
               //Viber
                if(isset( $_POST["enable_woo_chatbot_floating_viber"])) {
                    $enable_woo_chatbot_floating_viber = $_POST["enable_woo_chatbot_floating_viber"];
                }else{ $enable_woo_chatbot_floating_viber='';}
                update_option('enable_woo_chatbot_floating_viber', sanitize_text_field($enable_woo_chatbot_floating_viber));
                $qlcd_woo_chatbot_viber_acc = $_POST["qlcd_woo_chatbot_viber_acc"];
                update_option('qlcd_woo_chatbot_viber_acc', sanitize_text_field($qlcd_woo_chatbot_viber_acc));
                //Others integration
                if(isset( $_POST["enable_woo_chatbot_floating_phone"])) {
                    $enable_woo_chatbot_floating_phone = $_POST["enable_woo_chatbot_floating_phone"];
                }else{ $enable_woo_chatbot_floating_phone='';}
                update_option('enable_woo_chatbot_floating_phone', sanitize_text_field($enable_woo_chatbot_floating_phone));
                $qlcd_woo_chatbot_phone = $_POST["qlcd_woo_chatbot_phone"];
                update_option('qlcd_woo_chatbot_phone', sanitize_text_field($qlcd_woo_chatbot_phone));

                if(isset( $_POST["enable_woo_chatbot_floating_link"])) {
                    $enable_woo_chatbot_floating_link = $_POST["enable_woo_chatbot_floating_link"];
                }else{ $enable_woo_chatbot_floating_link='';}
                update_option('enable_woo_chatbot_floating_link', sanitize_text_field($enable_woo_chatbot_floating_link));
                $qlcd_woo_chatbot_weblink = $_POST["qlcd_woo_chatbot_weblink"];
                update_option('qlcd_woo_chatbot_weblink', sanitize_text_field($qlcd_woo_chatbot_weblink));

                if(isset( $_POST["enable_woo_chatbot_floating_livechat"])) {
                    $enable_woo_chatbot_floating_livechat = $_POST["enable_woo_chatbot_floating_livechat"];
                }else{ $enable_woo_chatbot_floating_livechat='';}
                update_option('enable_woo_chatbot_floating_livechat', sanitize_text_field($enable_woo_chatbot_floating_livechat));

                if(isset( $_POST["enable_woo_custom_intent_livechat_button"])) {
                    $enable_woo_custom_intent_livechat_button = $_POST["enable_woo_custom_intent_livechat_button"];
                }else{ $enable_woo_custom_intent_livechat_button='';}
                update_option('enable_woo_custom_intent_livechat_button', sanitize_text_field($enable_woo_custom_intent_livechat_button));

                $qlcd_woo_chatbot_livechatlink = $_POST["qlcd_woo_chatbot_livechatlink"];
                update_option('qlcd_woo_chatbot_livechatlink', sanitize_text_field($qlcd_woo_chatbot_livechatlink));

                $qlcd_woo_livechat_button_label = $_POST["qlcd_woo_livechat_button_label"];
                update_option('qlcd_woo_livechat_button_label', sanitize_text_field($qlcd_woo_livechat_button_label));

                $woo_custom_icon_livechat = $_POST["woo_custom_icon_livechat"];
                update_option('woo_custom_icon_livechat', sanitize_text_field($woo_custom_icon_livechat));
                //Re Targetting.
                $qlcd_woo_chatbot_ret_greet = $_POST["qlcd_woo_chatbot_ret_greet"];
                update_option('qlcd_woo_chatbot_ret_greet', sanitize_text_field($qlcd_woo_chatbot_ret_greet));

                if(isset( $_POST["enable_woo_chatbot_exit_intent"])) {
                    $enable_woo_chatbot_exit_intent = $_POST["enable_woo_chatbot_exit_intent"];
                }else{ $enable_woo_chatbot_exit_intent='';}
                update_option('enable_woo_chatbot_exit_intent', sanitize_text_field($enable_woo_chatbot_exit_intent));

                $woo_chatbot_exit_intent_msg = $_POST["woo_chatbot_exit_intent_msg"];
                update_option('woo_chatbot_exit_intent_msg', stripslashes($woo_chatbot_exit_intent_msg));

                if(isset( $_POST["woo_chatbot_exit_intent_once"])) {
                    $woo_chatbot_exit_intent_once = $_POST["woo_chatbot_exit_intent_once"];
                }else{ $woo_chatbot_exit_intent_once='';}
                update_option('woo_chatbot_exit_intent_once', sanitize_text_field($woo_chatbot_exit_intent_once));

                if(isset( $_POST["enable_woo_chatbot_scroll_open"])) {
                    $enable_woo_chatbot_scroll_open = $_POST["enable_woo_chatbot_scroll_open"];
                }else{ $enable_woo_chatbot_scroll_open='';}
                update_option('enable_woo_chatbot_scroll_open', sanitize_text_field($enable_woo_chatbot_scroll_open));

                $woo_chatbot_scroll_open_msg= $_POST["woo_chatbot_scroll_open_msg"];
                update_option('woo_chatbot_scroll_open_msg', stripslashes($woo_chatbot_scroll_open_msg));

                $woo_chatbot_scroll_percent= $_POST["woo_chatbot_scroll_percent"];
                update_option('woo_chatbot_scroll_percent', stripslashes($woo_chatbot_scroll_percent));

                if(isset( $_POST["woo_chatbot_scroll_once"])) {
                    $woo_chatbot_scroll_once = $_POST["woo_chatbot_scroll_once"];
                }else{ $woo_chatbot_scroll_once='';}
                update_option('woo_chatbot_scroll_once', sanitize_text_field($woo_chatbot_scroll_once));

                if(isset( $_POST["enable_woo_chatbot_auto_open"])) {
                    $enable_woo_chatbot_auto_open = $_POST["enable_woo_chatbot_auto_open"];
                }else{ $enable_woo_chatbot_auto_open='';}
                update_option('enable_woo_chatbot_auto_open', sanitize_text_field($enable_woo_chatbot_auto_open));

                if(isset( $_POST["enable_woo_chatbot_ret_sound"])) {
                    $enable_woo_chatbot_ret_sound = $_POST["enable_woo_chatbot_ret_sound"];
                }else{ $enable_woo_chatbot_ret_sound='';}
                update_option('enable_woo_chatbot_ret_sound', sanitize_text_field($enable_woo_chatbot_ret_sound));

                if(isset( $_POST["enable_woo_chatbot_sound_initial"])) {
                    $enable_woo_chatbot_sound_initial = $_POST["enable_woo_chatbot_sound_initial"];
                }else{ $enable_woo_chatbot_sound_initial='';}
                update_option('enable_woo_chatbot_sound_initial', sanitize_text_field($enable_woo_chatbot_sound_initial));


                $woo_chatbot_auto_open_msg = $_POST["woo_chatbot_auto_open_msg"];
                update_option('woo_chatbot_auto_open_msg', stripslashes($woo_chatbot_auto_open_msg));

                $woo_chatbot_auto_open_time = $_POST["woo_chatbot_auto_open_time"];
                update_option('woo_chatbot_auto_open_time', stripslashes($woo_chatbot_auto_open_time));
                //to complate checkout
                if(isset( $_POST["enable_woo_chatbot_ret_user_show"])) {
                    $enable_woo_chatbot_ret_user_show = $_POST["enable_woo_chatbot_ret_user_show"];
                }else{ $enable_woo_chatbot_ret_user_show='';}
                update_option('enable_woo_chatbot_ret_user_show', sanitize_text_field($enable_woo_chatbot_ret_user_show));

                if(isset( $_POST["enable_woo_chatbot_inactive_time_show"])) {
                    $enable_woo_chatbot_inactive_time_show = $_POST["enable_woo_chatbot_inactive_time_show"];
                }else{ $enable_woo_chatbot_inactive_time_show='';}
                update_option('enable_woo_chatbot_inactive_time_show', sanitize_text_field($enable_woo_chatbot_inactive_time_show));

                $woo_chatbot_inactive_time = $_POST["woo_chatbot_inactive_time"];
                update_option('woo_chatbot_inactive_time', sanitize_text_field($woo_chatbot_inactive_time));

                $woo_chatbot_checkout_msg = $_POST["woo_chatbot_checkout_msg"];
                update_option('woo_chatbot_checkout_msg', stripslashes($woo_chatbot_checkout_msg));

                if(isset( $_POST["woo_chatbot_auto_open_once"])) {
                    $woo_chatbot_auto_open_once = $_POST["woo_chatbot_auto_open_once"];
                }else{ $woo_chatbot_auto_open_once='';}
                update_option('woo_chatbot_auto_open_once', sanitize_text_field($woo_chatbot_auto_open_once));

                if(isset( $_POST["woo_chatbot_inactive_once"])) {
                    $woo_chatbot_inactive_once = $_POST["woo_chatbot_inactive_once"];
                }else{ $woo_chatbot_inactive_once='';}
                update_option('woo_chatbot_inactive_once', sanitize_text_field($woo_chatbot_inactive_once));


                $woo_chatbot_proactive_bg_color = $_POST["woo_chatbot_proactive_bg_color"];
                update_option('woo_chatbot_proactive_bg_color', sanitize_text_field($woo_chatbot_proactive_bg_color));

                $woo_chatbot_proactive_text_color = $_POST["woo_chatbot_proactive_text_color"];
                update_option('woo_chatbot_proactive_text_color', sanitize_text_field($woo_chatbot_proactive_text_color));

                if(isset( $_POST["enable_woo_chatbot_custom_color"])) {
                    $enable_woo_chatbot_custom_color = $_POST["enable_woo_chatbot_custom_color"];
                }else{ $enable_woo_chatbot_custom_color='';}
                update_option('enable_woo_chatbot_custom_color', sanitize_text_field($enable_woo_chatbot_custom_color));

               $woo_chatbot_text_color = $_POST["woo_chatbot_text_color"];
                update_option('woo_chatbot_text_color', sanitize_text_field($woo_chatbot_text_color));

                $woo_chatbot_link_color = $_POST["woo_chatbot_link_color"];
                update_option('woo_chatbot_link_color', sanitize_text_field($woo_chatbot_link_color));

                $woo_chatbot_link_hover_color = $_POST["woo_chatbot_link_hover_color"];
                update_option('woo_chatbot_link_hover_color', sanitize_text_field($woo_chatbot_link_hover_color));

                $woo_chatbot_bot_msg_bg_color = $_POST["woo_chatbot_bot_msg_bg_color"];
                update_option('woo_chatbot_bot_msg_bg_color', sanitize_text_field($woo_chatbot_bot_msg_bg_color));

                $woo_chatbot_bot_msg_text_color = $_POST["woo_chatbot_bot_msg_text_color"];
                update_option('woo_chatbot_bot_msg_text_color', sanitize_text_field($woo_chatbot_bot_msg_text_color));

                $woo_chatbot_user_msg_bg_color = $_POST["woo_chatbot_user_msg_bg_color"];
                update_option('woo_chatbot_user_msg_bg_color', sanitize_text_field($woo_chatbot_user_msg_bg_color));

                $woo_chatbot_user_msg_text_color = $_POST["woo_chatbot_user_msg_text_color"];
                update_option('woo_chatbot_user_msg_text_color', sanitize_text_field($woo_chatbot_user_msg_text_color));


				$woo_chatbot_buttons_bg_color = $_POST["woo_chatbot_buttons_bg_color"];
                update_option('woo_chatbot_buttons_bg_color', sanitize_text_field($woo_chatbot_buttons_bg_color));

                $woo_chatbot_buttons_text_color = $_POST["woo_chatbot_buttons_text_color"];
                update_option('woo_chatbot_buttons_text_color', sanitize_text_field($woo_chatbot_buttons_text_color));


                if(isset( $_POST["disable_woo_chatbot_call_gen"])) {
                    $disable_woo_chatbot_call_gen = $_POST["disable_woo_chatbot_call_gen"];
                }else{ $disable_woo_chatbot_call_gen='';}
                update_option('disable_woo_chatbot_call_gen', sanitize_text_field($disable_woo_chatbot_call_gen));

                if(isset( $_POST["disable_woo_chatbot_call_sup"])) {
                    $disable_woo_chatbot_call_sup = $_POST["disable_woo_chatbot_call_sup"];
                }else{ $disable_woo_chatbot_call_sup='';}
                update_option('disable_woo_chatbot_call_sup', sanitize_text_field($disable_woo_chatbot_call_sup));

                if(isset( $_POST["disable_woo_chatbot_feedback"])) {
                    $disable_woo_chatbot_feedback = $_POST["disable_woo_chatbot_feedback"];
                }else{ $disable_woo_chatbot_feedback='';}
                update_option('disable_woo_chatbot_feedback', sanitize_text_field($disable_woo_chatbot_feedback));

                $qlcd_woo_chatbot_feedback_label = $_POST["qlcd_woo_chatbot_feedback_label"];
                update_option('qlcd_woo_chatbot_feedback_label', serialize($qlcd_woo_chatbot_feedback_label));

                if(isset( $_POST["enable_woo_chatbot_meta_title"])) {
                    $enable_woo_chatbot_meta_title = $_POST["enable_woo_chatbot_meta_title"];
                }else{ $enable_woo_chatbot_meta_title='';}
                update_option('enable_woo_chatbot_meta_title', sanitize_text_field($enable_woo_chatbot_meta_title));

                $qlcd_woo_chatbot_meta_label = $_POST["qlcd_woo_chatbot_meta_label"];
                update_option('qlcd_woo_chatbot_meta_label', sanitize_text_field($qlcd_woo_chatbot_meta_label));

                $qlcd_woo_chatbot_phone_sent = $_POST["qlcd_woo_chatbot_phone_sent"];
                update_option('qlcd_woo_chatbot_phone_sent', sanitize_text_field($qlcd_woo_chatbot_phone_sent));

                $qlcd_woo_chatbot_phone_fail = $_POST["qlcd_woo_chatbot_phone_fail"];
                update_option('qlcd_woo_chatbot_phone_fail', sanitize_text_field($qlcd_woo_chatbot_phone_fail));

                if(isset( $_POST["enable_woo_chatbot_opening_hour"])) {
                    $enable_woo_chatbot_opening_hour = $_POST["enable_woo_chatbot_opening_hour"];
                }else{ $enable_woo_chatbot_opening_hour='';}
                update_option('enable_woo_chatbot_opening_hour', sanitize_text_field($enable_woo_chatbot_opening_hour));

                $woowbot_hours= $_POST["woowbot_hours"];
                update_option('woowbot_hours', serialize($woowbot_hours));

                if(isset( $_POST["enable_woo_chatbot_dailogflow"])) {
                    $enable_woo_chatbot_dailogflow = $_POST["enable_woo_chatbot_dailogflow"];
                }else{ $enable_woo_chatbot_dailogflow='';}
                update_option('enable_woo_chatbot_dailogflow', sanitize_text_field($enable_woo_chatbot_dailogflow));

                $qlcd_woo_chatbot_dialogflow_client_token= $_POST["qlcd_woo_chatbot_dialogflow_client_token"];
                update_option('qlcd_woo_chatbot_dialogflow_client_token', sanitize_text_field($qlcd_woo_chatbot_dialogflow_client_token));

                $qlcd_woo_chatbot_dialogflow_defualt_reply= $_POST["qlcd_woo_chatbot_dialogflow_defualt_reply"];
                update_option('qlcd_woo_chatbot_dialogflow_defualt_reply', sanitize_text_field($qlcd_woo_chatbot_dialogflow_defualt_reply));

            }
        }
    }
    /**
     * Display Notifications on specific criteria.
     *
     * @since    2.14
     */
    public static function woocommerce_inactive_notice_for_woo_chatbot()
    {
        if (current_user_can('activate_plugins')) :
            if (!class_exists('WooCommerce')) :
                deactivate_plugins(plugin_basename(__FILE__));
                ?>
                <div id="message" class="error">
                    <p>
                        <?php
                        printf(
                            __('%s WoowBot for WooCommerce REQUIRES WooCommerce%s %sWooCommerce%s must be active for WoowBot to work. Please install & activate WooCommerce.', 'woochatbot'),
                            '<strong>',
                            '</strong><br>',
                            '<a href="http://wordpress.org/extend/plugins/woocommerce/" target="_blank" >',
                            '</a>'
                        );
                        ?>
                    </p>
                </div>
                <?php
            elseif (version_compare(get_option('woocommerce_db_version'), QCLD_WOOCHATBOT_REQUIRED_WOOCOMMERCE_VERSION, '<')) :
                ?>
                <div id="message" class="error">
                    <p>
                        <?php
                        printf(
                            __('%WoowBot for WooCommerce is inactive%s This version of WoowBot requires WooCommerce %s or newer. For more information about our WooCommerce version support %sclick here%s.', 'woochatbot'),
                            '<strong>',
                            '</strong><br>',
                            QCLD_WOOCHATBOT_REQUIRED_WOOCOMMERCE_VERSION
                        );
                        ?>
                    </p>
                    <div style="clear:both;"></div>
                </div>
                <?php
            endif;
        endif;
    }
    /**
     * Admin notice for table reindex
     */
    public function admin_notice_reindex() { ?>
        <div class="updated notice is-dismissible">
            <p><?php printf( esc_html__( 'WoowBot Pro : To Enable Title, Content, Excerpt, Categories, Tag and SKU Search Re-Index Products is required. %s', 'woochatbot' ),'<a class="button button-secondary" href="'.esc_url( admin_url( 'admin.php?page=woowbot') ).'">'.esc_html__( 'Re-Index Products', 'woo_chatbot' ).'</a>'); ?></p>
        </div>
    <?php }
}
/**
 * Instantiate plugin.
 *
 */
if (!function_exists('qcld_woo_chatboot_plugin_init')) {
    function qcld_woo_chatboot_plugin_init()
    {
        global $qcld_woo_chatbot;
        $qcld_woo_chatbot = QCLD_Woo_Chatbot::qcld_woo_chatbot_get_instance();
    }
}
add_action('plugins_loaded', 'qcld_woo_chatboot_plugin_init');
/*
 * Initial Options will be insert as defualt data
 */
register_activation_hook(__FILE__, 'qcld_woo_chatboot_defualt_options');
function qcld_woo_chatboot_defualt_options(){
    $url = get_site_url();
    $url = parse_url($url);
    $domain = $url['host'];
    //$admin_email = "admin@" . $domain;
    $admin_email = get_option('admin_email');

    if(!get_option('woo_chatbot_position_x')) {
        update_option('woo_chatbot_position_x', 50);
    }
    if(!get_option('woo_chatbot_position_y')) {
        update_option('woo_chatbot_position_y', 50);
    }
    if(!get_option('woo_chatbot_position_x_mobile')) {
        update_option('woo_chatbot_position_x_mobile', 30);
    }
    if(!get_option('woo_chatbot_position_y_mobile')) {
        update_option('woo_chatbot_position_y_mobile', 30);
    }

    if(!get_option('disable_woo_chatbot')) {
        update_option('disable_woo_chatbot', '');
    }
    if(!get_option('disable_woo_chatbot_icon_animation')) {
        update_option('disable_woo_chatbot_icon_animation', '');
    }
    if(!get_option('disable_woo_chatbot_on_mobile')) {
        update_option('disable_woo_chatbot_on_mobile', '');
    }
	
	if(!get_option('skip_woo_greetings')) {
        update_option('skip_woo_greetings', '');
    }
	
	if(!get_option('limit_msg_show')) {
        update_option('limit_msg_show', '');
    }
	
    if(!get_option('disable_woo_chatbot_product_search')) {
        update_option('disable_woo_chatbot_product_search', '');
    }
    if(!get_option('disable_woo_chatbot_catalog')) {
        update_option('disable_woo_chatbot_catalog', '');
    }
    if(!get_option('disable_woo_chatbot_order_status')) {
        update_option('disable_woo_chatbot_order_status', '');
    }
    if(!get_option('disable_woo_chatbot_support')) {
        update_option('disable_woo_chatbot_support', '');
    }
    if(!get_option('enable_woo_chatbot_rtl')) {
        update_option('enable_woo_chatbot_rtl', '');
    }
    if(!get_option('enable_woo_chatbot_mobile_full_screen')) {
        update_option('enable_woo_chatbot_mobile_full_screen', '');
    }

     if(!get_option('disable_woo_chatbot_notification')) {
        update_option('disable_woo_chatbot_notification', '');
    }
    if(!get_option('disable_woo_chatbot_notification_mobile')) {
        update_option('disable_woo_chatbot_notification_mobile', '');
    }

    if(!get_option('disable_woo_chatbot_cart_item_number')) {
        update_option('disable_woo_chatbot_cart_item_number', '');
    }
    if(!get_option('disable_woo_chatbot_featured_product')) {
        update_option('disable_woo_chatbot_featured_product', '');
    }
    if(!get_option('disable_woo_chatbot_sale_product')) {
        update_option('disable_woo_chatbot_sale_product', '');
    }
     if(!get_option('woo_chatbot_open_product_detail')) {
        update_option('woo_chatbot_open_product_detail', '');
    }
    if(!get_option('qlcd_woo_chatbot_product_orderby')) {
        update_option('qlcd_woo_chatbot_product_orderby', sanitize_text_field('title'));
    }
    if(!get_option('qlcd_woo_chatbot_product_order')) {
        update_option('qlcd_woo_chatbot_product_order', sanitize_text_field('ASC'));
    }
    if(!get_option('qlcd_woo_chatbot_ppp')) {
        update_option('qlcd_woo_chatbot_ppp', intval(6));
    }
    if(!get_option('woo_chatbot_exclude_stock_out_product')) {
        update_option('woo_chatbot_exclude_stock_out_product', '');
    }
    if(!get_option('woo_chatbot_hide_product_details_add_to_cart')) {
        update_option('woo_chatbot_hide_product_details_add_to_cart', '');
    }
    if(!get_option('woo_chatbot_show_sub_category')) {
        update_option('woo_chatbot_show_sub_category', '');
    }
    if(!get_option('woo_chatbot_vertical_custom')){
        update_option('woo_chatbot_vertical_custom', 'Go To');
    }
    if(!get_option('woo_chatbot_show_home_page')) {
        update_option('woo_chatbot_show_home_page', 'on');
    }
    if(!get_option('woo_chatbot_show_posts')) {
        update_option('woo_chatbot_show_posts', 'on');
    }
    if(!get_option('woo_chatbot_show_pages')){
        update_option('woo_chatbot_show_pages', 'on');
    }
    if(!get_option('woo_chatbot_show_pages_list')) {
        update_option('woo_chatbot_show_pages_list', serialize(array()));
    }
    if(!get_option('woo_chatbot_show_woocommerce')) {
        update_option('woo_chatbot_show_woocommerce', 'on');
    }
    if(!get_option('qlcd_woo_chatbot_stop_words_name')) {
        update_option('qlcd_woo_chatbot_stop_words_name', 'english');
    }
    if(!get_option('qlcd_woo_chatbot_stop_words')) {
        update_option('qlcd_woo_chatbot_stop_words', "a,able,about,above,abst,accordance,according,accordingly,across,act,actually,added,adj,affected,affecting,affects,after,afterwards,again,against,ah,all,almost,alone,along,already,also,although,always,am,among,amongst,an,and,announce,another,any,anybody,anyhow,anymore,anyone,anything,anyway,anyways,anywhere,apparently,approximately,are,aren,arent,arise,around,as,aside,ask,asking,at,auth,available,away,awfully,b,back,be,became,because,become,becomes,becoming,been,before,beforehand,begin,beginning,beginnings,begins,behind,being,believe,below,beside,besides,between,beyond,biol,both,brief,briefly,but,by,c,ca,came,can,cannot,can't,cause,causes,certain,certainly,co,com,come,comes,contain,containing,contains,could,couldnt,d,date,did,didn't,different,do,does,doesn't,doing,done,don't,down,downwards,due,during,e,each,ed,edu,effect,eg,eight,eighty,either,else,elsewhere,end,ending,enough,especially,et,et-al,etc,even,ever,every,everybody,everyone,everything,everywhere,ex,except,f,far,few,ff,fifth,first,five,fix,followed,following,follows,for,former,formerly,forth,found,four,from,further,furthermore,g,gave,get,gets,getting,give,given,gives,giving,go,goes,gone,got,gotten,h,had,happens,hardly,has,hasn't,have,haven't,having,he,hed,hence,her,here,hereafter,hereby,herein,heres,hereupon,hers,herself,hes,hi,hid,him,himself,his,hither,home,how,howbeit,however,hundred,i,id,ie,if,i'll,im,immediate,immediately,importance,important,in,inc,indeed,index,information,instead,into,invention,inward,is,isn't,it,itd,it'll,its,itself,i've,j,just,k,keep,keeps,kept,kg,km,know,known,knows,l,largely,last,lately,later,latter,latterly,least,less,lest,let,lets,like,liked,likely,line,little,'ll,look,looking,looks,ltd,m,made,mainly,make,makes,many,may,maybe,me,mean,means,meantime,meanwhile,merely,mg,might,million,miss,ml,more,moreover,most,mostly,mr,mrs,much,mug,must,my,myself,n,na,name,namely,nay,nd,near,nearly,necessarily,necessary,need,needs,neither,never,nevertheless,new,next,nine,ninety,no,nobody,non,none,nonetheless,noone,nor,normally,nos,not,noted,nothing,now,nowhere,o,obtain,obtained,obviously,of,off,often,oh,ok,okay,old,omitted,on,once,one,ones,only,onto,or,ord,other,others,otherwise,ought,our,ours,ourselves,out,outside,over,overall,owing,own,p,page,pages,part,particular,particularly,past,per,perhaps,placed,please,plus,poorly,possible,possibly,potentially,pp,predominantly,present,previously,primarily,probably,promptly,proud,provides,put,q,que,quickly,quite,qv,r,ran,rather,rd,re,readily,really,recent,recently,ref,refs,regarding,regardless,regards,related,relatively,research,respectively,resulted,resulting,results,right,run,s,said,same,saw,say,saying,says,sec,section,see,seeing,seem,seemed,seeming,seems,seen,self,selves,sent,seven,several,shall,she,shed,she'll,shes,should,shouldn't,show,showed,shown,showns,shows,significant,significantly,similar,similarly,since,six,slightly,so,some,somebody,somehow,someone,somethan,something,sometime,sometimes,somewhat,somewhere,soon,sorry,specifically,specified,specify,specifying,still,stop,strongly,sub,substantially,successfully,such,sufficiently,suggest,sup,sure,t,take,taken,taking,tell,tends,th,than,thank,thanks,thanx,that,that'll,thats,that've,the,their,theirs,them,themselves,then,thence,there,thereafter,thereby,thered,therefore,therein,there'll,thereof,therere,theres,thereto,thereupon,there've,these,they,theyd,they'll,theyre,they've,think,this,those,thou,though,thoughh,thousand,throug,through,throughout,thru,thus,til,tip,to,together,too,took,toward,towards,tried,tries,truly,try,trying,ts,twice,two,u,un,under,unfortunately,unless,unlike,unlikely,until,unto,up,upon,ups,us,use,used,useful,usefully,usefulness,uses,using,usually,v,value,various,'ve,very,via,viz,vol,vols,vs,w,want,wants,was,wasnt,way,we,wed,welcome,we'll,went,were,werent,we've,what,whatever,what'll,whats,when,whence,whenever,where,whereafter,whereas,whereby,wherein,wheres,whereupon,wherever,whether,which,while,whim,whither,who,whod,whoever,whole,who'll,whom,whomever,whos,whose,why,widely,willing,wish,with,within,without,wont,words,world,would,wouldnt,www,x,y,yes,yet,you,youd,you'll,your,youre,yours,yourself,yourselves,you've,z,zero");
    }
    if(!get_option('qlcd_woo_chatbot_order_user')) {
        update_option('qlcd_woo_chatbot_order_user', sanitize_text_field('login'));
    }
    if(!get_option('woo_chatbot_custom_agent_path')) {
        update_option('woo_chatbot_custom_agent_path', '');
    }
    if(!get_option('woo_chatbot_custom_icon_path')) {
        update_option('woo_chatbot_custom_icon_path', '');
    }

    if(!get_option('woo_chatbot_icon')) {
        update_option('woo_chatbot_icon', sanitize_text_field('icon-0.png'));
    }
    if(!get_option('woo_chatbot_agent_image')){
        update_option('woo_chatbot_agent_image',sanitize_text_field('agent-0.png'));
    }
    if(!get_option('qcld_woo_chatbot_theme')) {
        update_option('qcld_woo_chatbot_theme', sanitize_text_field('template-01'));
    }
    
	if(!get_option('qcld_woo_chatbot_custom_icons')) {
        update_option('qcld_woo_chatbot_custom_icons', '');
    }

	if(!get_option('qcld_woo_chatbot_custom_icon_help')) {
        update_option('qcld_woo_chatbot_custom_icon_help', '');
    }

	if(!get_option('qcld_woo_chatbot_custom_icon_support')) {
        update_option('qcld_woo_chatbot_custom_icon_support', '');
    }


	if(!get_option('qcld_woo_chatbot_custom_icon_recent')) {
        update_option('qcld_woo_chatbot_custom_icon_recent', '');
    }


	if(!get_option('qcld_woo_chatbot_custom_icon_cart')) {
        update_option('qcld_woo_chatbot_custom_icon_cart', '');
    }


	if(!get_option('qcld_woo_chatbot_custom_icon_chat')) {
        update_option('qcld_woo_chatbot_custom_icon_chat', '');
    }

	if(!get_option('qcld_woo_chatbot_change_bg')) {
        update_option('qcld_woo_chatbot_change_bg', '');
    }
    if(!get_option('woo_chatbot_custom_css')) {
        update_option('woo_chatbot_custom_css', '');
    }
    if(!get_option('qlcd_woo_chatbot_host')) {
        update_option('qlcd_woo_chatbot_host', sanitize_text_field('Our Store'));
    }
    if(!get_option('qlcd_woo_chatbot_agent')) {
        update_option('qlcd_woo_chatbot_agent', sanitize_text_field('Carrie'));
    }
    if(!get_option('qlcd_woo_chatbot_host')) {
        update_option('qlcd_woo_chatbot_host', sanitize_text_field('Our Store'));
    }
    if(!get_option('qlcd_woo_chatbot_shopper_demo_name')) {
        update_option('qlcd_woo_chatbot_shopper_demo_name', sanitize_text_field('Amigo'));
    }
    if(!get_option('qlcd_woo_chatbot_yes')) {
        update_option('qlcd_woo_chatbot_yes', sanitize_text_field('YES'));
    }
    if(!get_option('qlcd_woo_chatbot_no')) {
        update_option('qlcd_woo_chatbot_no', sanitize_text_field('NO'));
    }
    if(!get_option('qlcd_woo_chatbot_or')) {
        update_option('qlcd_woo_chatbot_or', sanitize_text_field('OR'));
    }
    if(!get_option('qlcd_woo_chatbot_hello')) {
        update_option('qlcd_woo_chatbot_hello', sanitize_text_field('Hello'));
    }
    if(!get_option('qlcd_woo_chatbot_sorry')) {
        update_option('qlcd_woo_chatbot_sorry', sanitize_text_field('Sorry'));
    }
	
	if(!get_option('qlcd_woo_chatbot_chat')) {
        update_option('qlcd_woo_chatbot_chat', sanitize_text_field('Chat'));
    }
	
    if(!get_option('qlcd_woo_chatbot_agent_join')) {
        update_option('qlcd_woo_chatbot_agent_join', serialize(array('has joined the conversation')));
    }
    if(!get_option('qlcd_woo_chatbot_welcome')) {
        update_option('qlcd_woo_chatbot_welcome', serialize(array('Welcome to', 'Glad to have you at')));
    }
    if(!get_option('qlcd_woo_chatbot_back_to_start')) {
        update_option('qlcd_woo_chatbot_back_to_start', serialize(array('Back to Start')));
    }
    if(!get_option('qlcd_woo_chatbot_hi_there')) {
        update_option('qlcd_woo_chatbot_hi_there', serialize(array('Hi There!')));
    }
    if(!get_option('qlcd_woo_chatbot_welcome_back')) {
        update_option('qlcd_woo_chatbot_welcome_back', serialize(array('Welcome back', 'Good to see your again')));
    }
    if(!get_option('qlcd_woo_chatbot_asking_name')) {
        update_option('qlcd_woo_chatbot_asking_name', serialize(array('May I know your name?', 'What should I call you?')));
    }
    if(!get_option('qlcd_woo_chatbot_name_greeting')) {
        update_option('qlcd_woo_chatbot_name_greeting', serialize(array('Nice to meet you')));
    }
    if(!get_option('qlcd_woo_chatbot_i_am')) {
        update_option('qlcd_woo_chatbot_i_am', serialize(array('I am', 'This is')));
    }
    if(!get_option('qlcd_woo_chatbot_is_typing')) {
        update_option('qlcd_woo_chatbot_is_typing', serialize(array('is typing...')));
    }
    if(!get_option('qlcd_woo_chatbot_send_a_msg')) {
        update_option('qlcd_woo_chatbot_send_a_msg', serialize(array('Send a message.')));
    }
    if(!get_option('qlcd_woo_chatbot_choose_option')) {
        update_option('qlcd_woo_chatbot_choose_option', serialize(array('Choose an option.')));
    }
    if(!get_option('qlcd_woo_chatbot_viewed_products')) {
        update_option('qlcd_woo_chatbot_viewed_products', serialize(array('Recently viewed products')));
    }
    if(!get_option('qlcd_woo_chatbot_add_to_cart')) {
        update_option('qlcd_woo_chatbot_add_to_cart', serialize(array('Add to Cart')));
    }
    if(!get_option('qlcd_woo_chatbot_cart_link')) {
        update_option('qlcd_woo_chatbot_cart_link', serialize(array('Cart')));
    }
    if(!get_option('qlcd_woo_chatbot_checkout_link')) {
        update_option('qlcd_woo_chatbot_checkout_link', serialize(array('Checkout')));
    }
    if(!get_option('qlcd_woo_chatbot_featured_product_welcome')) {
        update_option('qlcd_woo_chatbot_featured_product_welcome', serialize(array('I have found following featured products')));
    }
    if(!get_option('qlcd_woo_chatbot_viewed_product_welcome')) {
        update_option('qlcd_woo_chatbot_viewed_product_welcome', serialize(array('I have found following recently viewed products')));
    }
    if(!get_option('qlcd_woo_chatbot_latest_product_welcome')) {
        update_option('qlcd_woo_chatbot_latest_product_welcome', serialize(array('I have found following latest products')));
    }
    if(!get_option('qlcd_woo_chatbot_cart_welcome')) {
        update_option('qlcd_woo_chatbot_cart_welcome', serialize(array('I have found following items from Shopping Cart.')));
    }
    if(!get_option('qlcd_woo_chatbot_cart_title')) {
        update_option('qlcd_woo_chatbot_cart_title', serialize(array('Title')));
    }
    if(!get_option('qlcd_woo_chatbot_cart_quantity')) {
        update_option('qlcd_woo_chatbot_cart_quantity', serialize(array('Qty')));
    }
    if(!get_option('qlcd_woo_chatbot_cart_price')) {
        update_option('qlcd_woo_chatbot_cart_price', serialize(array('Price')));
    }
	
	if(!get_option('qlcd_woo_chatbot_cart_total')) {
        update_option('qlcd_woo_chatbot_cart_total', serialize(array('Total')));
    }
	
	
    if(!get_option('qlcd_woo_chatbot_no_cart_items')) {
        update_option('qlcd_woo_chatbot_no_cart_items', serialize(array('No items in the cart')));
    }
    if(!get_option('qlcd_woo_chatbot_cart_updating')) {
        update_option('qlcd_woo_chatbot_cart_updating', serialize(array('Updating cart items ...')));
    }
    if(!get_option('qlcd_woo_chatbot_cart_removing')) {
        update_option('qlcd_woo_chatbot_cart_removing', serialize(array('Removing cart items ...')));
    }
    if(!get_option('qlcd_woo_chatbot_wildcard_msg')) {
        update_option('qlcd_woo_chatbot_wildcard_msg', serialize(array('I am here to find what you need. What are you looking for?')));
    }
    if(!get_option('qlcd_woo_chatbot_empty_filter_msg')) {
        update_option('qlcd_woo_chatbot_empty_filter_msg', serialize(array('Sorry, I did not understand you.')));
    }
    if(!get_option('qlcd_woo_chatbot_sys_key_help')) {
        update_option('qlcd_woo_chatbot_sys_key_help', 'start');
    }
    if(!get_option('qlcd_woo_chatbot_sys_key_product')) {
        update_option('qlcd_woo_chatbot_sys_key_product', 'product');
    }
    if(!get_option('qlcd_woo_chatbot_sys_key_catalog')) {
        update_option('qlcd_woo_chatbot_sys_key_catalog', 'catalog');
    }
    if(!get_option('qlcd_woo_chatbot_sys_key_order')) {
        update_option('qlcd_woo_chatbot_sys_key_order', 'order');
    }
    if(!get_option('qlcd_woo_chatbot_sys_key_support')) {
        update_option('qlcd_woo_chatbot_sys_key_support', 'support');
    }
    if(!get_option('qlcd_woo_chatbot_sys_key_reset')) {
        update_option('qlcd_woo_chatbot_sys_key_reset', 'reset');
    }
    if(!get_option('qlcd_woo_chatbot_help_welcome')) {
        update_option('qlcd_woo_chatbot_help_welcome', serialize(array('Welcome to Help Section.')));
    }
    if(!get_option('qlcd_woo_chatbot_help_msg')) {
        update_option('qlcd_woo_chatbot_help_msg', serialize(array('<h3>Type and Hit Enter</h3>  1. <b>start</b> Get back to the main menu. <br> 2. <b>prouduct</b> for  product. <br>  3. <b>catalog</b> for  PRODUCT CATEGORIES. <br> 4. <b>order</b> for  ORDER STATUS. <br> 5. <b>support</b> for  SUPPORT. <br> 6. <b>reset</b> To clear chat history and start from the beginning.')));
     }
    if(!get_option('qlcd_woo_chatbot_reset')) {
        update_option('qlcd_woo_chatbot_reset', serialize(array('Do you want to clear our chat history and start over?')));
    }
    if(!get_option('qlcd_woo_chatbot_wildcard_product')) {
        update_option('qlcd_woo_chatbot_wildcard_product', serialize(array('Product Search')));
    }
    if(!get_option('qlcd_woo_chatbot_wildcard_catalog')) {
        update_option('qlcd_woo_chatbot_wildcard_catalog', serialize(array('Catalog')));
    }
    if(!get_option('qlcd_woo_chatbot_featured_products')) {
        update_option('qlcd_woo_chatbot_featured_products', serialize(array('Featured Products')));
    }
    if(!get_option('qlcd_woo_chatbot_sale_products')) {
        update_option('qlcd_woo_chatbot_sale_products', serialize(array('Products on  Sale')));
    }
    if(!get_option('qlcd_woo_chatbot_wildcard_support')) {
        update_option('qlcd_woo_chatbot_wildcard_support', serialize(array('Support')));
    }
  if(!get_option('qlcd_woo_chatbot_messenger_label')) {
        update_option('qlcd_woo_chatbot_messenger_label', serialize(array('Chat with Us on Facebook Messenger')));
    }
    if(!get_option('qlcd_woo_chatbot_product_success')) {
        update_option('qlcd_woo_chatbot_product_success', serialize(array('Great! We have these products for', 'Found these products for')));
    }
    if(!get_option('qlcd_woo_chatbot_product_fail')) {
        update_option('qlcd_woo_chatbot_product_fail', serialize(array('Oops! Nothing matches your criteria', 'Sorry, I found nothing')));
    }
    if(!get_option('qlcd_woo_chatbot_product_asking')) {
        update_option('qlcd_woo_chatbot_product_asking', serialize(array('What are you shopping for?')));
    }
    if(!get_option('qlcd_woo_chatbot_product_suggest')) {
        update_option('qlcd_woo_chatbot_product_suggest', serialize(array('You can browse our extensive catalog. Just pick a category from below:')));
    }
    if(!get_option('qlcd_woo_chatbot_product_infinite')) {
        update_option('qlcd_woo_chatbot_product_infinite', serialize(array('Too many choices? Let\'s try another search term', 'I may have something else for you. Why not search again?')));
    }
    if(!get_option('qlcd_woo_chatbot_load_more')) {
        update_option('qlcd_woo_chatbot_load_more', serialize(array('Load More')));
    }
    if(!get_option('qlcd_woo_chatbot_wildcard_order')) {
        update_option('qlcd_woo_chatbot_wildcard_order', serialize(array('Order Status')));
    }
    if(!get_option('qlcd_woo_chatbot_order_welcome')) {
        update_option('qlcd_woo_chatbot_order_welcome', serialize(array('Welcome to Order status section!')));
    }
    if(!get_option('qlcd_woo_chatbot_order_username_asking')) {
        update_option('qlcd_woo_chatbot_order_username_asking', serialize(array('Please type your username?')));
    }
    if(!get_option('qlcd_woo_chatbot_order_username_password')) {
        update_option('qlcd_woo_chatbot_order_username_password', serialize(array('Please type your password')));
    }
    if(!get_option('qlcd_woo_chatbot_order_username_not_exist')) {
        update_option('qlcd_woo_chatbot_order_username_not_exist', serialize(array('This username does not exist.')));
    }
    if(!get_option('qlcd_woo_chatbot_order_username_thanks')) {
        update_option('qlcd_woo_chatbot_order_username_thanks', serialize(array('Thank you for the username')));
    }
    if(!get_option('qlcd_woo_chatbot_order_password_incorrect')) {
        update_option('qlcd_woo_chatbot_order_password_incorrect', serialize(array('Sorry Password is not correct!')));
    }
    if(!get_option('qlcd_woo_chatbot_asking_email')) {
        update_option('qlcd_woo_chatbot_asking_email', serialize(array('Please provide your email address')));
    }
    if(!get_option('qlcd_woo_chatbot_order_not_found')) {
        update_option('qlcd_woo_chatbot_order_not_found', serialize(array('I did not find any order by you')));
    }
     if(!get_option('qlcd_woo_chatbot_order_found')) {
        update_option('qlcd_woo_chatbot_order_found', serialize(array('I have found the following orders')));
    }
    if(!get_option('qlcd_woo_chatbot_order_email_support')) {
        update_option('qlcd_woo_chatbot_order_email_support', serialize(array('Email our support center about your order.')));
    }
    if(!get_option('qlcd_woo_chatbot_support_welcome')) {
        update_option('qlcd_woo_chatbot_support_welcome', serialize(array('Welcome to Support Section')));
    }
    if(!get_option('qlcd_woo_chatbot_support_email')) {
        update_option('qlcd_woo_chatbot_support_email', serialize(array('Click me if you want to send us a email.')));
    }
    if(!get_option('qlcd_woo_chatbot_asking_msg')) {
        update_option('qlcd_woo_chatbot_asking_msg', serialize(array('Thank you for email address. Please write your message now.')));
    }
    if(!get_option('qlcd_woo_chatbot_invalid_email')) {
        update_option('qlcd_woo_chatbot_invalid_email', serialize(array('Sorry, Email address is not valid! Please provide a valid email.')));
    }
    if(!get_option('qlcd_woo_chatbot_support_phone')) {
        update_option('qlcd_woo_chatbot_support_phone', serialize(array('Leave your number. We will call you back!')));
    }
    if(!get_option('qlcd_woo_chatbot_asking_phone')) {
        update_option('qlcd_woo_chatbot_asking_phone', serialize(array('Please provide your Phone number')));
    }
    if(!get_option('qlcd_woo_chatbot_thank_for_phone')) {
        update_option('qlcd_woo_chatbot_thank_for_phone', serialize(array('Thank you for Phone number')));
    }
    if(!get_option('qlcd_woo_chatbot_support_option_again')) {
        update_option('qlcd_woo_chatbot_support_option_again', serialize(array('You may choose option from below.')));
    }
    if(!get_option('qlcd_woo_chatbot_admin_email')) {
        update_option('qlcd_woo_chatbot_admin_email', $admin_email);
    }
    if(!get_option('qlcd_woo_chatbot_email_sub')) {
        update_option('qlcd_woo_chatbot_email_sub', sanitize_text_field('WooWBot Support Mail'));
    }
    if(!get_option('qlcd_woo_chatbot_email_sent')) {
        update_option('qlcd_woo_chatbot_email_sent', sanitize_text_field('Your email was sent successfully.Thanks!'));
    }
    if(!get_option('qlcd_woo_chatbot_email_fail')) {
        update_option('qlcd_woo_chatbot_email_fail', sanitize_text_field('Sorry! I could send your mail! Please contact the webmaster.'));
    }
    if(!get_option('qlcd_woo_chatbot_notification_interval')) {
        update_option('qlcd_woo_chatbot_notification_interval', sanitize_text_field(5));
    }
    if(!get_option('qlcd_woo_chatbot_notifications')) {
        update_option('qlcd_woo_chatbot_notifications', serialize(array('Welcome to Our Store')));
    }
    if(!get_option('support_query')) {
        update_option('support_query', serialize(array('What is WoowBot?')));
    }
    if(!get_option('support_ans')) {
        update_option('support_ans', serialize(array('WooWBot is a stand alone WooCommerce Chat Bot with zero configuration or bot training required. This plug and play chatbot also does not require any 3rd party service integration like Facebook. This chat bot helps shoppers find the products they are looking for easily and increase store sales! WoowBot is a must have plugin for trending conversational commerce or conversational shopping.')));
    }
    if(!get_option('qlcd_woo_chatbot_search_option')) {
        update_option('qlcd_woo_chatbot_search_option', 'standard');
    }
    if(!get_option('woo_chatbot_index_count')) {
        update_option('woo_chatbot_index_count', 0);
    }
    if(!get_option('woo_chatbot_app_pages')) {
        update_option('woo_chatbot_app_pages', 0);
    }
    if(!get_option('enable_woo_chatbot_custom_search')) {
        update_option('enable_woo_chatbot_custom_search', '');
    }
    if(!get_option('enable_woo_chatbot_custom_intent')) {
        update_option('enable_woo_chatbot_custom_intent', '');
    }
    if(!get_option('enable_woo_chatbot_rich_response')) {
        update_option('enable_woo_chatbot_rich_response', '');
    }


    //messenger options.
    if(!get_option('enable_woo_chatbot_messenger')) {
        update_option('enable_woo_chatbot_messenger', '');
    }
    if(!get_option('enable_woo_chatbot_messenger_floating_icon')) {
        update_option('enable_woo_chatbot_messenger_floating_icon', '');
    }
    if(!get_option('qlcd_woo_chatbot_fb_app_id')) {
        update_option('qlcd_woo_chatbot_fb_app_id', '');
    }
    if(!get_option('qlcd_woo_chatbot_fb_page_id')) {
        update_option('qlcd_woo_chatbot_fb_page_id', '');
    }
    if(!get_option('qlcd_woo_chatbot_fb_color')) {
        update_option('qlcd_woo_chatbot_fb_color', '#0084ff');
    }
    if(!get_option('qlcd_woo_chatbot_fb_in_msg')) {
        update_option('qlcd_woo_chatbot_fb_in_msg', 'Welcome to Our Store!');
    }
    if(!get_option('qlcd_woo_chatbot_fb_out_msg')) {
        update_option('qlcd_woo_chatbot_fb_out_msg', 'You are not logged in');
    }
    //Skype option
    if(!get_option('enable_woo_chatbot_skype_floating_icon')) {
        update_option('enable_woo_chatbot_skype_floating_icon', '');
    }
    if(!get_option('enable_woo_chatbot_skype_id')) {
        update_option('enable_woo_chatbot_skype_id', '');
    }
     //Whats App
    if(!get_option('enable_woo_chatbot_whats')) {
        update_option('enable_woo_chatbot_whats', '');
    }
    if(!get_option('qlcd_woo_chatbot_whats_label')) {
        update_option('qlcd_woo_chatbot_whats_label', serialize(array('Chat with Us on WhatsApp')));
    }
    if(!get_option('enable_woo_chatbot_floating_whats')) {
            update_option('enable_woo_chatbot_floating_whats', '');
        }
     if(!get_option('qlcd_woo_chatbot_whats_num')) {
            update_option('qlcd_woo_chatbot_whats_num', '');
        }
    //Viber
     if(!get_option('enable_woo_chatbot_floating_viber')) {
            update_option('enable_woo_chatbot_floating_viber', '');
        }
     if(!get_option('qlcd_woo_chatbot_viber_acc')) {
            update_option('qlcd_woo_chatbot_viber_acc', '');
        }
    //Integration others
    if(!get_option('enable_woo_chatbot_floating_phone')) {
        update_option('enable_woo_chatbot_floating_phone', '');
    }
    if(!get_option('qlcd_woo_chatbot_phone')) {
        update_option('qlcd_woo_chatbot_phone', '');
    }
    if(!get_option('enable_woo_chatbot_floating_link')) {
        update_option('enable_woo_chatbot_floating_link', '');
    }

    if(!get_option('qlcd_woo_chatbot_weblink')) {
        update_option('qlcd_woo_chatbot_weblink', '');
    }

    if(!get_option('enable_woo_chatbot_floating_livechat')) {
        update_option('enable_woo_chatbot_floating_livechat', '');
    }
    if(!get_option('enable_woo_custom_intent_livechat_button')) {
        update_option('enable_woo_custom_intent_livechat_button', '');
    }
    if(!get_option('qlcd_woo_chatbot_livechatlink')) {
        update_option('qlcd_woo_chatbot_livechatlink', '');
    }
    if(!get_option('qlcd_woo_livechat_button_label')) {
        update_option('qlcd_woo_livechat_button_label', 'Live Chat');
    }
    if(!get_option('woo_custom_icon_livechat')) {
        update_option('woo_custom_icon_livechat', 'Live Chat');
    }
    //Re-Tagetting
    if(!get_option('qlcd_woo_chatbot_ret_greet')) {
        update_option('qlcd_woo_chatbot_ret_greet', 'Hello');
    }
    if(!get_option('enable_woo_chatbot_exit_intent')) {
        update_option('enable_woo_chatbot_exit_intent', '');
    }
    if(!get_option('woo_chatbot_exit_intent_msg')) {
        update_option('woo_chatbot_exit_intent_msg', 'WAIT, WE HAVE A SPECIAL OFFER FOR YOU! Get Your 50% Discount Now. Use Coupon Code QC50 during checkout.');
    }
    if(!get_option('woo_chatbot_exit_intent_once')) {
        update_option('woo_chatbot_exit_intent_once', '');
    }

    if(!get_option('enable_woo_chatbot_scroll_open')) {
        update_option('enable_woo_chatbot_scroll_open', '');
    }
    if(!get_option('woo_chatbot_scroll_open_msg')) {
        update_option('woo_chatbot_scroll_open_msg', 'WE HAVE A VERY SPECIAL OFFER FOR YOU! Get Your 50% Discount Now. Use Coupon Code QC50 during checkout.');
    }
    if(!get_option('woo_chatbot_scroll_percent')) {
        update_option('woo_chatbot_scroll_percent', 50);
    }
    if(!get_option('woo_chatbot_scroll_once')) {
        update_option('woo_chatbot_scroll_once', '');
    }

    if(!get_option('enable_woo_chatbot_auto_open')) {
        update_option('enable_woo_chatbot_auto_open', '');
    }

    if(!get_option('enable_woo_chatbot_ret_sound')) {
        update_option('enable_woo_chatbot_ret_sound', '');
    }
    if(!get_option('enable_woo_chatbot_sound_initial')) {
        update_option('enable_woo_chatbot_sound_initial', '');
    }


    if(!get_option('woo_chatbot_auto_open_msg')) {
        update_option('woo_chatbot_auto_open_msg', 'A SPECIAL OFFER FOR YOU! Get Your 50% Discount Now. Use Coupon Code QC50 during checkout.');
    }
    if(!get_option('woo_chatbot_auto_open_time')) {
        update_option('woo_chatbot_auto_open_time', 10);
    }
    if(!get_option('woo_chatbot_auto_open_once')) {
        update_option('woo_chatbot_auto_open_once', '');
    }
     if(!get_option('woo_chatbot_inactive_once')) {
        update_option('woo_chatbot_inactive_once', '');
    }

    //To complete checkout.
    if(!get_option('enable_woo_chatbot_ret_user_show')) {
        update_option('enable_woo_chatbot_ret_user_show', '');
    }
    if(!get_option('woo_chatbot_auto_open_msg')) {
        update_option('woo_chatbot_checkout_msg', 'You have products in shopping cart, please complete your order.');
    }
    if(!get_option('woo_chatbot_inactive_time')) {
        update_option('woo_chatbot_inactive_time', 300);
    }
    if(!get_option('enable_woo_chatbot_inactive_time_show')) {
        update_option('enable_woo_chatbot_inactive_time_show', '');
    }

    if(!get_option('woo_chatbot_proactive_bg_color')) {
        update_option('woo_chatbot_proactive_bg_color', '#b9c315ad');
    }
    if(!get_option('woo_chatbot_proactive_text_color')) {
        update_option('woo_chatbot_proactive_text_color', '#c34d15ad');
    }
    if(!get_option('enable_woo_chatbot_custom_color')) {
        update_option('enable_woo_chatbot_custom_color', '');
    }
    if(!get_option('woo_chatbot_text_color')) {
        update_option('woo_chatbot_text_color', '#37424c');
    }
    if(!get_option('woo_chatbot_link_color')) {
        update_option('woo_chatbot_link_color', '#1f8ceb');
    }
    if(!get_option('woo_chatbot_link_hover_color')) {
        update_option('woo_chatbot_link_hover_color', '#65b6fd');
    }
    if(!get_option('woo_chatbot_bot_msg_bg_color')) {
        update_option('woo_chatbot_bot_msg_bg_color', '#1f8ceb');
    }
    if(!get_option('woo_chatbot_bot_msg_text_color')) {
        update_option('woo_chatbot_bot_msg_text_color', '#ffffff');
    }
    if(!get_option('woo_chatbot_user_msg_text_color')) {
        update_option('woo_chatbot_user_msg_text_color', '#ffffff');
    }
    if(!get_option('woo_chatbot_user_msg_bg_color')) {
        update_option('woo_chatbot_user_msg_bg_color', '#ffffff');
    }
	
	
	if(!get_option('woo_chatbot_user_msg_text_color')) {
        update_option('woo_chatbot_user_msg_text_color', '#ffffff');
    }
    if(!get_option('woo_chatbot_user_msg_bg_color')) {
        update_option('woo_chatbot_user_msg_bg_color', '#ffffff');
    }
	

    if(!get_option('disable_woo_chatbot_feedback')) {
        update_option('disable_woo_chatbot_feedback','');
    }
    if(!get_option('qlcd_woo_chatbot_feedback_label')) {
        update_option('qlcd_woo_chatbot_feedback_label',serialize(array('Send Feedback')));
    }

    if(!get_option('enable_woo_chatbot_meta_title')) {
        update_option('enable_woo_chatbot_meta_title','');
    }
    if(!get_option('qlcd_woo_chatbot_meta_label')) {
        update_option('qlcd_woo_chatbot_meta_label','*New Messages');
    }

    if(!get_option('disable_woo_chatbot_call_gen')) {
        update_option('disable_woo_chatbot_call_gen', '');
    }
    if(!get_option('disable_woo_chatbot_call_sup')) {
        update_option('disable_woo_chatbot_call_sup', '');
    }

    if(!get_option('qlcd_woo_chatbot_phone_sent')) {
        update_option('qlcd_woo_chatbot_phone_sent',  'Thanks for your phone number. We will call you ASAP!');
    }
    if(!get_option('qlcd_woo_chatbot_phone_fail')) {
        update_option('qlcd_woo_chatbot_phone_fail', 'Sorry! I could not collect phone number!');
    }
    if(!get_option('enable_woo_chatbot_opening_hour')) {
        update_option('enable_woo_chatbot_opening_hour', '');
    }
    if(!get_option('enable_woo_chatbot_opening_hour')) {
        update_option('woowbot_hours', array());
    }

    if(!get_option('enable_woo_chatbot_dailogflow')) {
        update_option('enable_woo_chatbot_dailogflow', '');
    }
    if(!get_option('qlcd_woo_chatbot_dialogflow_client_token')) {
        update_option('qlcd_woo_chatbot_dialogflow_client_token', '');
    }
    if(!get_option('$qlcd_woo_chatbot_dialogflow_defualt_reply')) {
        update_option('$qlcd_woo_chatbot_dialogflow_defualt_reply', 'Sorry, I did not understand you. You may browse');
    }
    if(!get_option('custom_intent_labels')) {
        update_option('custom_intent_labels', serialize(array()));
    }
    if(!get_option('custom_intent_names')) {
        update_option('custom_intent_names', serialize(array()));
    }
    if(!get_option('custom_intent_kewords')) {
        update_option('custom_intent_kewords', serialize(array()));
    }


}
/*
 * Reset Options will be insert as defualt data
 */
add_action('wp_ajax_qcld_woo_chatboot_delete_all_options', 'qcld_woo_chatboot_delete_all_options');
add_action('wp_ajax_nopriv_qcld_woo_chatboot_delete_all_options', 'qcld_woo_chatboot_delete_all_options');
//Jarvis all option will be delete during uninstlling.
function qcld_woo_chatboot_delete_all_options(){
    delete_option('disable_woo_chatbot');
    delete_option('disable_woo_chatbot_icon_animation');
    delete_option('disable_woo_chatbot_on_mobile');
	delete_option('skip_woo_greetings');
	delete_option('limit_msg_show');
    delete_option('disable_woo_chatbot_product_search');
    delete_option('disable_woo_chatbot_catalog');
    delete_option('disable_woo_chatbot_order_status');
    delete_option('disable_woo_chatbot_support');
    delete_option('disable_woo_chatbot_notification');
    delete_option('disable_woo_chatbot_notification_mobile');
    delete_option('enable_woo_chatbot_rtl');
    delete_option('enable_woo_chatbot_mobile_full_screen');
    delete_option('disable_woo_chatbot_cart_item_number');
    delete_option('disable_woo_chatbot_featured_product');
    delete_option('disable_woo_chatbot_sale_product');
    delete_option('woo_chatbot_open_product_detail');
    delete_option('qlcd_woo_chatbot_product_orderby');
    delete_option('qlcd_woo_chatbot_product_order');
    delete_option('qlcd_woo_chatbot_ppp');
    delete_option('woo_chatbot_show_parent_category');
    delete_option('woo_chatbot_show_sub_category');
    delete_option('woo_chatbot_exclude_stock_out_product');
    delete_option('woo_chatbot_hide_product_details_add_to_cart');
    delete_option('woo_chatbot_show_home_page');
    delete_option('woo_chatbot_show_posts');
    delete_option('woo_chatbot_show_pages');
    delete_option('woo_chatbot_show_pages_list');
    delete_option('woo_chatbot_show_woocommerce');
    delete_option('qlcd_woo_chatbot_stop_words_name');
    delete_option('qlcd_woo_chatbot_stop_words');
    delete_option('qlcd_woo_chatbot_order_user');
    delete_option('woo_chatbot_icon');
    delete_option('woo_chatbot_agent_image');
    delete_option('qcld_woo_chatbot_theme');
    
	delete_option('qcld_woo_chatbot_custom_icons');
	delete_option('qcld_woo_chatbot_custom_icon_help');
	delete_option('qcld_woo_chatbot_custom_icon_support');
	delete_option('qcld_woo_chatbot_custom_icon_recent');
	delete_option('qcld_woo_chatbot_custom_icon_cart');
	delete_option('qcld_woo_chatbot_custom_icon_chat');
	
	
	delete_option('qcld_woo_chatbot_change_bg');
    delete_option('woo_chatbot_custom_css');
    delete_option('qlcd_woo_chatbot_host');
    delete_option('qlcd_woo_chatbot_agent');
    delete_option('qlcd_woo_chatbot_yes');
    delete_option('qlcd_woo_chatbot_no');
    delete_option('qlcd_woo_chatbot_or');
    delete_option('qlcd_woo_chatbot_hello');
    delete_option('qlcd_woo_chatbot_sorry');
	 delete_option('qlcd_woo_chatbot_chat');
    delete_option('qlcd_woo_chatbot_agent_join');
    delete_option('qlcd_woo_chatbot_welcome');
    delete_option('qlcd_woo_chatbot_back_to_start');
    delete_option('qlcd_woo_chatbot_hi_there');
    delete_option('qlcd_woo_chatbot_welcome_back');
    delete_option('qlcd_woo_chatbot_asking_name');
    delete_option('qlcd_woo_chatbot_name_greeting');
    delete_option('qlcd_woo_chatbot_i_am');
    delete_option('qlcd_woo_chatbot_wildcard_msg');
    delete_option('qlcd_woo_chatbot_empty_filter_msg');
    delete_option('qlcd_woo_chatbot_wildcard_product');
    delete_option('qlcd_woo_chatbot_wildcard_catalog');
    delete_option('qlcd_woo_chatbot_featured_products');
    delete_option('qlcd_woo_chatbot_sale_products');
    delete_option('qlcd_woo_chatbot_wildcard_support');
    delete_option('qlcd_woo_chatbot_messenger_label');
    delete_option('qlcd_woo_chatbot_product_success');
    delete_option('qlcd_woo_chatbot_product_fail');
    delete_option('qlcd_woo_chatbot_product_asking');
    delete_option('qlcd_woo_chatbot_product_suggest');
    delete_option('qlcd_woo_chatbot_product_infinite');
    delete_option('qlcd_woo_chatbot_load_more');
    delete_option('qlcd_woo_chatbot_wildcard_order');
    delete_option('qlcd_woo_chatbot_order_welcome');
    delete_option('qlcd_woo_chatbot_order_username_asking');
    delete_option('qlcd_woo_chatbot_order_username_password');
    delete_option('qlcd_woo_chatbot_support_welcome');
    delete_option('qlcd_woo_chatbot_support_email');
    delete_option('qlcd_woo_chatbot_asking_email');
    delete_option('qlcd_woo_chatbot_asking_msg');
    delete_option('qlcd_woo_chatbot_admin_email');
    delete_option('qlcd_woo_chatbot_email_sub');
    delete_option('qlcd_woo_chatbot_email_sent');
    delete_option('qlcd_woo_chatbot_support_phone');
    delete_option('qlcd_woo_chatbot_asking_phone');
    delete_option('qlcd_woo_chatbot_thank_for_phone');
    delete_option('qlcd_woo_chatbot_sys_key_help');
    delete_option('qlcd_woo_chatbot_sys_key_product');
    delete_option('qlcd_woo_chatbot_sys_key_catalog');
    delete_option('qlcd_woo_chatbot_sys_key_order');
    delete_option('qlcd_woo_chatbot_sys_key_support');
    delete_option('qlcd_woo_chatbot_sys_key_reset');
    delete_option('qlcd_woo_chatbot_order_username_not_exist');
    delete_option('qlcd_woo_chatbot_order_username_thanks');
    delete_option('qlcd_woo_chatbot_order_password_incorrect');
    delete_option('qlcd_woo_chatbot_order_not_found');
    delete_option('qlcd_woo_chatbot_order_found');
    delete_option('qlcd_woo_chatbot_order_email_support');
    delete_option('qlcd_woo_chatbot_support_option_again');
    delete_option('qlcd_woo_chatbot_invalid_email');
    delete_option('qlcd_woo_chatbot_shopping_cart');
    delete_option('qlcd_woo_chatbot_add_to_cart');
    delete_option('qlcd_woo_chatbot_cart_link');
    delete_option('qlcd_woo_chatbot_checkout_link');
    delete_option('qlcd_woo_chatbot_cart_welcome');
    delete_option('qlcd_woo_chatbot_featured_product_welcome');
    delete_option('qlcd_woo_chatbot_viewed_product_welcome');
    delete_option('qlcd_woo_chatbot_latest_product_welcome');
    delete_option('qlcd_woo_chatbot_cart_title');
    delete_option('qlcd_woo_chatbot_cart_quantity');
    delete_option('qlcd_woo_chatbot_cart_price');
	delete_option('qlcd_woo_chatbot_cart_total');
    delete_option('qlcd_woo_chatbot_no_cart_items');
    delete_option('qlcd_woo_chatbot_cart_updating');
    delete_option('qlcd_woo_chatbot_cart_removing');
    delete_option('qlcd_woo_chatbot_email_fail');
    delete_option('support_query');
    delete_option('support_ans');
    delete_option('qlcd_woo_chatbot_notification_interval');
    delete_option('qlcd_woo_chatbot_notifications');
    delete_option( 'qlcd_woo_chatbot_search_option');
    delete_option( 'woo_chatbot_index_count');
    delete_option( 'woo_chatbot_app_pages');
    delete_option( 'enable_woo_chatbot_custom_search');
    delete_option( 'enable_woo_chatbot_custom_intent');
    delete_option( 'enable_woo_chatbot_rich_response');
    //messenger option
    delete_option( 'enable_woo_chatbot_messenger');
    delete_option( 'enable_woo_chatbot_messenger_floating_icon');
    delete_option( 'qlcd_woo_chatbot_fb_app_id');
    delete_option( 'qlcd_woo_chatbot_fb_page_id');
    delete_option( 'qlcd_woo_chatbot_fb_color');
    delete_option( 'qlcd_woo_chatbot_fb_in_msg');
    delete_option( 'qlcd_woo_chatbot_fb_out_msg');
    //skype option
    delete_option( 'enable_woo_chatbot_skype_floating_icon');
    delete_option( 'enable_woo_chatbot_skype_id');
    //whats app
    delete_option( 'enable_woo_chatbot_whats');
    delete_option( 'qlcd_woo_chatbot_whats_label');
    delete_option( 'enable_woo_chatbot_floating_whats');
    delete_option( 'qlcd_woo_chatbot_whats_num');
    // Viber
    delete_option( 'enable_woo_chatbot_floating_viber');
    delete_option( 'qlcd_woo_chatbot_viber_acc');
    //Integration others
    delete_option( 'enable_woo_chatbot_floating_phone');
    delete_option( 'qlcd_woo_chatbot_phone');
    delete_option( 'enable_woo_chatbot_floating_link');
    delete_option( 'qlcd_woo_chatbot_weblink');
    delete_option( 'enable_woo_chatbot_floating_livechat');
    delete_option( 'enable_woo_custom_intent_livechat_button');
    delete_option( 'qlcd_woo_chatbot_livechatlink');
    delete_option( 'qlcd_woo_livechat_button_label');
    delete_option( 'woo_custom_icon_livechat');
    //Re Targetting
    delete_option( 'qlcd_woo_chatbot_ret_greet');
    delete_option( 'enable_woo_chatbot_exit_intent');
    delete_option( 'woo_chatbot_exit_intent_msg');
    delete_option( 'woo_chatbot_exit_intent_once');

    delete_option( 'enable_woo_chatbot_scroll_open');
    delete_option( 'woo_chatbot_scroll_open_msg');
    delete_option( 'woo_chatbot_scroll_percent');
    delete_option( 'woo_chatbot_scroll_once');

    delete_option( 'enable_woo_chatbot_auto_open');
    delete_option( 'enable_woo_chatbot_ret_sound');
    delete_option( 'enable_woo_chatbot_sound_initial');
    delete_option( 'disable_woo_chatbot_feedback');
    delete_option( 'qlcd_woo_chatbot_feedback_label');
    delete_option( 'enable_woo_chatbot_meta_title');
    delete_option( 'qlcd_woo_chatbot_meta_label');
    delete_option( 'woo_chatbot_auto_open_msg');
    delete_option( 'woo_chatbot_auto_open_time');
    delete_option( 'woo_chatbot_auto_open_once');
    delete_option( 'woo_chatbot_inactive_once');
    delete_option( 'woo_chatbot_proactive_bg_color');
    delete_option( 'woo_chatbot_proactive_text_color');
    delete_option( 'woo_chatbot_bot_msg_bg_color');
    delete_option( 'woo_chatbot_bot_msg_text_color');
    delete_option( 'woo_chatbot_user_msg_bg_color');
    delete_option( 'woo_chatbot_user_msg_text_color');
	delete_option( 'woo_chatbot_buttons_bg_color');
    delete_option( 'woo_chatbot_buttons_text_color');
    delete_option( 'enable_woo_chatbot_custom_color');
    delete_option( 'woo_chatbot_text_color');
    delete_option( 'woo_chatbot_link_color');
    delete_option( 'woo_chatbot_link_hover_color');
    delete_option( 'qlcd_woo_chatbot_phone_sent');
    delete_option( 'qlcd_woo_chatbot_phone_fail');
    delete_option( 'disable_woo_chatbot_call_gen');
    delete_option( 'disable_woo_chatbot_call_sup');

    delete_option( 'enable_woo_chatbot_ret_user_show');
    delete_option( 'enable_woo_chatbot_inactive_time_show');
    delete_option( 'woo_chatbot_inactive_time');
    delete_option( 'woo_chatbot_checkout_msg');
    delete_option( 'qlcd_woo_chatbot_shopper_demo_name');
    delete_option( 'qlcd_woo_chatbot_is_typing');
    delete_option( 'qlcd_woo_chatbot_send_a_msg');
    delete_option( 'qlcd_woo_chatbot_choose_option');
    delete_option( 'qlcd_woo_chatbot_viewed_products');
    delete_option( 'qlcd_woo_chatbot_help_welcome');
    delete_option( 'qlcd_woo_chatbot_help_msg');
    delete_option( 'qlcd_woo_chatbot_reset');
    delete_option( 'enable_woo_chatbot_opening_hour');
    delete_option( 'woowbot_hours');
    delete_option( 'enable_woo_chatbot_dailogflow');
    delete_option( 'qlcd_woo_chatbot_dialogflow_client_token');
    delete_option( '$qlcd_woo_chatbot_dialogflow_defualt_reply');
    delete_option( 'custom_intent_names');
    delete_option( 'custom_intent_labels');
    delete_option( 'custom_intent_kewords');

    qcld_woo_chatboot_defualt_options();
    $html='Reset all options to default successfully.';
    wp_send_json($html);
}
/**
 *
 * Function to load translation files.
 *
 */
function woo_chatbot_lang_init() {
    load_plugin_textdomain( 'woochatbot', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'woo_chatbot_lang_init');