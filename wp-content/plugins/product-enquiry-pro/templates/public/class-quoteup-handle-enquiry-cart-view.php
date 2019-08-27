<?php

namespace Templates\Frontend;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Handle the view of approval and Rejection of Quote
 * @static $instance object of class
 */

class QuoteupHandleEnquiryCartView
{
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
    * Action to display quote cart view.
    */
    protected function __construct()
    {
        add_action('quoteup_enquiry_cart_content', array($this, 'enquiryCartView'));
    }

    /**
     * This function is used to enqueue scripts
     */
    public function enqueueScripts()
    {
        global $quoteup;
        $form_data = quoteupSettings();
        $prodCount = (int) $quoteup->wcCartSession->get('wdm_product_count');

        if (quoteupIsMPEEnabled($form_data) && $prodCount > 0) {
            // When MPE is enabled.
            wp_enqueue_script('quoteup-quote-cart', QUOTEUP_PLUGIN_URL.'/js/public/quote-cart.js', array('jquery', 'jquery-ui-draggable'), time(), true);
            wp_enqueue_script('quoteup-cart-responsive', QUOTEUP_PLUGIN_URL.'/js/public/responsive-table.js', array('jquery', 'jquery-ui-draggable'), time(), true);

            $redirect_url = $quoteup->displayQuoteButton->getRedirectUrl($form_data);

            $data = getLocalizationDataForJs($redirect_url);

            wp_localize_script('quoteup-quote-cart', 'wdm_data', $data);

            // wp_enqueue_script('phone_validate', QUOTEUP_PLUGIN_URL.'/js/public/phone-format.js', array('jquery'), false, true);

            // Enqueue custom form phone field required styles and scripts.
            quoteupEnqueuePhoneFieldsScripts();
            wp_enqueue_style('quoteup_responsive', QUOTEUP_PLUGIN_URL.'/css/public/responsive-style.css', false);
            if (!quoteupIsCustomFormEnabled($form_data) && isset($form_data['enable_google_captcha']) && $form_data['enable_google_captcha'] == 1) {
                // If default form and captcha is enabled.
                if (quoteupIsCaptchaVersion3($form_data)) {
                    $siteKey = isset($form_data['google_site_key']) ? $form_data['google_site_key'] : false;
                    // If reCaptcha version 3.
                    wp_register_script('quoteup-google-captcha', 'https://www.google.com/recaptcha/api.js?render='.$siteKey, array(), QUOTEUP_VERSION, true);
                    wp_enqueue_script('quoteup-google-captcha');
                    wp_localize_script('quoteup-google-captcha', 'quoteup_captcha_data', array(
                        'captcha_version'   => 'v3',
                        'site_key'          =>  $siteKey,
                    ));
                } else {
                    // If reCaptcha version 2.
                    wp_register_script('quoteup-google-captcha', 'https://www.google.com/recaptcha/api.js', array(), QUOTEUP_VERSION);
                    wp_enqueue_script('quoteup-google-captcha');
                }
            }
        }
    }

    /**
     * This function is used to get css settings
     * @param  [array] $form_data [settings stored in database]
     * @return [int]            [1 if manual css is selected]
     */
    public function getCssSettings($form_data)
    {
        $manual_css = '';
        if (isset($form_data[ 'button_CSS' ]) && $form_data[ 'button_CSS' ] == 'manual_css') {
            $manual_css = 1;
        }

        return $manual_css;
    }

    /**
     * This function is used to get image URL
     * @param  Array $product product details in cart session
     * @return String          Image URL
     */
    public function getImageURL($product)
    {
        $img_content = '';
        if (isset($product[ 'variation_id' ]) && $product[ 'variation_id' ] != '') {
            $img_content = wp_get_attachment_url(get_post_thumbnail_id($product[ 'variation_id' ]));
        }
        if (!$img_content || $img_content == '') {
            $img_content = wp_get_attachment_url(get_post_thumbnail_id($product[ 'id' ]));
        }
        if (!$img_content || $img_content == '') {
            $img_content = WC()->plugin_url().'/assets/images/placeholder.png';
        }

        return $img_content;
    }

    /**
     * This function is used to get total price to be displayed
     * Returns '-' if enable price is disabled
     * @param  String $current_status 'yes' if price is enabled
     * @param  Array $product        Product details in cart page
     * @return String                 price to be displayed
     */
    public function getTotalPrice($current_status, $product)
    {
        if ($current_status == 'yes') {
            $totalPrice = $product['price'];
            if (empty($totalPrice) || $totalPrice == null) {
                $totalPrice = 0;
            }
            $totalPrice = wc_price($totalPrice * $product[ 'quant' ]);
        } else {
            $totalPrice = '-';
        }

        return $totalPrice;
    }

    /**
     * This function is used to get placehoder for remarks textarea
     * @param  Array $form_data Settings stored in database
     * @return String            Placeholder to be displayed
     */
    public function getPlaceholder($form_data)
    {
        $placeholder = quoteupGetRemarksFieldPlaceholderEnqCart($form_data);
        return $placeholder;
    }

    /**
     * This function is used to display cart
     */
    public function enquiryCartView()
    {
        $this->enqueueScripts();

        $shopPageUrl = get_permalink(get_option('woocommerce_shop_page_id'));
        $default_vals = array('show_after_summary' => 1,
            'button_CSS' => 0,
            'pos_radio' => 0,
            'show_powered_by_link' => 0,
            'enable_send_mail_copy' => 0,
            'enable_telephone_no_txtbox' => 0,
            'only_if_out_of_stock' => 0,
            'dialog_product_color' => '#3079ED',
            'dialog_text_color' => '#000000',
            'dialog_color' => '#F7F7F7',
        );
        $form_data = get_option('wdm_form_data', $default_vals);
        $url = admin_url();

        $args = array(
            'shopPageUrl' => $shopPageUrl,
            'form_data' => $form_data,
            'url' => $url,
            );

        $args = apply_filters('quoteup_enquiry_mail_args', $args);
        
        //This loads the template for Enquiry Cart
        quoteupGetPublicTemplatePart('enquiry-cart/enquiry-cart', '', $args);
    }
}

$this->quoteupEnquiryCart = QuoteupHandleEnquiryCartView::getInstance();
