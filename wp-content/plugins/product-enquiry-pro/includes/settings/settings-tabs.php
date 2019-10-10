<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * This is used to create tabs on settings page. all the settings options will be shown in this tabs.
 *
 * @param [array] $form_data [Quoteup settings]
 *
 */
function settingsTabs($form_data)
{
    $settingsTabs = array(
        'wdm_general' => array(
            'name' => __('General', QUOTEUP_TEXT_DOMAIN),
            'id' => 'general-settings',
            'class' => 'tab nav-tab'
            ),
        'wdm_email' => array(
            'name' => __('Email', QUOTEUP_TEXT_DOMAIN),
            'id' => 'email-settings',
            'class' => 'tab nav-tab'
            ),
        'wdm_display' => array(
            'name' => __('Display', QUOTEUP_TEXT_DOMAIN),
            'id' => 'display-settings',
            'class' => 'tab nav-tab'
            ),
        'wdm_quote' => array(
            'name' => __('Quotation', QUOTEUP_TEXT_DOMAIN),
            'id' => 'quote-settings',
            'class' => 'tab nav-tab'
            ),
        'wdm_form' => array(
            'name' => __('Form', QUOTEUP_TEXT_DOMAIN),
            'id' => 'form-settings',
            'class' => 'tab nav-tab'
            ),
        );
    $settingsTabs = apply_filters('quoteup_settings_tab', $settingsTabs);
    $settingsTabs['wdm_other_extensions'] = array(
            'name' => __('Other Extensions', QUOTEUP_TEXT_DOMAIN),
            'id' => 'form-settings',
            'class' => 'tab nav-tab'
            );

    ?>
    <ul class='etabs'>
    <?php
    foreach ($settingsTabs as $key => $value) {
        ?>
        <li class="<?php echo $value['class']?>" id="<?php echo $value['id']?>"><a href="#<?php echo $key ?>"><?php echo $value['name']?></a></li>
        <?php
    }
    do_action('quoteup_add_product_enquiry_tab_link', $form_data);
    do_action('wdm_pep_add_product_enquiry_tab_link', $form_data);
    ?>
    </ul>
    <?php
}
