<?php
namespace BooklyPro\Lib;

use Bookly\Lib as BooklyLib;
use BooklyPro\Backend;
use BooklyPro\Frontend;

/**
 * Class Plugin
 * @package BooklyPro\Lib
 */
abstract class Plugin extends BooklyLib\Base\Plugin
{
    protected static $prefix;
    protected static $title;
    protected static $version;
    protected static $slug;
    protected static $directory;
    protected static $main_file;
    protected static $basename;
    protected static $text_domain;
    protected static $root_namespace;
    protected static $embedded;

    /**
     * @inheritdoc
     */
    protected static function init()
    {
        // Init ajax.
        Backend\Components\Dialogs\Payment\Ajax::init();
        Backend\Components\Gutenberg\AppointmentsList\Block::init();
        Backend\Components\Gutenberg\CancellationConfirmation\Block::init();
        Backend\Components\License\Ajax::init();
        Backend\Components\Settings\Ajax::init();
        Backend\Modules\Analytics\Ajax::init();
        Backend\Modules\Customers\Ajax::init();
        Backend\Modules\Services\Ajax::init();
        Backend\Modules\Staff\Ajax::init();
        Frontend\Modules\Booking\Ajax::init();
        Frontend\Modules\CustomerProfile\Ajax::init();
        Frontend\Modules\WooCommerce\Ajax::init();

        // Init proxy.
        Backend\Components\Appearance\ProxyProviders\Local::init();
        Backend\Components\Dialogs\Appointment\AttachPayment\ProxyProviders\Local::init();
        Backend\Components\Dialogs\Appointment\CustomerDetails\ProxyProviders\Local::init();
        Backend\Components\Dialogs\Appointment\Edit\ProxyProviders\Local::init();
        Backend\Components\Dialogs\Customer\ProxyProviders\Local::init();
        Backend\Components\Dialogs\Payment\ProxyProviders\Local::init();
        Backend\Components\Notices\ProxyProviders\Local::init();
        Backend\Components\Settings\ProxyProviders\Local::init();
        Backend\Components\TinyMce\ProxyProviders\Shared::init();
        Backend\Modules\Appearance\ProxyProviders\Local::init();
        Backend\Modules\Appearance\ProxyProviders\Shared::init();
        Backend\Modules\Appointments\ProxyProviders\Local::init();
        Backend\Modules\Calendar\ProxyProviders\Shared::init();
        Backend\Modules\Customers\ProxyProviders\Local::init();
        Backend\Modules\Services\ProxyProviders\Local::init();
        Backend\Modules\Services\ProxyProviders\Shared::init();
        Backend\Modules\Settings\ProxyProviders\Local::init();
        Backend\Modules\Settings\ProxyProviders\Shared::init();
        Backend\Modules\Staff\ProxyProviders\Local::init();
        Backend\Modules\Staff\ProxyProviders\Shared::init();
        Frontend\Modules\Booking\ProxyProviders\Local::init();
        Frontend\Modules\Booking\ProxyProviders\Shared::init();
        Notifications\Assets\Item\ProxyProviders\Shared::init();
        Notifications\Cart\ProxyProviders\Local::init();
        Notifications\Test\ProxyProviders\Local::init();
        ProxyProviders\Local::init();
        ProxyProviders\Shared::init();

        if ( ! is_admin() ) {
            // Init short code.
            Frontend\Modules\CancellationConfirmation\ShortCode::init();
            Frontend\Modules\CustomerProfile\ShortCode::init();
        }
    }

    /**
     * @inheritdoc
     */
    public static function run()
    {
        parent::run();

        // Run embedded add-ons.
        foreach ( self::embeddedAddons() as $plugin_class ) {
            $plugin_class::run();
        }
    }

    /**
     * @inheritdoc
     */
    public static function uninstall( $network_wide )
    {
        // Uninstall embedded add-ons.
        foreach ( self::embeddedAddons() as $plugin_class ) {
            $plugin_class::uninstall( $network_wide );
        }

        parent::uninstall( $network_wide );
    }

    /**
     * @inheritdoc
     */
    public static function activate( $network_wide )
    {
        parent::activate( $network_wide );

        if ( ! $network_wide ) {
            // Activate embedded add-ons.
            foreach ( self::embeddedAddons() as $plugin_class ) {
                $plugin_class::activate( false );
            }
        }
    }

    /**
     * Get embedded add-ons.
     *
     * @return BooklyLib\Base\Plugin[]
     */
    protected static function embeddedAddons()
    {
        $result = array();

        $dir = self::getDirectory() . '/lib/addons/';
        if ( is_dir( $dir ) ) {
            foreach ( glob( $dir . 'bookly-addon-*', GLOB_ONLYDIR ) as $path ) {
                include_once $path . '/autoload.php';
                $namespace = implode( '', array_map( 'ucfirst', explode( '-', str_replace( '-addon-', '-', basename( $path ) ) ) ) );
                $result[] = '\\' . $namespace . '\Lib\Plugin';
            }
        }

        return $result;
    }
}