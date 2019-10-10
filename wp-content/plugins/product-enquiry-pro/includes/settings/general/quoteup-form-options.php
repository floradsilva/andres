<?php

namespace Includes\Settings;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * This function is used to display form option fields
  * @param [array] $form_data [Settings stored previously in database]
 */
function formOptionsSection($form_data)
{
    ?>
    <fieldset>

        <?php
        echo '<legend>'.__('Form Options', QUOTEUP_TEXT_DOMAIN).'</legend>';

        enquiryButtonLabel($form_data);
        replaceEnquiry($form_data);
        enquiryButtonLocation($form_data);
        enquiryAsLink($form_data);
        displayWisdmlabs($form_data);
        quoteupShowVariationIdSetting($form_data);
        disableNonce($form_data);
        ?>
    </fieldset>
    <?php
}

/**
 * This is used to show Enquiry button label on settings page.
 *
 * @param [array] $form_data [Settings stored previously in database]
 */
function enquiryButtonLabel($form_data)
{
    ?>

    <div class="fd">
        <div class='left_div'>
            <label for="custom_label">
                <?php _e(' Enquiry Button Label ', QUOTEUP_TEXT_DOMAIN) ?>
            </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('Add custom label for Enquiry or Quote button.', QUOTEUP_TEXT_DOMAIN);
            echo \quoteupHelpTip($helptip, true);
            ?>
            <input type="text" class="wdm_wpi_input wdm_wpi_text" name="wdm_form_data[custom_label]"
                   value="<?php echo empty($form_data[ 'custom_label' ]) ? _e('Make an Enquiry', QUOTEUP_TEXT_DOMAIN) : $form_data[ 'custom_label' ]; ?>" id="custom_label"  />
        </div>
    </div>


    <?php
}


/**
 * This is used to replace 'enquiry' words on settings page.
 *
 * @param [array] $form_data [Settings stored previously in database]
 */
function replaceEnquiry($form_data)
{
    ?>
    <div class="fd">
        <div class='left_div'>
            <label for="replace_enquiry">
                <?php _e(' Alternate word for Enquiry ', QUOTEUP_TEXT_DOMAIN) ?>
            </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('Alternate word for Enquiry.', QUOTEUP_TEXT_DOMAIN);
            echo \quoteupHelpTip($helptip, true);
            ?>
            <input type="text" class="wdm_wpi_input wdm_wpi_text" name="wdm_form_data[replace_enquiry]" value="<?php echo empty($form_data[ 'replace_enquiry' ]) ? 'Enquiry' : $form_data[ 'replace_enquiry' ]; ?>" id="replace_enquiry"  />
        </div>
        <div class="clear"></div>
    </div>
    <?php
}

/**
 * This is used to show Enquiry button location on settings page.
 *
 * @param [array] $form_data [Settings stored previously in database]
 */
function enquiryButtonLocation($form_data)
{
    ?>

    <div class="fd">
        <div class='left_div'>
            <label>
                <?php _e(' Button Location', QUOTEUP_TEXT_DOMAIN) ?>

            </label>

        </div>
        <div class='right_div'>
            <?php
            if (isset($form_data[ 'pos_radio' ])) {
                $pos = $form_data[ 'pos_radio' ];
            } else {
                $pos = 'show_after_summary';
            }
            ?>

            <input type="radio" class="wdm_wpi_input wdm_wpi_checkbox input-without-tip" name="wdm_form_data[pos_radio]"
                   value="show_after_summary" 
                    <?php
                    if ($pos == 'show_after_summary') {
                        ?> 
                    checked 
                        <?php
                    } ?> id="show_after_summary" />
                    <?php echo '<em>'.__(' After single product summary ', QUOTEUP_TEXT_DOMAIN).'</em>'; ?>

            <br />


            <input type="radio" class="wdm_wpi_input wdm_wpi_checkbox input-without-tip" name="wdm_form_data[pos_radio]" value="show_at_page_end" 
            <?php
            if ($pos == 'show_at_page_end') {
                ?>
                checked
                <?php
            } ?> id="show_at_page_end" />

            <?php echo '<em>'.__(' At the end of single product page ', QUOTEUP_TEXT_DOMAIN).'</em>'; ?>
        </div>
        <div class="clear"></div>
    </div>

    <?php
}

/**
 * This is used to show checkbox for show enquiry button as a link on settings page.
 *
 * @param [array] $form_data [Settings stored previously in database]
 *
 * @return [type] [description]
 */
function enquiryAsLink($form_data)
{
    $showButtonAsLink = isset($form_data[ 'show_button_as_link' ]) ? $form_data[ 'show_button_as_link' ] : 0;
    ?>

    <div class="fd">
        <div class='left_div'>
            <label for="link">
                <?php _e(' Display Enquiry Button As A Link ', QUOTEUP_TEXT_DOMAIN) ?>
            </label>
        </div>
        <div class='right_div'>
            <input type="checkbox" class="wdm_wpi_input wdm_wpi_checkbox input-without-tip" value="1" <?php checked(1, $showButtonAsLink); ?> id="show_button_as_link" />
            <input type="hidden" name="wdm_form_data[show_button_as_link]" value="<?php echo isset($form_data[ 'show_button_as_link' ]) && $form_data[ 'show_button_as_link' ] == 1 ? $form_data[ 'show_button_as_link' ] : 0 ?>" />

        </div>
        <div class="clear"></div>
    </div>

    <?php
}

/**
 * This is used to show checkbox for show footer on form.
 *
 * @param [array] $form_data [Settings stored previously in database]
 *
 * @return [type] [description]
 */
function displayWisdmlabs($form_data)
{
    $displayWisdmlabs = isset($form_data[ 'show_powered_by_link' ]) ? $form_data[ 'show_powered_by_link' ] : 0;
    //Don't show option to Display Powered by WisdmLabs if not checked till now
    if ($displayWisdmlabs != 1) {
        return;
    }
    ?>

    <div class="fd">
        <div class='left_div'>
            <label for="link">
                <?php _e(" Display 'Powered by WisdmLabs' ", QUOTEUP_TEXT_DOMAIN) ?>
            </label>
        </div>
        <div class='right_div'>
            <input type="checkbox" class="wdm_wpi_input wdm_wpi_checkbox input-without-tip" value="1" <?php checked(1, $displayWisdmlabs); ?> id="show_powered_by_link" />
            <input type="hidden" name="wdm_form_data[show_powered_by_link]" value="<?php echo isset($form_data[ 'show_powered_by_link' ]) && $form_data[ 'show_powered_by_link' ] == 1 ? $form_data[ 'show_powered_by_link' ] : 0 ?>" />

        </div>
        <div class="clear"></div>
    </div>

    <?php
}

/**
 * Displays 'Selector for Variation Id' setting in the 'General' tab.
 *
 * @since 6.3.4
 * @param [array] $form_data [Settings stored previously in database]
 */
function quoteupShowVariationIdSetting($form_data)
{
    ?>
    <div class="fd">
        <div class='left_div'>
            <label for="variation_id_selector">
                <?php _e('Selector for Variation Id', QUOTEUP_TEXT_DOMAIN) ?>
            </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('Enter the selector for Variation Id if variable products\' attributes are not getting added in an enquiry email or in an enquiry cart. This selector is used only on the variable product page to fetch the variation Id of the variation selected by the user.', QUOTEUP_TEXT_DOMAIN);
            echo \quoteupHelpTip($helptip, true);
            ?>
            <input type="text" class="wdm_wpi_input wdm_wpi_text" name="wdm_form_data[variation_id_selector]" value="<?php echo empty($form_data[ 'variation_id_selector' ]) ? '' : $form_data[ 'variation_id_selector' ]; ?>" id="variation_id_selector"  />
        </div>
        <div class="clear"></div>
    </div>
    <?php
}

function disableNonce($form_data)
{
    $disableNonce = isset($form_data[ 'deactivate_nonce' ]) ? $form_data[ 'deactivate_nonce' ] : 0;
    ?>

    <div class="fd">
        <div class='left_div'>
            <label for="link">
                <?php _e("Disable Nonce ", QUOTEUP_TEXT_DOMAIN) ?>
            </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('Check this option if your enquiry system does not work properly or displays an “Unauthorised Enquiry” error. Note: In all other cases, we advise you to keep it unchecked.', QUOTEUP_TEXT_DOMAIN);
            echo \quoteupHelpTip($helptip, true);
            ?>
            <input type="checkbox" class="wdm_wpi_input wdm_wpi_checkbox" value="1" <?php checked(1, $disableNonce); ?> id="deactivate_nonce" />
            <input type="hidden" name="wdm_form_data[deactivate_nonce]" value="<?php echo isset($form_data[ 'deactivate_nonce' ]) && $form_data[ 'deactivate_nonce' ] == 1 ? $form_data[ 'deactivate_nonce' ] : 0 ?>" />

        </div>
        <div class="clear"></div>
    </div>
    <?php
}



formOptionsSection($form_data);
