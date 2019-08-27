<?php
// @since 6.3.4

namespace Includes\Settings;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * This function is used to display Enquiry mail and Enquiry cart customization
 * Section.
 * @param array $form_data Quoteup settings
 */
function displayEnquiryMailAndCartCustomizationOptions($form_data)
{
    ?>
    <fieldset>
        <?php
        if (quoteupIsMPEEnabled($form_data)) {
            echo '<legend class="enquiry-mail-cart-cust-opt">'.__('Enquiry Mail and Enquiry Cart Customization Options', QUOTEUP_TEXT_DOMAIN).'</legend>';
            echo '<legend class="enquiry-mail-opt" style="display:none;">'.__('Enquiry Mail Customization Option', QUOTEUP_TEXT_DOMAIN).'</legend>';
        } else {
            echo '<legend class="enquiry-mail-cart-cust-opt" style="display:none;">'.__('Enquiry Mail and Enquiry Cart Customization Options', QUOTEUP_TEXT_DOMAIN).'</legend>';
            echo '<legend class="enquiry-mail-opt">'.__('Enquiry Mail Customization Option', QUOTEUP_TEXT_DOMAIN).'</legend>';
        }

        quoteupShowDisablePriceColSetting($form_data);
        quoteupShowDisableRemarksColSetting($form_data);
        quoteupShowRemarksLabelSetting($form_data);
        quoteupShowRemarksColumnPlaceholderSetting($form_data);
        ?>
    </fieldset>
    <?php
}

/**
 * This function is used to display 'Disable Price column' setting.
 *
 * @param array $form_data Quoteup settings
 */
function quoteupShowDisablePriceColSetting($form_data)
{
    ?>
    <div class="fd">
        <div class='left_div'>
            <label for="disable_price_col"> <?php _e('Disable Price column', QUOTEUP_TEXT_DOMAIN); ?> </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('This setting let\'s you disable the \'Price\' column in the enquiry mail and enquiry cart.', QUOTEUP_TEXT_DOMAIN);
            echo \quoteupHelpTip($helptip, true);
            ?>          
            <input type="checkbox" class="wdm_wpi_input wdm_wpi_checkbox" value="1" <?php checked(1, isset($form_data[ 'disable_price_col' ]) ? $form_data[ 'disable_price_col' ] : 0); ?> id="disable_price_col" /> 
            <input type="hidden" name="wdm_form_data[disable_price_col]" value="<?php echo isset($form_data[ 'disable_price_col' ]) && $form_data[ 'disable_price_col' ] == 1 ? $form_data[ 'disable_price_col' ] : 0 ?>" />
        </div>
        <div class='clear'></div>
    </div>
    <?php
}

/**
 * This function is used to display 'Disable Expected Price or Remarks column' setting.
 *
 * @param array $form_data Quoteup settings
 */
function quoteupShowDisableRemarksColSetting($form_data)
{
    ?>
    <div class="fd expected_price_remarks_enable_wrapper">
        <div class='left_div'>
            <label for="disable_remarks_col"> <?php _e('Disable Expected Price or Remarks column', QUOTEUP_TEXT_DOMAIN); ?> </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('This setting let\'s you disable the \'Expected Price\' or \'Remarks\' column in the enquiry
            mail and enquiry cart. If \'Quotation System\' is disabled, then it shows \'Remarks\' as column name and if \'Quotation System\' is enabled, it shows \'Expected Price\' as column name.', QUOTEUP_TEXT_DOMAIN);
            echo \quoteupHelpTip($helptip, true);
            ?>          
            <input type="checkbox" class="wdm_wpi_input wdm_wpi_checkbox" value="1" <?php checked(1, isset($form_data[ 'disable_remarks_col' ]) ? $form_data[ 'disable_remarks_col' ] : 0); ?> id="disable_remarks_col" /> 
            <input type="hidden" name="wdm_form_data[disable_remarks_col]" value="<?php echo isset($form_data[ 'disable_remarks_col' ]) && $form_data[ 'disable_remarks_col' ] == 1 ? $form_data[ 'disable_remarks_col' ] : 0 ?>" />
        </div>
        <div class='clear'></div>
    </div>
    <?php
}

/**
 * This function is used to show 'Expected Price or Remarks Label' setting.
 *
 * @param [array] $form_data [Settings stored previously in database]
 */
function quoteupShowRemarksLabelSetting($form_data)
{
    $placeholder = '';

    if (!isset($form_data[ 'expected_price_remarks_label' ]) || empty($form_data[ 'expected_price_remarks_label' ])) {
        $form_data[ 'expected_price_remarks_label' ] = '';
    }

    // If 'Quotation System' is enabled.
    if (isset($form_data[ 'enable_disable_quote' ]) && $form_data[ 'enable_disable_quote' ] == 0) {
        $placeholder = __('Expected Price', QUOTEUP_TEXT_DOMAIN);
    } else {
        $placeholder = __('Remarks', QUOTEUP_TEXT_DOMAIN);
    }
    ?>

    <div class="fd expected_price_remarks_label_wrapper">
        <div class='left_div'>
            <label for="expected_price_remarks_label">
                <?php _e('Expected Price or Remarks column head Label', QUOTEUP_TEXT_DOMAIN); ?>
            </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('Add custom label for Expected Price or Remarks column head in Enquiry mail and Enquiry cart. By deafult, if \'Quotation System\' is disabled, then it shows \'Remarks\' as column name and if \'Quotation System\' is enabled, it shows \'Expected Price\' as column name.', QUOTEUP_TEXT_DOMAIN);
            echo \quoteupHelpTip($helptip, true); ?>
            <input type="text" class="wdm_wpi_input wdm_wpi_text" name="wdm_form_data[expected_price_remarks_label]"
                   value="<?php echo $form_data[ 'expected_price_remarks_label' ]; ?>" placeholder="<?php echo $placeholder; ?>" id="expected_price_remarks_label"  />
        </div>
        <div class='clear'></div>
    </div>
    <?php
}

/**
 * This function is used to show 'Expected Price or Remarks Column Placeholder' setting.
 *
 * @param [array] $form_data [Settings stored previously in database]
 */
function quoteupShowRemarksColumnPlaceholderSetting($form_data) {
    $placeholder = '';

    if (!isset($form_data[ 'expected_price_remarks_col_phdr' ]) || empty($form_data[ 'expected_price_remarks_col_phdr' ])) {
        $form_data[ 'expected_price_remarks_col_phdr' ] = '';
    }

    // If 'Quotation System' is enabled.
    if (isset($form_data[ 'enable_disable_quote' ]) && $form_data[ 'enable_disable_quote' ] == 0) {
        $placeholder = __('Expected price and remarks', QUOTEUP_TEXT_DOMAIN);
    } else {
        $placeholder = __('Remarks', QUOTEUP_TEXT_DOMAIN);
    }
    ?>

    <div class="fd expected_price_remarks_col_phdr_wrapper">
        <div class='left_div'>
            <label for="expected_price_remarks_col_phdr">
                <?php _e('Expected Price or Remarks column field placeholder', QUOTEUP_TEXT_DOMAIN); ?>
            </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('Add custom placeholder for Expected Price or Remarks column field in Enquiry mail and Enquiry cart. By deafult, if \'Quotation System\' is disabled, then it shows \'Remarks\' as field placeholder and if \'Quotation System\' is enabled, it shows \'Expected price and remarks\' as field placeholder.', QUOTEUP_TEXT_DOMAIN);
            echo \quoteupHelpTip($helptip, true); ?>
            <input type="text" class="wdm_wpi_input wdm_wpi_text" name="wdm_form_data[expected_price_remarks_col_phdr]"
                   value="<?php echo $form_data[ 'expected_price_remarks_col_phdr' ]; ?>" placeholder="<?php echo $placeholder; ?>" id="expected_price_remarks_col_phdr"  />
        </div>
        <div class='clear'></div>
    </div>
    <?php
}


displayEnquiryMailAndCartCustomizationOptions($form_data);
