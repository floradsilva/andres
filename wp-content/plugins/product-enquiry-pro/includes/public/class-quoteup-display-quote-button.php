<?php

namespace Includes\Frontend;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
* This class is to display the quote button according to the settings specified by admin.
* Quote button of single product enquiry or multiproduct.
* If variable product then link to select a variation.

*/
class QuoteUpDisplayQuoteButton
{
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
    public $add_to_cart_disabled_variable_products = array();
    public $enquiry_disabled_variable_products = array();
    public $price_disabled_variable_products = array();

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
    * Load the templates.
    * action for quote button display.
    */
    protected function __construct()
    {
        $this->loadTemplates();
        add_action('wp_head', array($this, 'bootstrapQuoteupButtonDisplay'), 10);
    }

    /**
    * Checks the google Captcha.
    * For  various action hooks that are required.
    */
    public function bootstrapQuoteupButtonDisplay()
    {
        $form_data = quoteupSettings();
        if (!quoteupIsMPEEnabled($form_data) && !quoteupIsCustomFormEnabled($form_data) && isset($form_data['enable_google_captcha']) && $form_data['enable_google_captcha'] == 1 && (is_archive() || is_product())) {
            // When 'default form' and 'single product enquiry' enabled.
            $siteKey            = $form_data[ 'google_site_key' ];
            $isCaptchaVersion3  = quoteupIsCaptchaVersion3($form_data);

            if ($isCaptchaVersion3) {
                // If reCaptcha version 3.
                wp_register_script('quoteup-google-captcha', 'https://www.google.com/recaptcha/api.js?render='.$siteKey, array(), QUOTEUP_VERSION, true);
                wp_enqueue_script('quoteup-google-captcha');
                wp_localize_script('quoteup-google-captcha', 'quoteup_captcha_data', array(
                    'captcha_version'   => 'v3',
                    'site_key'          =>  $siteKey,
                ));
            } else {
                // If reCaptcha version 2.
                echo "<script src='https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit' async defer></script>";
                echo "<script type='text/javascript'>
                    var CaptchaCallback = function() {
                        jQuery('.g-recaptcha').each(function(index, el) {
                           productID = jQuery(this).closest('#frm_enquiry').find('[id^=\"product_id_\"]').val();
                           var widgetID = grecaptcha.render('test'+productID, {'sitekey' : '".$siteKey."', 'callback' : correctCaptcha_quote});
                           jQuery(this).closest('#frm_enquiry').find('#test'+productID).attr('data-widgetID', widgetID);
                        });
                    };

                    var correctCaptcha_quote = function(response) {
                        jQuery('.wdmHiddenRecaptcha').val(response);
                    };
                </script>";
            }
        }
        $this->hookAllRequiredActions();
    }

    /**
    * This function is to add various actions and filter hooks required.
    * Filter to find if Product is purchasable or not.
    * Filter for getting price , variation price for the Products.
    * Action to decide position of Quote button.
    * Action to check Language Error.
    * Action to display Quote button on Archive page.
    * Action to hide variations and add to cart button on the Archive page for Variable Product.
    * Action to display Quote button on Single Product page.
    */
    private function hookAllRequiredActions()
    {
        $form_data = quoteupSettings();
        do_action('quoteup_create_custom_field');
        do_action('pep_create_custom_field');
        add_filter('woocommerce_is_purchasable', array($this, 'enableAddToCartForProduct'), 10, 2);
        add_filter('woocommerce_get_price_html', array($this, 'displayPrice'), 50, 2);
        add_filter('woocommerce_variation_price_html', array($this, 'displayPrice'), 50, 2);
        add_filter('woocommerce_variation_sale_price_html', array($this, 'displayPrice'), 50, 2);
        if ((!isset($form_data['show_enquiry_only_loggedin']) || $form_data['show_enquiry_only_loggedin'] == 0) || is_user_logged_in()) {
            add_action('wp_head', array($this, 'decidePositionOfQuoteButton'), 11);
            add_action('woocommerce_after_shop_loop_item', array($this, 'displayQuoteButtonOnArchive'), 9);
        }
        add_action('woocommerce_before_shop_loop', array($this, 'quoteupEnquiryLanguageError'), 10);
        add_action('wp_footer', array($this, 'hideVariationForVariableProducts'), 10);
        add_action('woocommerce_after_single_variation', array($this, 'hideAddToCartForVariableProduct'));
        add_action('qutoeup_display_quote_button', array($this, 'displayAddToQuoteButtonOnSingleProduct'), 30);
        add_action('quoteup_before_setting_pos_quote_button', array($this, 'addCustomQtyFieldDisplayToWcHook'));
    }

    /**
     * This function gives the error of enquiry language error.
     */
    public function quoteupEnquiryLanguageError()
    {
        global $quoteupMultiproductQuoteButton,$decideDisplayQuoteButton;
        if (quoteupIsWpmlActive()) {
            $currentLanguageName = $quoteupMultiproductQuoteButton->decideDisplayButtonOrMessage('Archive');
            if (!$decideDisplayQuoteButton) {
                echo "<div class = 'quote-button-error'>".sprintf(__('Please change language to %s to make enquiry', QUOTEUP_TEXT_DOMAIN), $currentLanguageName).'</div>';
            }
        }
    }

    /**
    * This function get the instances of the Single Product and Multiproduct Quote buttons.
    * These instances are then set to global variables.
    */
    private function loadTemplates()
    {
        include_once QUOTEUP_PLUGIN_DIR.'/templates/public/class-quoteup-multiproduct-quote-button.php';
        include_once QUOTEUP_PLUGIN_DIR.'/templates/public/class-quoteup-single-product-modal.php';
        $GLOBALS[ 'quoteupSingleProductModal' ] = $quoteupSingleProductModal;
        $GLOBALS[ 'quoteupMultiproductQuoteButton' ] = $quoteupMultiproductQuoteButton;
        unset($quoteupSingleProductModal);
        unset($quoteupMultiproductQuoteButton);
    }

    /**
     * This function decides to display add to cart button or not based on settings done by admin
     * @param int $purchasable 1 if purchasable
     * @param object $product Woocommerce products.
     * @param string $purchasable 1 if purchasable and false if not.
     */
    public function enableAddToCartForProduct($purchasable, $product)
    {
        $prod_id = $product->get_id();

        if (version_compare(WC_VERSION, '2.7', '<')) {
            if (isset($product->parent)) {
                $prod_id = $product->parent->id;
            }
        } else {
            if ($product->get_type() == 'variation' && method_exists($product, 'get_parent_id')) {
                $prod_id = $product->get_parent_id();
            }
        }

        $current_status = get_post_meta($prod_id, '_enable_add_to_cart', true);

        $current_status = apply_filters('quoteup_display_add_to_cart', $current_status, $product);

        if ($current_status == 'yes') {
            return $purchasable;
        } else {
            if ($product->get_type() == 'variable' || $product->get_type() == 'variation') {
                $this->setAddToCartDisabledVariableProducts($prod_id);
            }

            return false;
        }

        return $purchasable;
    }

    /**
    * This function set add to cart disable for variable products.
    * @param int $prod_id 0 if add to cart disable.
    */
    protected function setAddToCartDisabledVariableProducts($prod_id)
    {
        if (!in_array($prod_id, $this->add_to_cart_disabled_variable_products)) {
            $this->add_to_cart_disabled_variable_products[] = $prod_id;
        }
    }
    /**
    * This function disables enquiry for variable product.
    * @param int $prod_id Variable Product Id.
    */
    protected function setEnquiryDisabledVariableProducts($prod_id)
    {
        if (!in_array($prod_id, $this->enquiry_disabled_variable_products)) {
            $this->enquiry_disabled_variable_products[] = $prod_id;
        }
    }

    /**
    * This function disables the Price for Variable Products.
    * @param int $prod_id 0 if enable price disable.
    */
    protected function setPriceDisabledVariableProducts($prod_id)
    {
        if (!in_array($prod_id, $this->price_disabled_variable_products)) {
            $this->price_disabled_variable_products[] = $prod_id;
        }
    }

    /**
     * Add function 'addQuantityFieldIfRequired' to WooCommerce
     * hook 'woocommerce_single_product_summary'.
     */
    public function addCustomQtyFieldDisplayToWcHook()
    {
        add_action('woocommerce_single_product_summary', array($this, 'addQuantityFieldIfRequired'), 30);
    }

    /**
     * This function adds 'quantity' input field on the single product page for
     * simple products if WooCommerce has not displayed the 'quantity' input
     * field.
     *
     * If 'Show Add to cart' PEP setting is disabled, then 'quantity' field is
     * not displayed on the simple product page. In that case, this function
     * adds the quantity field.
     */
    public function addQuantityFieldIfRequired()
    {
        $isWcQtyFieldShown = 0 == did_action('woocommerce_before_add_to_cart_quantity') &&
        0 == did_action('woocommerce_after_add_to_cart_quantity') ? false : true;

        if (!$isWcQtyFieldShown && is_product()) {
            global $wp_query;
            $productId = $wp_query->post->ID;
            $product   = wc_get_product($productId);

            woocommerce_quantity_input(array(
                'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                'input_value' => $product->get_min_purchase_quantity()
            ));
        }
    }

    /**
     * This function decides to display price or not based on settings done by admin
     * @param html $price price of the Product
     * @param object $product Woocommerce Products
     * @return mixed html of price if price enabled , false if disabled
     */
    public function displayPrice($price, $product)
    {
        $prod_id = $product->get_id();
        $productType = $product->get_type();
        if ($productType == 'variation') {
            if (version_compare(WC_VERSION, '3.0.0', '<')) {
                $prod_id = $product->parent->id;
            } else {
                $prod_id = $product->get_parent_id();
            }
        }
        $current_price_status = get_post_meta($prod_id, '_enable_price', true);
        $final_price_status = apply_filters('quoteup_display_price', $current_price_status, $prod_id);
        if ($final_price_status == 'yes') {
            return $price;
        } else {
            if (current_action() == 'woocommerce_variation_price_html' || current_action() == 'woocommerce_variation_sale_price_html' || current_action() == 'woocommerce_get_price_html') {
                $this->setPriceDisabledVariableProducts($prod_id);
            }

            return false;
        }
    }

    /**
     * THis function is used to get stock status of product
     * @param boolean $isProductObjectAvailable true if available else false
     * @param object $product Woocommerce Product object
     * @param int $product_id Product id
     * @param boolean $isProductInStock true if yes.
     */
    public function getStockStatus($isProductObjectAvailable, $product, $product_id)
    {
        if ($isProductObjectAvailable) {
            $isProductInStock = \quoteupIsProductInStock($product);
        } else {
            $isProductInStock = \quoteupIsProductInStock($product_id);
        }

        return $isProductInStock;
    }

    /**
     * This function is used to return the flag to display quote button or not.
     *
     * @param [int]    $product_id   [Product ID]
     * @param [String] $product_type [type of product]
     *
     * @return [boolean] [true if buttons should be displayed]
     */
    public function getDisplayButton($product_id, $product_type)
    {
        $current_button_status = get_post_meta($product_id, '_enable_pep', true);
        $displayButton = true;
        if (empty($current_button_status)) {
            if ($product_type == 'variable') {
                $this->setEnquiryDisabledVariableProducts($product_id);
            }
            $displayButton = false;
        } elseif ($current_button_status == 'yes') {
            $displayButton = true;
        }

        return apply_filters('quoteup_get_display_button', $displayButton);
    }

    /**
     * Decides whether Quote button should be displayed or not.
     * Checks whether the post is a product.
     * If yes check whether in settings display button in out of stock checked or not.
     * If yes display button otherwise return false.
     * @global object $post wordpress posts
     *
     * @return bool return true if button should be displayed. otherwise returns false.
     */
    protected function shouldQuoteButtonBeDisplayed()
    {
        global $post, $product;
        $isProductObjectAvailable = false;

        //Check if Global $product exists or not. If that exists, take information from $product
        //else read $post.

        if (method_exists($product, 'get_id')) {
            $product_id = $product->get_id();
            $isProductObjectAvailable = true;
        } elseif (isset($post->ID)) {
            $product_id = $post->ID;
        } else {
            return false;
        }

        if (method_exists($product, 'get_type')) {
            $product_type = $product->get_type();
        } else {
            $product_object = wc_get_product($product_id);
            $product_type = $product_object->get_type();
        }

        $form_data = quoteupSettings();
        // show only when out of stock feature
        if (isset($form_data[ 'only_if_out_of_stock' ]) && $form_data[ 'only_if_out_of_stock' ] == 1) {
            $isProductInStock = $this->getStockStatus($isProductObjectAvailable, $product, $product_id);
            if ($isProductInStock) {
                if ($product_type == 'variable') {
                    $this->setEnquiryDisabledVariableProducts($product_id);
                }

                return false;
            }
        }

        return $this->getDisplayButton($product_id, $product_type);
    }

    /**
     * This function is used to decide the position of quote button.
     * First it decides that Quote Button should be displayed or not.
     * If yes from settings take the value where to be displayed.
     * provide action and filters for the same.
     */
    public function decidePositionOfQuoteButton()
    {
        global $post;

        /*
         * Check if Enquiry/Quote button should be shown or not
         */
        if (isset($post)) {
            if (!isset($post->post_type) || $post->post_type != 'product') {
                return;
            }

            $show_quoteup_button = apply_filters('quoteup_display_quote_button', $this->shouldQuoteButtonBeDisplayed(), $post->post_type, $post->ID);

            //Keeping old filter for Old PEP customers
            $show_quoteup_button = apply_filters('pep_before_deciding_position_of_enquiry_form', $show_quoteup_button, $post->post_type, $post->ID);

            if (!$show_quoteup_button) {
                return;
            }
        }

        do_action('quoteup_before_setting_pos_quote_button', $post);

        $default_vals = array(
            'pos_radio' => 'show_after_summary',
        );

        $form_init_data = get_option('wdm_form_data', $default_vals);

        if (isset($form_init_data[ 'pos_radio' ])) {
            if ($form_init_data[ 'pos_radio' ] == 'show_after_summary') {
                add_action('woocommerce_single_product_summary', array($this, 'displayAddToQuoteButtonOnSingleProduct'), 30);
            } elseif ($form_init_data[ 'pos_radio' ] == 'show_at_page_end') {
                add_action('woocommerce_after_single_product', array($this, 'displayAddToQuoteButtonOnSingleProduct'), 10);
            }
        } else {
            add_action('woocommerce_single_product_summary', array($this, 'displayAddToQuoteButtonOnSingleProduct'), 30);
        }

        do_action('quoteup_after_setting_pos_quote_button');
    }

    /**
     * This function is used to display quote button on single product page.
     * Gets the Settings or if not specified put the default values.
     * Enqueue the required scripts and get the localization data to be passed to the scripts.
     * Check the Enquiry type from settings Multiproduct or Single product and display quote button accordingly.
     */
    public function displayAddToQuoteButtonOnSingleProduct()
    {
        $this->instantiateViews();
        global $product, $quoteupMultiproductQuoteButton;

        $btn_class = apply_filters('quoteup_enquiry_button_classes', 'single_add_to_cart_button button alt wdm_enquiry');

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

        $this->enqueueScripts($form_data);

        $prod_id = $product->get_id();
        $prod_price = $product->get_price_html();
        $prod_price = strip_tags($prod_price);

        $price = $prod_price;

        if (isset($form_data[ 'enable_disable_mpe' ]) && $form_data[ 'enable_disable_mpe' ] == 1) {
            //No Modal for Multi Product
            $quoteupMultiproductQuoteButton->displayQuoteButton($prod_id, $btn_class, static::$instance);
        } else {
            $args = array(
                'prod_id' => $prod_id,
                'price' => $price,
                'btn_class' => $btn_class,
                'instance' => static::$instance,
                );

            do_action('quoteup_display_enquiry_modal', $args);
        }
    }

    /**
    * This function is to enqueue Scripts and styles for the QuoteUp.
    * @param array $form_data settings of quoteup.
    */
    public function enqueueScripts($form_data)
    {
        wp_enqueue_style('modal_css1', QUOTEUP_PLUGIN_URL.'/css/wdm-bootstrap.css', false, false);
        wp_enqueue_style('quoteup-common-css', QUOTEUP_PLUGIN_URL.'/css/common.css');
        wp_enqueue_style('wdm-quoteup-icon2', QUOTEUP_PLUGIN_URL.'/css/public/wdm-quoteup-icon.css');

        // wp_enqueue_script('phone_validate', QUOTEUP_PLUGIN_URL.'/js/public/phone-format.js', array('jquery'), false, true);

        // jQuery based MutationObserver library to monitor changes in attributes, nodes, subtrees etc
        wp_enqueue_script('quoteup-jquery-mutation-observer', QUOTEUP_PLUGIN_URL.'/js/admin/jquery-observer.js', array('jquery'), false, true);

        //wp_enqueue_script('modal_validate', QUOTEUP_PLUGIN_URL.'/js/public/frontend.js', array('jquery', 'phone_validate'), false, true);
        wp_enqueue_script('modal_validate', QUOTEUP_PLUGIN_URL.'/js/public/frontend.js', array('jquery'), false, true);

        $redirect_url = $this->getRedirectUrl($form_data);

        $data = getLocalizationDataForJs($redirect_url);

        wp_localize_script('modal_validate', 'wdm_data', $data);

        // Enqueue custom form phone field required styles and scripts.
        quoteupEnqueuePhoneFieldsScripts();
    }

    /**
     * This function is used to get the dialog title color.
     *
     * @param array $form_data quoteup settings
     *
     * @return string $pcolor custom color specified in settings
     */
    public function getDialogTitleColor($form_data)
    {
        if (isset($form_data[ 'dialog_product_color' ])) {
            if ($form_data[ 'dialog_product_color' ] != '') {
                $pcolor = $form_data[ 'dialog_product_color' ];
            }
        } else {
            $pcolor = '#333';
        }

        return $pcolor;
    }

    /**
     * This function is used to get the username if user is logged in.
     * @return string $name username of logged in user or username stored in cookie.
     */
    public function getUserName()
    {
        $name = '';
        if (is_user_logged_in()) {
            global $current_user;
            wp_get_current_user();

            $name = $current_user->user_firstname.' '.$current_user->user_lastname;
            if ($name == ' ') {
                $name = $current_user->user_login;
            }
        } else {
            if (isset($_COOKIE[ 'wdmusername' ])) {
                $name = filter_var($_COOKIE[ 'wdmusername' ], FILTER_SANITIZE_STRING);
            }
        }

        return $name;
    }

    /**
     * This function is used to get the user email if user is logged in.\
     * @return string $email user email id if logged in or email stored in cookie.
     */
    public function getUserEmail()
    {
        $email = '';
        if (is_user_logged_in()) {
            global $current_user;
            wp_get_current_user();
            $email = $current_user->user_email;
        } else {
            if (isset($_COOKIE[ 'wdmuseremail' ])) {
                $email = filter_var($_COOKIE[ 'wdmuseremail' ], FILTER_SANITIZE_EMAIL);
            }
        }

        return $email;
    }

    /**
     * This function is used to get dialog color
     * @param array $form_data QuoteUp Settings
     * @return atring $color dialog color
     */
    public function getDialogColor($form_data)
    {
        if (isset($form_data[ 'dialog_color' ])) {
            if ($form_data[ 'dialog_color' ] != '') {
                $color = $form_data[ 'dialog_color' ];
            }
        } else {
            $color = '#fff';
        }

        return $color;
    }

    /**
     * This function is used to display quote button on Archive page.
     * Gets the Settings or if not specified put the default values.
     * Enqueue the required scripts and get the localization data to be passed to the scripts.
     * If it is a Variable Product on Shop or Archive page then give the link to select variation on single product page of that variable product.
     * Check the Enquiry type from settings Multiproduct or Single product and display quote button accordingly.
     */
    public function displayQuoteButtonOnArchive()
    {
        $this->instantiateViews();
        global $product, $quoteupMultiproductQuoteButton, $decideDisplayQuoteButton;

        $btn_class = 'button wdm_enquiry';

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

        //checkbox value
        if (isset($form_data[ 'show_enquiry_on_shop' ])) {
            $shop_enq_btn = $form_data[ 'show_enquiry_on_shop' ];
        } else {
            $shop_enq_btn = '0';
        }

        if ($shop_enq_btn === '0' || !$this->shouldQuoteButtonBeDisplayed() || !$decideDisplayQuoteButton) {
            return;
        }

        $this->enqueueScripts($form_data);

        $prod_id = $product->get_id();
        $prod_price = $product->get_price_html();
        $prod_price = strip_tags($prod_price);
        $price = $prod_price;

        ob_start();
        if ($product->get_type() == 'variable') {
            //Display link to the product and not actual form
            if (quoteupIsWpmlActive() && !$decideDisplayQuoteButton) {
                    return;
            }
            $this->displayVariableProductLink($form_data, $btn_class);
        } else {
            if (isset($form_data[ 'enable_disable_mpe' ]) && $form_data[ 'enable_disable_mpe' ] == 1) {
                //No Modal for Multi Product
                $quoteupMultiproductQuoteButton->displayQuoteButton($prod_id, $btn_class, static::$instance);
            } else {
                $args = array(
                    'prod_id' => $prod_id,
                    'price' => $price,
                    'btn_class' => $btn_class,
                    'instance' => static::$instance,
                    );

                do_action('quoteup_display_enquiry_modal', $args);
            }
        }

        $quoteButtonContent = ob_get_contents();
        ob_end_clean();

        echo $quoteButtonContent;
    }
    /**
    * This function gets instance of the Multiproduct and Single Product Quote button.
    */
    public function instantiateViews()
    {
        global $quoteupMultiproductQuoteButton, $quoteupSingleProductModal;
        if ($quoteupMultiproductQuoteButton == null || $quoteupSingleProductModal == null) {
            $this->loadTemplates();
        }
    }

    /**
     * This function is used to get the redirect URL after successful enquiry request
     * @param array $form_data Settings for Quoteup
     * @return string $redirect_url redirection page url
     */
    public function getRedirectUrl($form_data)
    {
        if (!empty($form_data[ 'redirect_user' ]) && $form_data[ 'redirect_user' ] != '') {
            $redirect_url = $form_data[ 'redirect_user' ];
        } else {
            $redirect_url = 'n';
        }

        return $redirect_url;
    }

    /**
     * This function is used to get the button text saved in settings
     * @param array $form_data Settings for Quoteup
     * @return string custom label for button
     */
    public function returnButtonText($form_data)
    {
        return empty($form_data[ 'custom_label' ]) ? __('Make an Enquiry', QUOTEUP_TEXT_DOMAIN) : $form_data[ 'custom_label' ];
    }

    /**
     * Displays a link to Variable Products Details page on shop page.
     *
     * @global object $product Global Product Object
     *
     * @param array  $form_data Settings set on the settings page
     * @param string $btn_class Class to be applied to a an Enquiry/Quote button
     */
    public function displayVariableProductLink($form_data, $btn_class)
    {
        global $product;
        $manual_css = 0;
        if (isset($form_data[ 'button_CSS' ]) && $form_data[ 'button_CSS' ] == 'manual_css') {
            $manual_css = 1;
        }
        echo '<div class="quote-form">';
        if (isset($form_data[ 'show_button_as_link' ]) && $form_data[ 'show_button_as_link' ] == 1) {
            ?>
            <a id="wdm-variable-product-trigger-<?php echo $product->get_id() ?>" href='<?php echo esc_url($product->add_to_cart_url()) ?>' style='font-weight: bold;
            <?php
            if ($form_data[ 'button_text_color' ]) {
                echo 'color: '.$form_data[ 'button_text_color' ].';';
            }
            ?>'>
                <?php echo $this->returnButtonText($form_data); ?>
            </a>
            <?php
        } else {
            ?>
            <button class="<?php echo $btn_class ?>" id="wdm-variable-product-trigger-<?php echo $product->get_id(); ?>"  <?php echo ($manual_css == 1) ? getManualCSS($form_data) : ''; ?><?php echo 'onclick="location.href=\''.esc_url($product->add_to_cart_url()).'\'"'; ?>>
                <?php echo $this->returnButtonText($form_data); ?>
            </button>
            <?php
        }
        echo '</div>';
    }

    /**
     * Variations are already being hidden using CSS due to function hideAddToCartForVariableProduct.
     *
     * This function enqueues a javascript file which removes variations and 'Add To Cart' button
     */
    public function hideVariationForVariableProducts()
    {
        //If there are no products for which Add to cart and Quote request is disabled, then no need to load js file
        if (empty($this->add_to_cart_disabled_variable_products) && empty($this->enquiry_disabled_variable_products) && empty($this->price_disabled_variable_products)) {
            return;
        }
        /*
         * Hide variation for variable products if 'Add to Cart' and Enquiry/Quote request is disabled for
         * variable product

        * This js file is to remove add to cart option for variable products on the archive page.
*/
        wp_enqueue_script('hide-variation', QUOTEUP_PLUGIN_URL.'/js/public/hide-var.js', array('jquery'));
        if (!empty($this->add_to_cart_disabled_variable_products)) {
            wp_localize_script('hide-variation', 'quoteup_add_to_cart_disabled_variable_products', $this->add_to_cart_disabled_variable_products);
        }

        /*
         * Hide price for variable products if 'Show Price' is disabled for variable product
         */
        if (!empty($this->price_disabled_variable_products)) {
            wp_localize_script('hide-variation', 'quoteup_price_disabled_variable_products', $this->add_to_cart_disabled_variable_products);
        }

        /*
         * Remove variations for such variable products for which Add to Cart and Enquiry is disabled and Price
         * is hidden
         */
        if (!empty($this->add_to_cart_disabled_variable_products) && !empty($this->enquiry_disabled_variable_products) && !empty($this->price_disabled_variable_products)) {
            $common_product_ids = array_intersect($this->add_to_cart_disabled_variable_products, $this->enquiry_disabled_variable_products, $this->price_disabled_variable_products);
            wp_localize_script('hide-variation', 'quoteup_hide_variation_variable_products', $common_product_ids);
        }
    }

    /**
     * Returning woocommerce_is_purchasable as false on variable product does not hide 'Add to cart' button
     * Therefore, inline syling will be added to hide add to cart button if admin wants to disable
     * 'Add to Cart'.
     * If done by JS, it will display 'Add to Cart' button during page load till JS gets loaded and executed.
     * Hides the 'quantity' field only if 'Enable Enquiry Button' quoteup setting is disabled for the product.
     *
     * @global object $product
     */
    public function hideAddToCartForVariableProduct()
    {
        global $product;
        $isQuoteupEnquiryButtonVisible = $this->shouldQuoteButtonBeDisplayed();

        echo '<!-- Adding inline style to Hide \'Add to Cart\' Button -->';
        if (in_array($product->get_id(), $this->add_to_cart_disabled_variable_products) && !$isQuoteupEnquiryButtonVisible) {
            echo "<style type='text/css'>
                    form.variations_form.cart[data-product_id='{$product->get_id()}'] .single_add_to_cart_button,  form.variations_form.cart[data-product_id='{$product->get_id()}'] .quantity {
                        display : none;
                    }
                  </style>";
        }

        echo '<!-- Adding inline style to Hide Variations if [Add To Cart and Enquiry is disabled] & [Price is Hidden] -->';
        if (in_array($product->get_id(), $this->add_to_cart_disabled_variable_products) && in_array($product->get_id(), $this->enquiry_disabled_variable_products) && in_array($product->get_id(), $this->price_disabled_variable_products)) {
            echo "<style type='text/css'>
                    form.variations_form.cart[data-product_id='{$product->get_id()}']{
                        display : none;
                    }
                  </style>";
        }
    }
}

$this->displayQuoteButton = QuoteUpDisplayQuoteButton::getInstance();
