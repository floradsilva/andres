<?php

namespace Includes\Frontend;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
* Add the quoteup enquiry button shortcode.
* Checks if quote button should be dislayed or not.
*/
class QuoteupEnquiryButtonShortcode
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

    protected function __construct()
    {
    }

    /**
     * Callback for enquiry button shortcode
     * Decides whether Enquiry button added or not.
     * Adds the Enquiry buttons on basis of Enquiry type.
     * @param array $atts product data
     * @return HTML Quote Button
     */
    public static function quoteupEnquiryButtonShortcodeCallback($atts)
    {
        global $quoteup;
        $quoteup->displayQuoteButton->instantiateViews();
        global $product, $quoteupMultiproductQuoteButton;

        $btn_class = 'button wdm_enquiry';
        $pid = $atts['product_id'];

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

        if (!QuoteupEnquiryButtonShortcode::shouldQuoteButtonBeDisplayed($pid)) {
            return;
        }
        if ($form_data['show_enquiry_only_loggedin'] == 1 && !is_user_logged_in()) {
            return;
        }

        $quoteup->displayQuoteButton->enqueueScripts($form_data);
        $product = wc_get_product($pid);
        $prod_id = $product->get_id();
        $prod_price = $product->get_price_html();
        $prod_price = strip_tags($prod_price);
        $price = $prod_price;

        ob_start();
        if (isset($form_data[ 'enable_disable_mpe' ]) && $form_data[ 'enable_disable_mpe' ] == 1) {
            //No Modal for Multi Product
            $quoteupMultiproductQuoteButton->displayQuoteButton($prod_id, $btn_class, $quoteup->displayQuoteButton);
        } else {
            if ($product->get_type() == 'variable' && !is_singular('product')) {
                // Display link to the product and not actual form
                $quoteup->displayQuoteButton->displayVariableProductLink($form_data, $btn_class);
            } else {
                $args = array(
                    'prod_id' => $prod_id,
                    'price' => $price,
                    'btn_class' => $btn_class,
                    'instance' => $quoteup->displayQuoteButton,
                    );

                do_action('quoteup_display_enquiry_modal', $args);
            }
        }

        $quoteButtonContent = ob_get_contents();
        ob_end_clean();

        return $quoteButtonContent;
    }


    /**
     * Decides whether Quote button should be displayed or not.
     *
     * @global object $post
     *
     * @return bool return true if button should be displayed. otherwise returns false.
     */
    protected static function shouldQuoteButtonBeDisplayed($product_id)
    {
        $displayButton = false;

        $form_data = quoteupSettings();
        // show only when out of stock feature
        if (isset($form_data[ 'only_if_out_of_stock' ]) && $form_data[ 'only_if_out_of_stock' ] == 1) {
            $isProductInStock = \quoteupIsProductInStock($product_id);
            if ($isProductInStock) {
                return false;
            }
        }

        $current_button_status = get_post_meta($product_id, '_enable_pep', true);

        $displayButton = true;
        if ('yes' !== $current_button_status) {
            $displayButton = false;
        }
        
        return $displayButton;
    }
}

QuoteupEnquiryButtonShortcode::getInstance();
