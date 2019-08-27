<?php
/**
 * Enquiry cart.
 *
 * This template can be overridden by copying it to yourtheme/quoteup/public/enquiry-cart/enquiry-cart.php.
 *
 * HOWEVER, on occasion QuoteUp will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author  WisdmLabs
 *
 * @version 6.1.0
 */
global $quoteup;
$prodCount = $quoteup->wcCartSession->get('wdm_product_count');
if ($prodCount > 0) {
//if ($_SESSION[ 'wdm_product_count' ] > 0) {
    ?>
    <div class='quoteup-quote-cart'>
        <div class='woocommerce wdm-quoteup-woo'>
            <div class='error-quote-cart' id='error-quote-cart'>
            </div>
            <?php

            do_action('quoteup_before_enquiry_cart_table', $args);

            /*
             * quoteup_enquiry_cart_table hook.
             *
             * @hooked enquiryCartTable - 10 (Displays enquiry cart table)
             */
            do_action('quoteup_enquiry_cart_table', $args);

            do_action('quoteup_after_enquiry_cart_table', $args);
            ?>

        </div>
        <?php
        if (!isset($form_data['enquiry_form']) || $form_data['enquiry_form'] == 'default' || $form_data['enquiry_form'] == '') {
            $args = apply_filters('quoteup_enquiry_cart_default_form_arguments', $args);

            //This loads the template for default form for enquiry cart
            quoteupGetPublicTemplatePart('enquiry-cart/default-enquiry-form', '', $args);
        } else {
            $args = apply_filters('quoteup_enquiry_cart_custom_form_arguments', $args);

            //This loads the template for default form for enquiry cart
            quoteupGetPublicTemplatePart('enquiry-cart/custom-enquiry-form', '', $args);
        }
        ?>
    </div>
    <?php

    /**
     * quoteup_after_enquiry_form hook.
     *
     * @hooked successMessage - 10 (outputs success message)
     */
    do_action('quoteup_after_enquiry_form', $args);
} else {
    $args = apply_filters('quoteup_empty_enquiry_cart_arguments', $args);

    do_action('quoteup_before_enquiry_cart_empty', $args);

    /*
     * quoteup_enquiry_cart_empty hook.
     *
     * @hooked enquriyCartEmpty - 10 (outputs enquiry cart empty message)
     */
    do_action('quoteup_enquiry_cart_empty', $args);

    do_action('quoteup_after_enquiry_cart_empty', $args);
}
?>
