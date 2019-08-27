<?php

namespace Includes\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
* This class is to create dashboard menu for quoteup.
* It also creates the screen options
 * @static $instance Object of class
*/

class QuoteUpDashboardMenu
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
    * Action for creating dashboard menu
    * Add filters for screen options.
    */
    protected function __construct()
    {
        add_action('admin_menu', array($this, 'dashboardMenu'), 1);
        add_filter('set-screen-option', array($this, 'wdmSetScreenOption'), 10, 3);
    }

    /**
     * Include required files and create a menu for QuoteUp.
     * Gets the unread enquiry count for displaying on the menu of PEP.
     * Add the submenus based on the condition if Quotation system is enabled or not.
     * If yes show create quote submenu also.
     * Otherwise only two submenus i.e, Enquiry details and settings.
     * @global array $quoteup_plugin_data
     */
    public function dashboardMenu()
    {
        global $quoteup;
        require_once QUOTEUP_PLUGIN_DIR.'/includes/admin/listing/class-quoteup-quotes-list.php';
        require_once QUOTEUP_PLUGIN_DIR.'/includes/admin/listing/class-quoteup-enquiries-list.php';
        $getDataFromDb = \Licensing\WdmLicense::checkLicenseAvailiblity('pep', false);
        $optionData = quoteupSettings();
        $capablity = apply_filters('quoteup_menu_capablities', 'manage_woocommerce');
        $unreadEnquiryCount = $this->getUnreadEnquiryCount();
        if ($getDataFromDb == 'available') {
            if ($unreadEnquiryCount > 0) {
                $menuName = sprintf(__('Product Enquiry Pro %s %s %s', QUOTEUP_TEXT_DOMAIN), '<span class="unread-enquiry-count update-plugins"><span class="enquiry-count">', $unreadEnquiryCount, '</span></span>');
            } else {
                $menuName = __('Product Enquiry Pro', QUOTEUP_TEXT_DOMAIN);
            }
            add_menu_page(__('QuoteUp', QUOTEUP_TEXT_DOMAIN), $menuName, $capablity, 'quoteup-details-new', array($quoteup->quoteDetails, 'displayQuoteDetails'), QUOTEUP_PLUGIN_URL.'/images/pep.png');
            add_submenu_page('admin.php?page=quoteup-details-edit', __('Edit Enquiry', QUOTEUP_TEXT_DOMAIN), __('Quote Details', QUOTEUP_TEXT_DOMAIN), $capablity, 'quoteup-details-edit', array($quoteup->quoteDetailsEdit, 'editQuoteDetails'));
            if (isset($optionData['enable_disable_quote']) && $optionData['enable_disable_quote'] == 1) {
                $menu = add_submenu_page('quoteup-details-new', __('Enquiry Details', QUOTEUP_TEXT_DOMAIN), __('Enquiry Details', QUOTEUP_TEXT_DOMAIN), $capablity, 'quoteup-details-new', array($quoteup->quoteDetails, 'displayQuoteDetails'));
            } else {
                $menu = add_submenu_page('quoteup-details-new', __('Enquiry & Quote Details', QUOTEUP_TEXT_DOMAIN), __('Enquiry & Quote Details', QUOTEUP_TEXT_DOMAIN), $capablity, 'quoteup-details-new', array($quoteup->quoteDetails, 'displayQuoteDetails'));
                add_submenu_page('quoteup-details-new', __('Create New Quote', QUOTEUP_TEXT_DOMAIN), __('Create New Quote', QUOTEUP_TEXT_DOMAIN), $capablity, 'quoteup-create-quote', array($quoteup->quoteCreateQuotation, 'createDashboardQuotation'));
            }
            add_submenu_page('quoteup-details-new', __('Forms', QUOTEUP_TEXT_DOMAIN), __('Forms', QUOTEUP_TEXT_DOMAIN), $capablity, 'edit.php?post_type=form', null);
            add_action("load-{$menu}", array($this, 'menuActionLoadHook'));
            do_action('quoteup_dashboard_menu');
            add_submenu_page('quoteup-details-new', __('QuoteUp Settings', QUOTEUP_TEXT_DOMAIN), __('Settings', QUOTEUP_TEXT_DOMAIN), $capablity, 'quoteup-for-woocommerce', array($quoteup->displaySettingsPage, 'displaySettings'));
            add_action('admin_enqueue_scripts', array($quoteup->displaySettingsPage, 'enqueueScripts'));
        }
    }
    /**
    * This function gets the unread enquiry count from meta table.
    * @return the count of unread enquiries
    */
    public function getUnreadEnquiryCount()
    {
        global $wpdb;
        $metaTbl = getEnquiryMetaTable();
        $sql = "SELECT COUNT(enquiry_id) FROM $metaTbl WHERE meta_key= '_unread_enquiry' AND meta_value = 'yes'";
        return $wpdb->get_var($sql);
    }

    /**
     * This function is used to load data for quotes and enquiries.
     * Adds screen option on the page.
     * @return [type] [description]
     */
    public function menuActionLoadHook()
    {
        global $quoteupQuotesList,$quoteupEnquiriesList;
        $optionData = quoteupSettings();
        if (isset($optionData['enable_disable_quote']) && $optionData['enable_disable_quote'] == 1) {
            $quoteupEnquiriesList = new QuoteupEnquiriesList();
        } else {
            $quoteupQuotesList = new QuoteupQuotesList();
        }
        $option = 'per_page';
 
        $args = array(
            'label' => __('Number of items per page : ', QUOTEUP_TEXT_DOMAIN),
            'default' => 10,
            'option' => 'request_per_page'
        );
         
        add_screen_option($option, $args);
    }
 
    /**
    * Setting the Screen option for the page.
    * @param string $status
    * @param string $option screen option
    * @param $value
    */
    public function wdmSetScreenOption($status, $option, $value)
    {
        if ('request_per_page' == $option) {
            return $value;
        }
     
        return $status;
    }
}
QuoteUpDashboardMenu::getInstance();
