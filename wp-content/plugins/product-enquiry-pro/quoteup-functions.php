<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Checks if provided product id is simple or not.
 *
 * @param int $productId Product Id of the product
 *
 * @return bool returns true if it is a simple produc, otherwise returns false.
 */
function isSimpleProduct($productId)
{
    $product = get_product($productId);
    if ($product->get_type() == null || $product->is_type('simple')) {
        return true;
    }

    return false;
}

/**
 * Returns the Manual CSS Settings saved in the options table.
 *
 * @param array $form_data Quoteup settings
 * @return array $style_attr manual styling  array
 */
function getManualCSS($form_data = array())
{
    if (empty($form_data)) {
        $form_data = quoteupSettings();
    }

    $btn_text_color = $form_data[ 'button_text_color' ];
    $btn_border = $form_data[ 'button_border_color' ];

    $end = $form_data[ 'end_color' ];
    $start = $form_data[ 'start_color' ];
    $style_attr = "style = '";
    $style_array = array();
    if (!empty($btn_text_color)) {
        $style_array[] = "color:{$btn_text_color} !important";
    }
    if (!empty($btn_border)) {
        $style_array[] = "border-color:{$btn_border}";
    }
    if (!empty($start)) {
        $style_array[] = "background: {$start}";
    }
    if (!empty($btn_border)) {
        $style_array[] = "border-color:{$btn_border}";
    }
    if (!empty($start) && !empty($end)) {
        $style_array[] = "background: -webkit-linear-gradient(bottom,{$start}, {$end})";
        $style_array[] = "background: -o-linear-gradient(bottom,{$start}, {$end})";
        $style_array[] = "background: -moz-linear-gradient(bottom,{$start}, {$end})";
        $style_array[] = "filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr={$start}, endColorstr={$end})";
        $style_array[] = "-ms-filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr={$start}, endColorstr={$end})";
        $style_array[] = "background: linear-gradient({$start}, {$end})";
    }
    $style_attr .= implode(';', $style_array)."'";

    return htmlspecialchars($style_attr);
}

/**
 * This function is used to get localization data.
 * Checks some settings for the data.
 * Check if multiproduct is checked or not ,and the get the MPE Cart page.
 * Gets the text of the buttons specified by the user.
 * @param string $redirect_url redirect url to some page if it is specified in the settings.
 * @return array data which can be required for js processing.
 */
function getLocalizationDataForJs($redirect_url)
{
    $product_id = '';
    $quoteCart = '';
    $quoteCartLink = '';
    $mpe = 'no';
    if (is_product()) {
        global $product;
        $product_id = $product->get_id();
    }
    $form_data = get_option('wdm_form_data');
    if (isset($form_data[ 'enable_disable_mpe' ]) && $form_data[ 'enable_disable_mpe' ] == 1) {
        $mpe = 'yes';
    }
    if (isset($form_data[ 'mpe_cart_page' ])) {
        $quoteCart = $form_data[ 'mpe_cart_page' ];
        $quoteCartLink = get_permalink($quoteCart);
    }

    if (isset($form_data[ 'cart_custom_label' ]) && !empty($form_data[ 'cart_custom_label' ])) {
        $QuoteCartLinkWithText = "<a href='$quoteCartLink'>".$form_data[ 'cart_custom_label' ].'</a>';
    } elseif (isset($form_data[ 'enable_disable_quote' ]) && $form_data[ 'enable_disable_quote' ] == 0) {
        $QuoteCartLinkWithText = "<a href='$quoteCartLink'>".__('View Enquiry & Quote Cart', QUOTEUP_TEXT_DOMAIN).'</a>';
    } else {
        $QuoteCartLinkWithText = "<a href='$quoteCartLink'>".__('View Enquiry Cart', QUOTEUP_TEXT_DOMAIN).'</a>';
    }

    $buttonText = empty($form_data[ 'custom_label' ]) ? __('Make an Enquiry', QUOTEUP_TEXT_DOMAIN) : $form_data[ 'custom_label' ];

    $localizationData = array(
        'ajax_admin_url' => admin_url('admin-ajax.php'),
        'name_req' => __('Please Enter Name', QUOTEUP_TEXT_DOMAIN),
        'valid_name' => __('Please Enter Valid Name', QUOTEUP_TEXT_DOMAIN),
        'e_req' => __('Please Enter Email Address', QUOTEUP_TEXT_DOMAIN),
        'email_err' => __('Please Enter Valid Email Address', QUOTEUP_TEXT_DOMAIN),
        'tel_err' => __('Please Enter Valid Telephone No', QUOTEUP_TEXT_DOMAIN),
        'msg_req' => __('Please Enter Message', QUOTEUP_TEXT_DOMAIN),
        'msg_err' => __('Message length must be between 15 to 500 characters', QUOTEUP_TEXT_DOMAIN),
        'nm_place' => __('Name*', QUOTEUP_TEXT_DOMAIN),
        'email_place' => __('Email*', QUOTEUP_TEXT_DOMAIN),
        'please_enter' => __('Please Enter', QUOTEUP_TEXT_DOMAIN),
        'please_select' => __('Please Select', QUOTEUP_TEXT_DOMAIN),
        'fields' => apply_filters('quoteup_get_custom_field', 'fields'),
        'redirect' => $redirect_url,
        'product_id' => $product_id,
        'MPE' => $mpe,
        'view_quote_cart_link_with_text' => $QuoteCartLinkWithText,
        'view_quote_cart_link_with_sold_individual_text' => __('Products that are sold individually can be added only once', QUOTEUP_TEXT_DOMAIN),
        'view_quote_cart_link' => $quoteCartLink,
        'products_added_in_quote_cart' => __('products added in Quote Cart', QUOTEUP_TEXT_DOMAIN),
        'select_variation' => __('Please select variation before sending enquiry', QUOTEUP_TEXT_DOMAIN),
        'variation_id_selector' => quoteupGetVariationIdSelector($form_data),
        'product_added_in_quote_cart' => __('product added in Quote Cart', QUOTEUP_TEXT_DOMAIN),
        'cart_not_updated' => __('Enter valid Quantity', QUOTEUP_TEXT_DOMAIN),
        'spinner_img_url' => admin_url('images/spinner.gif'),
        'empty_cart_remove' => __('Your cart is currently empty', QUOTEUP_TEXT_DOMAIN),
        'buttonText' => $buttonText,
        'cf_phone_field_pref_countries' => quoteupReturnPreferredCountryCodes(),
        //'cf_phone_field_inc_cc' => quoteupReturnOnlyAllowedCC(),
    );

    return apply_filters('quoteup_modify_localization_data', $localizationData);
}

/**
 * Returns the  Base Url of the plugin without trailing slash.
 *
 * @return string plugins' url
 */
function quoteupPluginUrl()
{
    return untrailingslashit(plugins_url('/', __FILE__));
}

/**
 * Returns the  Base dir of the plugin without trailing slash.
 * @return string PEP Plugin directory.
 */
function quoteupPluginDir()
{
    return untrailingslashit(plugin_dir_path(__FILE__));
}

/**
 * Returns the Base dir of the WooCommerce plugin without trailing slash.
 * @return string Woocommerce directory
 */
function quoteupWcPluginDir()
{
    return untrailingslashit(plugin_dir_path(dirname(__FILE__)).'woocommerce');
}

/**
 * Generates a hash to be used for Enquiry.
 *
 * @param int $enquiryId Enquiry Id
 *
 * @return string enquiry hash
 */
function quoteupEnquiryHashGenerator($enquiryId)
{
    $hash = sha1(uniqid(rand(), true));
    list($usec, $sec) = explode(' ', microtime());
    $hash .= dechex($usec).dechex($sec);

    return $enquiryId.'_'.$hash;
}

/**
 * Generates a link to be used to reach Approval/Rejection page.
 *
 * @param string $enquiryHash enquiry hash
 *
 * @return mixed reutrns false or returns a generated link
 */
function quoteLinkGenerator($enquiryHash)
{
    $enquiryHash = trim($enquiryHash);
    if (empty($enquiryHash)) {
        return false;
    }
    $optionData = quoteupSettings();
    if (!isset($optionData[ 'approval_rejection_page' ]) || !intval($optionData[ 'approval_rejection_page' ])) {
        return false;
    }
    $pageId = $optionData[ 'approval_rejection_page' ];
    if (quoteupIsWpmlActive()) {
        $pageId = icl_object_id($optionData[ 'approval_rejection_page' ], 'page', true);
    }

    return add_query_arg('quoteupHash', $enquiryHash, get_page_link($pageId));
}

/**
 * Return the approve and reject URL.
 *
 * @param  int      $enquiryid Enquiry ID
 * @return array    Returns an associative array containing the Approval and
 *                  Rejection URL or empty array.
 */
function getApproveRejectLink($enquiryId)
{
    global $wpdb;
    $appRejURL = array();    
    $tblname = getEnquiryDetailsTable();    

    $hash = quoteupEnquiryHashGenerator($enquiryId);
    \updateHash($enquiryId, $hash);

    //Generate Unique URL For Approve or reject
    $uniqueURL = quoteLinkGenerator($hash);
    if (empty($uniqueURL)) {
        $appRejURL;
    }

    $qry = "SELECT email from $tblname where enquiry_id = $enquiryId";
    $email = $wpdb->get_var($qry);
    $nonce = wp_create_nonce('approveRejectionNonce');
    $rejectURL = $uniqueURL.'&enquiryEmail='.$email.'&source=emailReject';
    $approveURL = $uniqueURL."&_quoteupApprovalRejectionNonce=$nonce&quoteupHash=$hash&enquiryEmail=$email&source=emailApprove";

    $appRejURL = array(
        'approve' => $approveURL,
        'reject'  => $rejectURL,
    );
    return $appRejURL;
}

/**
 * Set hash to the enquiry in database.
 * @param int $enquiry_id unique id for enquiry.
 * @param string $hash Hash value for the enquiry.
 */
function updateHash($enquiry_id, $hash)
{
    global $wpdb;
    $table_name = getEnquiryDetailsTable();
    $wpdb->update(
        $table_name,
        array(
        'enquiry_hash' => $hash,
        ),
        array(
        'enquiry_id' => $enquiry_id,
        )
    );
}

/**
 * Check if a product is sold individually (no quantities).
 * @param [int] $productId [Id of the product]
 * @return bool [if a product is sold individually (no quantities)->yes else no]
 */
function isSoldIndividually($productId)
{
    $product = wc_get_product($productId);

    return $product->is_sold_individually();
}

/**
 * [This function downloads the file from the specified url]
 * it is the copy of wordpress download URL function.
 * We have replaced wp_remote_safe_get to wp_remote_get.
 *
 * @param [string] $url     [URL from which we have to download file]
 * @param int $timeout [set timeout to 300]
 * @return string quoteup download  url
 */
function quoteup_download_url($url, $timeout = 300)
{
    //WARNING: The file is not automatically deleted, The script must unlink() the file.
    if (!$url) {
        return new WP_Error('http_no_url', __('Invalid URL Provided.', QUOTEUP_TEXT_DOMAIN));
    }

    $tmpfname = wp_tempnam($url);
    if (!$tmpfname) {
        return new WP_Error('http_no_file', __('Could not create Temporary file.', QUOTEUP_TEXT_DOMAIN));
    }

    $response = wp_remote_get($url, array('timeout' => $timeout, 'stream' => true,
        'filename' => $tmpfname, ));

    if (is_wp_error($response)) {
        unlink($tmpfname);

        return $response;
    }

    if (200 != wp_remote_retrieve_response_code($response)) {
        unlink($tmpfname);

        return new WP_Error('http_404', trim(wp_remote_retrieve_response_message($response)));
    }

    $content_md5 = wp_remote_retrieve_header($response, 'content-md5');
    if ($content_md5) {
        $md5_check = verify_file_md5($tmpfname, $content_md5);
        if (is_wp_error($md5_check)) {
            unlink($tmpfname);

            return $md5_check;
        }
    }

    return $tmpfname;
}

/**
 * This function is used to get enquiry id from hash.
 *
 * @param [string] $quoteupHash [enquiry hash]
*@return string enquiry hash value
 */
function getEnquiryIdFromHash($quoteupHash)
{
    $enquiry_id = explode('_', $quoteupHash);

    return $enquiry_id[ 0 ];
}

/**
 * This function is used to check if product is available.
 * This also checks the status of product is not in trash.
 *
 * @param [type] $productID [Product Id]
 *
 * @return bool [true if Product available]
 */
function isProductAvailable($productID)
{
    $parentAvailable = '';
    $productType = get_post_type($productID);
    if ($productType == 'product_variation') {
        $parentID = wp_get_post_parent_id($productID);
        $parentAvailable = get_post_status($parentID);
    }
    $productAvailable = get_post_status($productID);

    if ($productAvailable) {
        return !($productAvailable == 'trash' || $parentAvailable == 'trash');
    }

    return false;
}

/**
 * This function is used to display helptip.
 *
 * @param [String] $helptip  [Help tip to be displayed]
 * @param bool     $settings [description]
 * @param string   $image    [alternate for image]
 * @param string   $title    [title for help tip]
 *
 * @return [HTML string] [helptip]
 */
function quoteupHelpTip($helptip, $settings = false, $image = '', $title = '')
{

    if ($settings === true) {
        $wooVersion = WC_VERSION;
        $wooVersion = floatval($wooVersion);
        $value = '';
        if ($wooVersion < 2.5) {
            $value = '<img class="help_tip" data-tip="'.esc_attr($helptip).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
        } else {
            $value = \wc_help_tip($helptip);
        }
        
        return $value;
    }
    if (!empty($image)) {
        return '<img class="help_tip tips" alt="'.esc_attr($title).'" data-tip="'.esc_attr($helptip).'" src="'.$image.'" height="25" width="25" />';
    }

    return '<span class="help_tip tips" data-tip="'.esc_attr($helptip).'">'.esc_attr($title).'</span>';
}

/**
 * Checks whether provided product is in stock or not.
 *
 * @param $product it can be a product id or a Product Object
 *
 * @return bool true if in stock
 */
function quoteupIsProductInStock($product)
{
    if (!is_object($product)) {
        $product = wc_get_product($product);
    }
    if ($product->is_in_stock()) {
        return true;
    }

    return false;
}

/*
 * This function is used to replace 'enquiry', 'quote' and 'quotation' words if set by user in settings.
 *
 * @param [string] $translatedText [Translated text of the orignal string]
 * @param [string] $text           [Orignal string]
 * @param [string] $domain         [text domain of the string]
 *
 * @return [string] [Returns the final string]
 */
add_filter('gettext', 'replaceText', 10, 3);

function replaceText($translatedText, $text, $domain)
{
    $form_data = quoteupSettings();
    // Check if string belongs to our plugin
    if ($domain == 'quoteup') {
        switch ($translatedText) {
            case ' Alternate word for Enquiry ':
            case ' Alternate word for Quote ':
            case 'Quotation  ':
            case 'Product Enquiry Pro %s %s %s':
            case 'Enquiry Details':
            case 'Enquiry & Quote Details':
            case 'Product Enquiry Pro for WooCommerce (A.K.A QuoteUp)':
            case 'QuoteUp Settings':
            case 'Create New Quote':
            case 'Alternate word for Enquiry.':
            case 'Alternate word for Quote and Quotation.':
                return $translatedText;            
            default:
                //Check if replace text for enquiry is set by the admin
                if (isset($form_data['replace_enquiry']) && !empty($form_data['replace_enquiry'])) {
                    //Replace the text
                    $translatedText = str_ireplace('enquiry', $form_data['replace_enquiry'], $translatedText);
                }
                //Check if replace text for quote and quotation is set by the admin
                if (isset($form_data['replace_quote']) && !empty($form_data['replace_quote'])) {
                    //Replace the text
                    $translatedText = str_ireplace('quote', $form_data['replace_quote'], $translatedText);
                    $translatedText = str_ireplace('quotation', $form_data['replace_quote'], $translatedText);
                }
                break;
        }
    }
    unset($text);

    return $translatedText;
}

/**
 * This function is used to get settings.
 * WPML
 * @return array $settings settings of quoteup for current language
 */
function quoteupSettings()
{
    static $settings;
    if (quoteupIsWpmlActive()) {
        global $sitepress;
        if ($sitepress !== null) {
            $currentLanguage = $sitepress->get_current_language();
            if (isset($settings[$currentLanguage])) {
                return $settings[$currentLanguage];
            } else {
                $settings[$currentLanguage] = get_option('wdm_form_data');

                return $settings[$currentLanguage];
            }
        }
    }
    if (!isset($settings) || empty($settings)) {
        $settings = get_option('wdm_form_data');
    }

    return $settings;
}

/**
* Get the Admin Template
* @param string $slug slug name for template.
* @param string $name
* @param array $args parameters to be passed if any.
*/
function quoteupGetAdminTemplatePart($slug, $name = '', $args = array())
{
    quoteupGetTemplatePart($args, $slug, $name, 'admin');
}
/**
* Get the Public Template
* @param string $slug slug name for template.
* @param string $name
* @param array $args parameters to be passed if any.
*/
function quoteupGetPublicTemplatePart($slug, $name = '', $args = array())
{
    quoteupGetTemplatePart($args, $slug, $name, 'public');
}

/**
* Get the  Template for the page
* @param string $templateType admin or public
* @param string $slug slug name for template.
* @param string $name
* @param array $args parameters to be passed if any.
*/
function quoteupGetTemplatePart($args, $slug, $name = '', $templateType = 'public')
{
    $template = '';
    extract($args);
    // Look in yourtheme/quoteup/slug-name.php
    if ($name) {
        $template = locate_template("quoteup/{$templateType}/{$slug}-{$name}.php");
    }

    // Get default slug-name.php
    if (!$template && $name && file_exists(QUOTEUP_PLUGIN_DIR."/templates/{$templateType}/{$slug}-{$name}.php")) {
        $template = QUOTEUP_PLUGIN_DIR."/templates/{$templateType}/{$slug}-{$name}.php";
    }

    // If template file doesn't exist, yourtheme/quoteup/slug.php
    if (!$template) {
        $template = locate_template("quoteup/{$templateType}/{$slug}.php");
    }

    // Get default slug.php
    if (!$template && file_exists(QUOTEUP_PLUGIN_DIR."/templates/{$templateType}/{$slug}.php")) {
        $template = QUOTEUP_PLUGIN_DIR."/templates/{$templateType}/{$slug}.php";
    }

    // Allow 3rd party plugin filter template file from their plugin
    $template = apply_filters("quoteup_get_{$templateType}_template_part", $template, $slug, $name, $args);

    if ($template) {
        include $template;
    }
}

/**
 * This function returns the total quantity of any product in given array of products.
 *
 * @param [array] $product_ids   [Product Ids of Product in Quote Cart]
 * @param [array] $quantities    [Quantities of Product in Quote Cart]
 * @param [array] $variation_ids [Variation ids of Products in Quote cart]
 *
 * @return [array] $quantities [Total quantities of Products in Quote cart]
 */
function getAllCartItemsTotalQuantity($product_ids, $quantities, $variation_ids)
{
    $size = sizeof($product_ids);
    for ($i = 0; $i < $size; ++$i) {
        $product_id = absint($product_ids[$i]);
        $variation_id = absint($variation_ids[$i]);
        $quantity = $quantities[$i];

        if (empty($product_id) || empty($variation_id) || empty($quantity)) {
            continue;
        }

        // Get the product
        $_product = wc_get_product($variation_id ? $variation_id : $product_id);

        $quantities = getManageStockQuantity($_product, $product_id, $variation_id, $quantities, $quantity);
    }

    return $quantities;
}

function getManageStockQuantity($_product, $product_id, $variation_id, $quantities, $quantity)
{
    if ($_product->is_type('variation') && true === $_product->managing_stock()) {
        // Variation has stock levels defined so its handled individually
        $quantities[ $variation_id ] = isset($quantities[ $variation_id ]) ? $quantities[ $variation_id ] + $quantity : $quantity;
    } else {
        $quantities[ $product_id ] = isset($quantities[ $product_id ]) ? $quantities[ $product_id ] + $quantity : $quantity;
    }
    return $quantities;
}

/**
* Sets Enough stock to false and returns the variation details in string format.
* @param int $product_id Product Id
* @param int $variation_id Variation Id if Variable Product
* @param array $variationDetail Variation details
* @return string Varaiation details in string format
*/
function setEnoughStockFalse($product_id, $variation_id, $variationDetail)
{
    global $quoteup_enough_stock, $quoteup_enough_stock_product_id;
    $quoteup_enough_stock = false;
    $quoteup_enough_stock_product_id = $product_id;
    if ('-' != $variation_id && 0 != $variation_id) {
        return getCartVariationString($variationDetail);
    }
}

/**
 * Returns the Product Details of Enquiry.
 *
 * @param  [int]    Enquiry Id
 *
 * @return [mix] Returns the array of Product details if found. Else returns Null
 */
function getProductDetailsOfEnquiry($enquiryId)
{
    global $wpdb;
    $productDetails = $wpdb->get_var($wpdb->prepare("SELECT product_details FROM {$wpdb->prefix}enquiry_detail_new WHERE enquiry_id = %d", $enquiryId));
    if (!is_null($productDetails)) {
        $productDetails = maybe_unserialize($productDetails);
    }

    return $productDetails;
}

/**
 * Returns list of all Product ids in the Quote. For variable products, it returns variation ids.
 *
 * @param  [int]    Enquiry Id
 *
 * @return [mix] Returns array of product ids in the Quote if found. Else returns null.
 */
function getProductIdsInQuote($enquiryId)
{
    global $wpdb;
    return $wpdb->get_col($wpdb->prepare("SELECT DISTINCT product_id FROM {$wpdb->prefix}enquiry_quotation WHERE enquiry_id = %d", $enquiryId));
}

/**
 * THis function is used to get variation string for error in quote.
 *
 * @param [type] $variationDetails [description]
 * @return string variation string with variation data appended
 */
function getQuoteVariationString($variationDetails)
{
    if ($variationDetails != '' && !empty($variationDetails)) {
        $newVariation = array();
        foreach ($variationDetails as $individualVariation) {
            $keyValue = explode(':', $individualVariation);
            $newVariation[ trim($keyValue[ 0 ]) ] = trim($keyValue[ 1 ]);
        }

        $variation_detail = $newVariation;
        $variationString = '';
        foreach ($variation_detail as $attributeName => $attributeValue) {
            if (!empty($variationString)) {
                $variationString .= ',';
            }
            $variationString .= '<b> '.wc_attribute_label(str_replace('attribute_', '', $attributeName)).'</b> : '.$attributeValue;
        }

        return '('.$variationString.')';
    }

    return '';
}

 /**
 * THis function is used to get variation string for error in quote
 * @param  [array] $variationDetails [Variation details if Variable Product]
 * @return [string] $variationString Variation details appended as string
 */
function getCartVariationString($variationDetail)
{
    if ($variationDetail != '') {
        $variationString = '';
        $variation_detail = maybe_unserialize($variationDetail);
        foreach ($variation_detail as $attributeName => $attributeValue) {
            if (!empty($variationString)) {
                $variationString .= ',';
            }
            $variationString .= '<b> '.wc_attribute_label(str_replace('attribute_', '', $attributeName)).'</b> : '.$attributeValue;
        }

        return '('.$variationString.')';
    }

    return '';
}

/**
 * This function is used to remove admin bar language switcher.
 *
 * @param [type] $hook [description]
 */
function quoteupWpmlRemoveAdminBarMenu()
{
    if (quoteupIsWpmlActive() && isset($_GET['page']) && ($_GET['page'] == 'quoteup-details-edit' || $_GET['page'] == 'quoteup-for-woocommerce')) {
        global $sitepress;
        $sitepress->switch_lang('all');
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('WPML_ALS');
    }
}

/**
 * Returns true if WPML is active. Else returns false.
 * @return bool 
 */
function quoteupIsWpmlActive()
{
    if (!defined('ICL_SITEPRESS_VERSION') || ICL_PLUGIN_INACTIVE) {
        return false;
    }
    
    return true;
}

/**
 * This function is used to add short code on given page.
 *
 * @param [int]    $pageId    [page id]
 * @param [string] $shortcode [SHortcode to be added]
 */
function quoteupAddShortcodeOnPage($pageId, $shortcode)
{
    //get content of the page
    $selectedPage = get_post($pageId);

    if ($selectedPage !== null) {
        $pages = getRelatedPages($selectedPage);
        foreach ($pages as $singlePage) {
            //Check if shortcode is present already
            if (quoteupDoesContentHaveShortcode($singlePage->post_content, $shortcode) === false) {
                // Update Selected Page
                $page_data = array(
                      'ID' => $singlePage->ID,
                      'post_content' => $singlePage->post_content."<br /> [$shortcode]",
                );

                // Update the page into the database
                wp_update_post($page_data);
            }
        }
    }
}

/**
* Gets the related pages to the selected page corresponding to translations.
* @param object $selectedPage Page selected
* @return array $pages related pages
*/
function getRelatedPages($selectedPage)
{
    $pages = array($selectedPage);
    if (quoteupIsWpmlActive()) {
        global $sitepress;
        $trid = $sitepress->get_element_trid($selectedPage->ID, 'post_'.$selectedPage->post_type);
        $translations = $sitepress->get_element_translations($trid, 'post_'.$selectedPage->post_type);

        if ($translations) {
            foreach ($translations as $singleTranslation) {
                $page = get_post($singleTranslation->element_id);
                if ($page !== null) {
                    $pages[] = $page;
                }
            }
        }
    }
    return $pages;
}

/**
 * This function is used to remove short code on given page.
 *
 * @param [int]    $pageId    [page id]
 * @param [string] $shortcode [SHortcode to be added]
 */
function quoteupRemoveShortcodeFromPage($pageId, $shortcode)
{
    //get content of the page
    $selectedPage = get_post($pageId);

    if ($selectedPage !== null) {
        $pages = getRelatedPages($selectedPage);
        foreach ($pages as $singlePage) {
            // Update Selected Page
                $page_data = array(
                      'ID' => $singlePage->ID,
                      'post_content' => str_replace("[$shortcode]", '', $selectedPage->post_content),
                );

                // Update the page into the database
                wp_update_post($page_data);
        }
    }
}

/**
 * Returns Attribute Name for variations which are not Taxonomies.
 * @param string $variableProduct Variable PRoduct 
 * @param string $variationAttribute Variation attribute
 * @param array $allAttributes ALL variation attributes
 * @return string $label label for variation
 */
function quoteupVariationAttributeLabel($variableProduct, $variationAttribute, $allAttributes){

        if (version_compare(WC_VERSION, '3.0.0', '<')) {

            if(isset($allAttributes[ str_replace('attribute_', '', $variationAttribute) ])) {
                $label = wc_attribute_label($allAttributes[ str_replace('attribute_', '', $variationAttribute) ]['name']);
            } else {
                $label = $variationAttribute;
            }
            
        } else {
            $label = wc_attribute_label(str_replace('attribute_', '', $variationAttribute), $variableProduct);
        }
        return $label;
}

/**
 * Checks if content has provided shortcode.
 *
 * @param string $content   Content in which shortcode is to be searched
 * @param string $shortcode Shortcode to search
 *
 * @return bool returns true if found, else returns false
 */
function quoteupDoesContentHaveShortcode($content, $shortcode)
{
    if (false === strstr($content, "[$shortcode]")) {
        return false;
    }

    return true;
}

/**
 * Remove Class Filter Without Access to Class Object.
 *
 * In order to use the core WordPress remove_filter() on a filter added with the callback
 * to a class, you either have to have access to that class object, or it has to be a call
 * to a static method.  This method allows you to remove filters with a callback to a class
 * you don't have access to.
 *
 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
 *
 * @param string $tag         Filter to remove
 * @param string $class_name  Class name for the filter's callback
 * @param string $method_name Method name for the filter's callback
 * @param int    $priority    Priority of the filter (default 10)
 *
 * @return bool Whether the function is removed.
 */
function quoteupRemoveClassFilter($tag, $class_name = '', $method_name = '', $priority = 10)
{
    global $wp_filter;

    // Check that filter actually exists first
    if (!isset($wp_filter[ $tag ])) {
        return false;
    }

    /**
     * If filter config is an object, means we're using WordPress 4.7+ and the config is no longer
     * a simple array, rather it is an object that implements the ArrayAccess interface.
     *
     * To be backwards compatible, we set $callbacks equal to the correct array as a reference (so $wp_filter is updated)
     *
     * @see https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
     */
    $callbacks = getCallbacks($wp_filter, $tag);

    // Exit if there aren't any callbacks for specified priority
    if (!isset($callbacks[ $priority ]) || empty($callbacks[ $priority ])) {
        return false;
    }

    // Loop through each filter for the specified priority, looking for our class & method
    foreach ((array) $callbacks[ $priority ] as $filter_id => $filter) {
        // Filter should always be an array - array( $this, 'method' ), if not goto next
        // If first value in array is not an object, it can't be a class
        // Method doesn't match the one we're looking for, goto next
        if (!isset($filter[ 'function' ]) || !is_array($filter[ 'function' ]) || !is_object($filter[ 'function' ][ 0 ]) || $filter[ 'function' ][ 1 ] !== $method_name) {
            continue;
        }

        // Method matched, now let's check the Class
        if (get_class($filter[ 'function' ][ 0 ]) === $class_name) {
            // Now let's remove it from the array
            unset($callbacks[ $priority ][ $filter_id ]);

            // and if it was the only filter in that priority, unset that priority
            if (empty($callbacks[ $priority ])) {
                unset($callbacks[ $priority ]);
            }

            // and if the only filter for that tag, set the tag to an empty array
            if (empty($callbacks)) {
                $callbacks = array();
            }

            // If using WordPress older than 4.7
            if (!is_object($wp_filter[ $tag ])) {
                // Remove this filter from merged_filters, which specifies if filters have been sorted
                unset($GLOBALS[ 'merged_filters' ][ $tag ]);
            }

            return true;
        }
    }
}

function getCallbacks($wp_filter, $tag)
{
    if (is_object($wp_filter[ $tag ]) && isset($wp_filter[ $tag ]->callbacks)) {
        $callbacks = &$wp_filter[ $tag ]->callbacks;
    } else {
        $callbacks = &$wp_filter[ $tag ];
    }
    return $callbacks;
}

/**
 * Remove Class Action Without Access to Class Object.
 *
 * In order to use the core WordPress remove_action() on an action added with the callback
 * to a class, you either have to have access to that class object, or it has to be a call
 * to a static method.  This method allows you to remove actions with a callback to a class
 * you don't have access to.
 *
 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
 *
 * @param string $tag         Action to remove
 * @param string $class_name  Class name for the action's callback
 * @param string $method_name Method name for the action's callback
 * @param int    $priority    Priority of the action (default 10)
 *
 * @return bool Whether the function is removed.
 */
function quoteupRemoveClassAction($tag, $class_name = '', $method_name = '', $priority = 10)
{
    quoteupRemoveClassFilter($tag, $class_name, $method_name, $priority);
}

/**
* Prints the functions hooked by the specific hook name
* @param string $hookName Hook name
*/
function quoteupLogHookedFunctions($hookName)
{
    global $wp_filter;

    if (isset($wp_filter[$hookName])) {
        error_log("PRINTING ALL FUNCTIONS HOOKED ON $hookName ".print_r($wp_filter[$hookName], true));
    }
}

/**
 * This function is used to print vatiations from the given product details.
 *
 * @param [array] $product [Product Details]
 *
 * @return [string] [Variation string]
 */
function printVariations($product)
{
    $variationToBeSent = '';
    $product['variation'] = maybe_unserialize($product['variation']);

    if (isset($product['variation']) && $product['variation'] != '') {
        if (isset($product['variation_id'])) {
            $isAvailable = isProductAvailable($product['variation_id']);
            $variableProduct = wc_get_product($product['variation_id']);
        } else {
            $isAvailable = isProductAvailable($product['variationID']);
            $variableProduct = wc_get_product($product['variationID']);
        }
        if (!$isAvailable) {
            foreach ($product['variation'] as $singleVariationAttribute => $singleVariationValue) {
                if (!empty($variationToBeSent)) {
                    $variationToBeSent .= '<br>';
                }

                $variationToBeSent .= '<b>'.wc_attribute_label($singleVariationAttribute).': </b>'.stripcslashes($singleVariationValue);
            }

            return '<br>'.$variationToBeSent;
        }

        $product_attributes = $variableProduct->get_attributes();
        $variationToBeSent =  getVariationString($product['variation'], $variableProduct, $product_attributes);
        return '<br>'.$variationToBeSent;
    }

    return '';
}

/**
 * Format array for the datepicker.
 *
 * WordPress stores the locale information in an array with a alphanumeric index, and
 * the datepicker wants a numerical index. This function replaces the index with a number
  * @param array $ArrayToStrip array of date field 
 * @return array $NewArray stripped array
 */
function stripArrayIndices($ArrayToStrip)
{
    foreach ($ArrayToStrip as $objArrayItem) {
        $NewArray[] = $objArrayItem;
    }

    return  $NewArray;
}

/**
 * Convert a date format to a jQuery UI DatePicker format.
 *
 * @param string $dateFormat a date format
 *
 * @return string date format
 */
function dateFormatTojQueryUIDatePickerFormat($dateFormat)
{
    $chars = array(
        // Day
        'd' => 'dd', 'j' => 'd', 'l' => 'DD', 'D' => 'D',
        // Month
        'm' => 'mm', 'n' => 'm', 'F' => 'MM', 'M' => 'M',
        // Year
        'Y' => 'yy', 'y' => 'y',
    );

    return strtr((string) $dateFormat, $chars);
}

/**
* Checks whether the scripts is already enqueued.
* @param string $handle script handle
* @return bool true if not enqueued
*/
function shouldScriptBeEnqueued($handle)
{
    if (wp_script_is($handle, 'enqueued') || wp_script_is($handle, 'done')) {
        return false;
    }

    return true;
}

/**
* Checks whether the styles is already enqueued.
* @param string $handle style handle
* @return bool true if not enqueued
*/
function shouldStyleBeEnqueued($handle)
{
    if (wp_style_is($handle, 'enqueued') || wp_style_is($handle, 'done')) {
        return false;
    }

    return true;
}

/**
* Prints the error Log in debug log file.
* @param string $prefixText Prefix titles.
* @param array $data Error data.
*/
function quoteupDebugDataLog($prefixText, $data){
    error_log(strtoupper($prefixText) . ': ' . print_r($data, true));
}

/**
* Gets the debug bulk data and specific the prefixes to the keys.
* @param array $data Error data.
*/
function quoteupDebugBulkData($data){
    if(is_array($data)){
        foreach($data as $key => $value){
            $prefixText = $key;
            if(is_numeric($key)){
                $prefixText = 'DATA ' . $key;
            }
            quoteupDebugDataLog($prefixText, $value);
        }
    }
}

/**
* Gets the products selection template.
* @param array $excludedProducts Products already included 
*
*/
function getProductsSelection($excludedProducts = "")
{
    ?>
    <div class="quote-products-selection">
        <div class="wrap">
            <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('create-dashboard-quotation'); ?>">
            <input  type="hidden" 
                name="wc_products_selections" 
                class="wc-product-search" 
                data-multiple="true" 
                style="width: 75%;" 
                data-placeholder="<?php esc_attr_e('Search for a product', 'quoteup'); ?>"
                data-action="woocommerce_wpml_json_search_products_and_variations" 
                data-selected=""
                data-exclude="<?php echo $excludedProducts ?>"
                value="" />
        </div>
        <button class="button quoteup-add-products-button button-primary"><?php _e('Add Product(s)', QUOTEUP_TEXT_DOMAIN); ?></button>
        <span id="productLoad" class="productLoad"></span>
    </div>
    <?php
}

/**
* This function is used to get the Variation Dropdown,
* @param int $count count of Products.
* @param int $variationID Variation Id
* @param string $productImage Product image url
* @param int $id Product Id.
* @param array $product Product Details.
* @param array $variationData Variation data of Product.
*/

function quoteupVariationDropdown($count, $variationID, $productImage, $id, $product, $variationData)
{
        // Enqueue variation scripts
        wp_enqueue_script('wc-add-to-cart-variation');

        // Get Available variations?
        $get_variations = sizeof($product->get_children()) <= apply_filters('woocommerce_ajax_variation_threshold', 30, $product);

        // Load the template
        $args = array(
            'available_variations' => $get_variations ? $product->get_available_variations() : false,
            'attributes'           => $product->get_variation_attributes(),
            'selected_attributes'  => method_exists($product, 'get_default_attributes') ? $product->get_default_attributes() : $product->get_variation_default_attributes(),
            'count'                => $count,
            'variationID'          => $variationID,
            'productImage'         => $productImage,
            'id'                   => $id,
            'product'              => $product,
            'variationData'        => $variationData,
        );

        quoteupGetAdminTemplatePart('variable', "", $args);
}

/**
* Gets the attributes of the Product
* @param object $product Product details
* @return array atrributes of product.
*/
function getAttributes($product) {
    $attributes = array_filter( (array) maybe_unserialize( $product->product_attributes() ) );
    $taxonomies = wp_list_pluck( wc_get_attribute_taxonomies(), 'attribute_name' );

    // Check for any attributes which have been removed globally
    foreach ( $attributes as $key => $attribute ) {
        if ($attribute['is_taxonomy'] && !in_array(substr( $attribute['name'], 3 ), $taxonomies)) {
                unset( $attributes[ $key ] );
        }
    }

    return apply_filters( 'woocommerce_get_product_attributes', $attributes );
}

/**
* Gets the current site locale.
* @return string $currentLocale
*/
function getCurrentLocale()
{
    $currentLocale = get_locale();
    $arr = explode("_", $currentLocale, 2);
    $currentLocale = $arr[0];
    if (quoteupIsWpmlActive()) {
        global $sitepress;
        $currentLocale = $sitepress->get_current_language();
    }
    return $currentLocale;
}

/**
* Gets the Product price to be displayed 
* @param array $product product detials
* @return int price of product
*/
function quoteupGetPriceToDisplay($product)
{
    if (version_compare(WC_VERSION, '3.0.0', '<')) {
        return $product->get_display_price();
    } else {
        return wc_get_price_to_display($product);
    }
}

/**
* This function gets the array of date localization data for datepicker js.
* @return array localization data
*/
function getDateLocalizationArray()
{
    global $wp_locale;
 
    return array(
        'closeText' => __('Done', QUOTEUP_TEXT_DOMAIN),
        'currentText' => __('Today', QUOTEUP_TEXT_DOMAIN),
        'monthNames' => stripArrayIndices($wp_locale->month),
        'monthNamesShort' => stripArrayIndices($wp_locale->month_abbrev),
        'monthStatus' => __('Show a different month', QUOTEUP_TEXT_DOMAIN),
        'dayNames' => stripArrayIndices($wp_locale->weekday),
        'dayNamesShort' => stripArrayIndices($wp_locale->weekday_abbrev),
        'dayNamesMin' => stripArrayIndices($wp_locale->weekday_initial),
        // set the date format to match the WP general date settings
        'dateFormat' => dateFormatTojQueryUIDatePickerFormat(get_option('date_format')),
        // get the start of week from WP general setting
        'firstDay' => get_option('start_of_week'),
        // is Right to left language? default is false
        'isRTL' => is_rtl(),
    );
}

/**
* This function return the variation details appended in a string format.
* @param array $variation_data Variation details
* @param array $variableProduct Variable product details
* @param array $product_attributes Product variation attributes
* @return string $variationToBeSent Variation to be sent.
*/
function getVariationString($variation_data, $variableProduct, $product_attributes)
{
    $variationToBeSent = '';
    foreach ($variation_data as $name => $value) {
        $taxonomy = urldecode($name);

        // If this is a term slug, get the term's nice name
        if (taxonomy_exists($taxonomy)) {
            $term = get_term_by('slug', $value, $taxonomy);
            if (!is_wp_error($term) && $term && $term->name) {
                $value = $term->name;
            }
            $label = wc_attribute_label($taxonomy);
            // If this is a custom option slug, get the options name
        } else {
            $label = quoteupVariationAttributeLabel($variableProduct, $taxonomy, $product_attributes);
        }

        if (!empty($variationToBeSent)) {
            $variationToBeSent .= '<br>';
        }

        $variationToBeSent .= '<b>'.$label.': </b>'.stripcslashes($value);
    }

    return $variationToBeSent;
}

/**
* This function enqueue the datepicker files for QuoteUp
* Specifies the localization data for date.
*/
function enqueueDatePickerFiles()
{
    // Datepicker files
    global $wp_scripts;
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
            // JS and css required for datepciker
            wp_enqueue_script('datepicker', quoteupPluginUrl().'/js/public/datepicker.js', array('jquery', 'jquery-ui-core', 'jquery-effects-highlight', 'jquery-ui-datepicker'), true);

            // get registered script object for jquery-ui
            $uiObject = $wp_scripts->query('jquery-ui-core');
            // tell WordPress to load the Smoothness theme from Google CDN
            $protocol = is_ssl() ? 'https' : 'http';
    $url = "$protocol://ajax.googleapis.com/ajax/libs/jqueryui/{$uiObject->ver}/themes/smoothness/jquery-ui.min.css";
    wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
    wp_enqueue_style('jquery-ui-datepicker', QUOTEUP_PLUGIN_URL.'/css/admin/datepicker.css');
    $aryArgs = getDateLocalizationArray();
    wp_localize_script('datepicker', 'dateData', $aryArgs);
    // End of datepicker files
}

/**
* Return the variation details in specific array format.
* @param array $variationDetails Variation details array
* @return array $variationDetailsForArray variation details 
*/
function getVariationDetailsForArray($variationDetails)
{
    $variationDetailsForArray = array();
    foreach ($variationDetails as $attriname => $attriValue) {
        $variationString = '';
        $variationString = $attriname.' : '.$attriValue;
        array_push($variationDetailsForArray, $variationString);
    }

    return $variationDetailsForArray;
}

/**
 * Check whether the captcha version 3 is valid or not.
 * 
 * @param   object   $response   Site verify response to verify the token.
 * 
 * return   bool    True if the captcha v3 is valid, false otherwise. 
 */
function quoteupVerifyCaptchaV3($response)
{
    $isCaptchaResponseValid = false;
    if ($response->success && 'quoteup_captcha' === $response->action && (float) $response->score > 0.5) {
        $isCaptchaResponseValid = true;
    }

    return apply_filters('quoteup_is_captcha_v3_valid', $isCaptchaResponseValid, $response);
}

/**
 * Check whether author emails are same.
 * 
 * @param   array   $authorEmails   Array containing author emails.
 * 
 * @return  bool    Returns true if author emails are same, false otherwise.
 */
function quoteupAreAuthorEmailsSame($authorEmails)
{
    if (1 === count(array_unique($authorEmails))) {
        return true;
    }

    return false;
}

/**
 * Returns Enquiry cart page Id. If Enquiry cart page is not set, it retuns 0.
 *
 * @since 6.3.4
 * @param array Quoteup settings.
 *
 * @return int Return an Enquiry cart page Id if set, 0 otherwise.
 */
function quoteupGetEnquiryCartPageId($quoteupSettings = array())
{
    $pageId = 0;
    if (empty($quoteupSettings)) {
        $quoteupSettings = quoteupSettings();
    }

    if (isset($quoteupSettings['mpe_cart_page']) && intval($quoteupSettings['mpe_cart_page'])) {
        $pageId = intval($quoteupSettings['mpe_cart_page']);
    }
    return apply_filters('quoteup_enquiry_cart_page_id', $pageId);
}

/**
 * Returns true if current page is Enquiry cart page, false otherwise.
 * Checks whether the Multi-product enquiry is enabled. If MPE is enabled,
 * checks whether the current page is Enquiry cart page.
 *
 * @since 6.3.4
 * @param array Quoteup settings.
 *
 * @return bool True if current page is enquiry cart page, false otherwise.
 */
function quoteupIsEnquiryCartPage($quoteupSettings = array())
{
    if (empty($quoteupSettings)) {
        $quoteupSettings = quoteupSettings();
    }

    $enquiryCartPageId = quoteupGetEnquiryCartPageId($quoteupSettings);
    $result = is_page($enquiryCartPageId);
    return apply_filters('quoteup_is_enquiry_cart_page', $result, $enquiryCartPageId);
}

/**
 * Returns the string translation. The translation is done by the
 * WPML string translation functinality. The translation string should
 * be entered in the WPML String Translation.
 *
 * @since 6.3.4
 * @param   string  $translationString  String to be translated.
 *
 * @return  string  Translated string.
 */
function quoteupReturnWPMLVariableStrTranslation($translationString)
{
    $translationString = __($translationString, 'quoteup');
    return $translationString;
}

/**
 ******************************************************************************
 * Quoteup settings functions.
 ******************************************************************************
 */

/**
 * Check whether the multi product enquiry is enabled.
 *
 * @param array $quoteupSettings Quoteup setting.
 * 
 * @return bool Returns true if multi-product enquiry is enabled.
 */
function quoteupIsMPEEnabled($quoteupSettings)
{
    return isset($quoteupSettings['enable_disable_mpe']) && 1 == $quoteupSettings['enable_disable_mpe'] ? true : false;
}

/**
 * Return the Enquiry Cart Icon position.
 *
 * @param array $quoteupSettings Quoteup settings.
 * 
 * @return string Returns the string containing the value for Enquiry Cart Icon
 *                Position. Default: 'icon_top_right'.
 */
function quoteupGetEcIconPosition($quoteupSettings)
{
    return isset($quoteupSettings['ec_icon_pos']) ? $quoteupSettings['ec_icon_pos'] : 'icon_top_right';
}

/**
 * Check whether 'Specify Icon Color' setting is enabled.
 *
 * @param array $quoteupSettings Quoteup setting.
 *
 * @return bool Returns true if 'Specify Icon Color' setting is enabled,
 *              false otherwise.
 */
function quoteupIsManualEcIconColorEnabled($quoteupSettings)
{
    return isset($quoteupSettings['enable_manual_ec_icon_color']) && 1 == $quoteupSettings['enable_manual_ec_icon_color'] ? true : false;
}

/**
 * Return the color specified in the setting for various section of enquiry
 * cart icon. Various sections for styling Enquiry Cart Icon:
 *     ec_icon_bg_color
 *     ec_icon_border_color
 *     ec_icon_color
 *     ec_icon_number_bg_color
 *     ec_icon_number_border_color
 *     ec_icon_number_color
 *
 * @param array $quoteupSettings Quoteup setting.
 *
 * @return array Returns array containing the different colors set for the
 *               various section of enquiry cart. Returns empty array if
 *               'Specify Icon Color' setting is disabled.
 */
function quoteupGetEcIconColors($quoteupSettings)
{
    if (!quoteupIsManualEcIconColorEnabled($quoteupSettings)) {
        return array();
    }

    $ecIconColors = array();
    $ecIconColors['ec_icon_bg_color'] = isset($quoteupSettings['ec_icon_bg_color']) ? $quoteupSettings['ec_icon_bg_color'] : '#6D6D6D';
    $ecIconColors['ec_icon_border_color'] = isset($quoteupSettings['ec_icon_border_color']) ? $quoteupSettings['ec_icon_border_color'] : '#6D6D6D';
    $ecIconColors['ec_icon_color'] = isset($quoteupSettings['ec_icon_color']) ? $quoteupSettings['ec_icon_color'] : '#fff';
    $ecIconColors['ec_icon_number_bg_color'] = isset($quoteupSettings['ec_icon_number_bg_color']) ? $quoteupSettings['ec_icon_number_bg_color'] : '#fff';
    $ecIconColors['ec_icon_number_border_color'] = isset($quoteupSettings['ec_icon_number_border_color']) ? $quoteupSettings['ec_icon_number_border_color'] : '#fff';
    $ecIconColors['ec_icon_number_color'] = isset($quoteupSettings['ec_icon_number_color']) ? $quoteupSettings['ec_icon_number_color'] : '#6D6D6D';

    return $ecIconColors;
}

/**
 * Check whether the captcha version is 3 or 2.
 * 
 * @param array $quoteupSettings Quoteup setting.
 * 
 * @return bool Returns true if captcha version 3 is selected, false otherwise.
 */
function quoteupIsCaptchaVersion3($quoteupSettings)
{
    return isset($quoteupSettings['is_captcha_version_3']) && 1 == $quoteupSettings['is_captcha_version_3'] ? true : false;
}

/**
 * Check if the 'custom form' is enalbed for an enquiry.
 * 
 * @param   array   $quoteupSettings Quoteup setting.
 * 
 * @return  bool    True if 'custom form' is enabled, false otherwise.
 */
function quoteupIsCustomFormEnabled($quoteupSettings)
{
    return isset($quoteupSettings['enquiry_form']) && 'custom' == $quoteupSettings['enquiry_form'] ? true : false;
}

/**
 * Check if 'Disable Price column' setting is enabled.
 * 
 * @param   array   $quoteupSettings Quoteup setting.
 * 
 * @return  bool    True if price column is disabled, false otherwise.
 */
function quoteupIsPriceColumnDisabled($quoteupSettings)
{
    return isset($quoteupSettings['disable_price_col']) && 1 == $quoteupSettings['disable_price_col'] ? true : false;
}

/**
 * Checks if 'Disable Expected Price or Remarks column' setting is enalbed.
 * 
 * @param   array   $quoteupSettings Quoteup setting.
 * 
 * @return  bool    True if expected price or remarks is disabled, false otherwise.
 */
function quoteupIsRemarksColumnDisabled($quoteupSettings)
{
    return isset($quoteupSettings['disable_remarks_col']) && 1 == $quoteupSettings['disable_remarks_col'] ? true : false;
}

/**
 * Checks if 'Disable Bootstrap' setting is enalbed.
 * 
 * @param   array   $quoteupSettings Quoteup setting.
 * 
 * @return  bool    True if bootstrap is disabled, false otherwise.
 */
function quoteupIsBootstrapDisabled($quoteupSettings)
{
    return isset($quoteupSettings['disable_bootstrap']) && 1 == $quoteupSettings['disable_bootstrap'] ? true : false;
}

/**
 * Returns selector for Variation Id based on the setting value.
 * 
 * @since 6.3.4
 * @param   array   $quoteupSettings Quoteup setting.
 *
 * @return  string  Variation Id selector. 
 */
function quoteupGetVariationIdSelector($quoteupSettings)
{
    return empty($quoteupSettings['variation_id_selector']) ? '' : $quoteupSettings['variation_id_selector'];
}

/**
 * Return the table head name for 'Expected Price' or 'Remarks' column in the
 * enquiry cart.
 *
 * @since 6.3.4
 * @param array $quoteupSettings Quoteup settings.
 *
 * @return string Return the table head name for 'Expected Price' or 'Remarks' column
 */
function quoteupGetRemarksThNameEnqCart($quoteupSettings)
{
    $tableHeadName = '';

    if (isset($quoteupSettings['expected_price_remarks_label']) && ! empty($quoteupSettings['expected_price_remarks_label'])) {
        $tableHeadName = $quoteupSettings['expected_price_remarks_label'];
    } else if (isset($quoteupSettings['enable_disable_quote']) && $quoteupSettings[ 'enable_disable_quote' ] == 0) {
        $tableHeadName = __('Expected Price', QUOTEUP_TEXT_DOMAIN);
    } else {
        $tableHeadName = __('Remarks', QUOTEUP_TEXT_DOMAIN);
    }

    return $tableHeadName;
}

/**
 * Return the 'Expected Price' or 'Remarks' column field's placeholder in the
 * enquiry cart.
 *
 * @since 6.3.4
 * @param array $quoteupSettings Quoteup settings.
 *
 * @return string Return the 'Expected Price' or 'Remarks' column field's
 *                placeholder.
 */
function quoteupGetRemarksFieldPlaceholderEnqCart($quoteupSettings)
{
    $placeholderString = '';

    if (isset($quoteupSettings['expected_price_remarks_col_phdr']) && ! empty($quoteupSettings['expected_price_remarks_col_phdr'])) {
        $placeholderString = $quoteupSettings['expected_price_remarks_col_phdr'];
    } else if (isset($quoteupSettings['enable_disable_quote']) && $quoteupSettings['enable_disable_quote'] == 0) {
        $placeholderString = __('Expected price and remarks', QUOTEUP_TEXT_DOMAIN);
    } else {
        $placeholderString = __('Remarks', QUOTEUP_TEXT_DOMAIN);
    }

    return $placeholderString;
}

/**
 ******************************************************************************
 * End for Quoteup settings functions.
 ******************************************************************************
 */
