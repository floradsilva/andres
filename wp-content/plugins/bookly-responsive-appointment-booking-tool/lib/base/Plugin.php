<?php
namespace Bookly\Lib\Base;

use Bookly\Lib;
use BooklyPro\Lib\Base\Plugin as PluginPro;

/**
 * Class Plugin
 * @package Bookly\Lib\Base
 */
abstract class Plugin
{
    /******************************************************************************************************************
     * Protected properties                                                                                           *
     ******************************************************************************************************************/

    /**
     * Prefix for options and metas.
     *
     * @staticvar string
     */
    protected static $prefix;

    /**
     * Plugin title.
     *
     * @staticvar string
     */
    protected static $title;

    /**
     * Plugin version.
     *
     * @staticvar string
     */
    protected static $version;

    /**
     * Plugin slug.
     *
     * @staticvar string
     */
    protected static $slug;

    /**
     * Path to plugin directory.
     *
     * @staticvar string
     */
    protected static $directory;

    /**
     * Path to plugin main file.
     *
     * @staticvar string
     */
    protected static $main_file;

    /**
     * Plugin basename.
     *
     * @staticvar string
     */
    protected static $basename;

    /**
     * Plugin text domain.
     *
     * @staticvar string
     */
    protected static $text_domain;

    /**
     * Root namespace of plugin classes.
     *
     * @staticvar string
     */
    protected static $root_namespace;

    /**
     * Whether the plugin is embedded or not.
     *
     * @staticvar bool
     */
    protected static $embedded;

    /******************************************************************************************************************
     * Private properties                                                                                             *
     ******************************************************************************************************************/

    /**
     * Array of plugin classes for objects.
     *
     * @var static[]
     */
    private static $plugin_classes = array();

    /******************************************************************************************************************
     * Public methods                                                                                                 *
     ******************************************************************************************************************/

    /**
     * Run plugin.
     */
    public static function run()
    {
        if ( ! session_id() ) {
            // fix loopback request failure
            if (
                // WP 4.9+ plugin or theme editor
                ! isset( $_GET['wp_scrape_key'] )
                &&
                // WP 5.2+ Site Health feature=loopback_requests
                ! ( isset( $_POST['action'] ) && $_POST['action'] == 'health-check-site-status' )
            ) {
                // Start session.
                @session_start();
            }
        }

        /** @var static $plugin_class */
        $plugin_class = get_called_class();

        // WP hooks.
        $plugin_class::registerHooks();

        // Update checker.
        if ( ! $plugin_class::embedded() ) {
            $plugin_class::initUpdateChecker();
        }

        // Init.
        $plugin_class::init();

        add_action( 'init', function () use ( $plugin_class ) {
            // Updater.
            $plugin_class::update();
        } );
    }

    /**
     * Activate plugin.
     *
     * @param bool $network_wide
     */
    public static function activate( $network_wide )
    {
        if ( $network_wide && has_action( 'bookly_plugin_activate' ) ) {
            do_action( 'bookly_plugin_activate', static::getSlug() );
        } else {
            $installer_class = static::getRootNamespace() . '\Lib\Installer';
            $installer = new $installer_class();
            /** @var Installer $installer */
            $installer->install();
        }
    }

    /**
     * Deactivate plugin.
     *
     * @param bool $network_wide
     */
    public static function deactivate( $network_wide )
    {
        if ( $network_wide && has_action( 'bookly_plugin_deactivate' ) ) {
            do_action( 'bookly_plugin_deactivate', static::getSlug() );
        }
    }

    /**
     * Uninstall plugin.
     *
     * @param string|bool $network_wide
     */
    public static function uninstall( $network_wide )
    {
        if ( $network_wide !== false && has_action( 'bookly_plugin_uninstall' ) ) {

            /** @var static $plugin_class */
            $plugin_class = get_called_class();
            // Register bookly and add-ons in bookly_plugins list.
            add_filter( 'bookly_plugins', function ( array $plugins ) use ( $plugin_class ) {
                $plugins[ $plugin_class::getSlug() ] = $plugin_class;

                return $plugins;
            } );

            do_action( 'bookly_plugin_uninstall', static::getSlug() );
        } else {
            $installer_class = static::getRootNamespace() . '\Lib\Installer';
            /** @var Installer $installer */
            $installer = new $installer_class();
            $installer->uninstall();
        }
    }

    /**
     * Get prefix.
     *
     * @return mixed
     */
    public static function getPrefix()
    {
        if ( static::$prefix === null ) {
            static::$prefix = str_replace( array( '-addon', '-' ), array( '', '_' ), static::getSlug() ) . '_';
        }

        return static::$prefix;
    }

    /**
     * Get plugin title.
     *
     * @return string
     */
    public static function getTitle()
    {
        if ( static::$title === null ) {
            if ( ! function_exists( 'get_plugin_data' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            $plugin_data = get_plugin_data( static::getMainFile() );
            static::$version     = $plugin_data['Version'];
            static::$title       = $plugin_data['Name'];
            static::$text_domain = $plugin_data['TextDomain'];
        }

        return static::$title;
    }

    /**
     * Get plugin version.
     *
     * @return string
     */
    public static function getVersion()
    {
        if ( static::$version === null ) {
            if ( ! function_exists( 'get_plugin_data' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            $plugin_data = get_plugin_data( static::getMainFile() );
            static::$version     = $plugin_data['Version'];
            static::$title       = $plugin_data['Name'];
            static::$text_domain = $plugin_data['TextDomain'];
        }

        return static::$version;
    }

    /**
     * Get plugin slug.
     *
     * @return string
     */
    public static function getSlug()
    {
        if ( static::$slug === null ) {
            static::$slug = basename( static::getDirectory() );
        }

        return static::$slug;
    }

    /**
     * Get path to plugin directory.
     *
     * @return string
     */
    public static function getDirectory()
    {
        if ( static::$directory === null ) {
            $reflector = new \ReflectionClass( get_called_class() );
            static::$directory = dirname( dirname( $reflector->getFileName() ) );
        }

        return static::$directory;
    }

    /**
     * Get path to plugin main file.
     *
     * @return string
     */
    public static function getMainFile()
    {
        if ( static::$main_file === null ) {
            static::$main_file = static::getDirectory() . '/main.php';
        }

        return static::$main_file;
    }

    /**
     * Get plugin basename.
     *
     * @return string
     */
    public static function getBasename()
    {
        if ( static::$basename === null ) {
            static::$basename = plugin_basename( static::getMainFile() );
        }

        return static::$basename;
    }

    /**
     * Get root namespace of called class.
     *
     * @return string
     */
    public static function getRootNamespace()
    {
        if ( static::$root_namespace === null ) {
            $called_class = get_called_class();
            static::$root_namespace = substr( $called_class, 0, strpos( $called_class, '\\' ) );
        }

        return static::$root_namespace;
    }

    /**
     * Get entity classes.
     *
     * @return Lib\Base\Entity[]
     */
    public static function getEntityClasses()
    {
        $result = array();

        $dir = static::getDirectory() . '/lib/entities';
        if ( is_dir( $dir ) ) {
            foreach ( scandir( $dir ) as $filename ) {
                if ( $filename == '.' || $filename == '..' ) {
                    continue;
                }
                $result[] = static::getRootNamespace() . '\Lib\Entities\\' . basename( $filename, '.php' );
            }
        }

        return $result;
    }

    /**
     * Get plugin purchase code option name.
     *
     * @return string
     */
    public static function getPurchaseCodeOption()
    {
        return static::getPrefix() . 'envato_purchase_code';
    }

    /**
     * Get plugin purchase code.
     *
     * @param int $blog_id
     * @return string
     */
    public static function getPurchaseCode( $blog_id = null )
    {
        $option = static::getPurchaseCodeOption();

        return $blog_id ? get_blog_option( $blog_id, $option ) : get_option( $option );
    }

    /**
     * Update plugin purchase code.
     *
     * @param string $value
     * @param int    $blog_id
     */
    public static function updatePurchaseCode( $value, $blog_id = null )
    {
        $option = static::getPurchaseCodeOption();

        if ( $blog_id ) {
            update_blog_option( $blog_id, $option, $value );
        } else {
            update_option( $option, $value );
        }
    }

    /**
     * Get plugin installation time.
     *
     * @return int
     */
    public static function getInstallationTime()
    {
        return get_option( static::getPrefix() . 'installation_time' );
    }

    /**
     * Check whether the plugin is network active.
     *
     * @return bool
     */
    public static function isNetworkActive()
    {
        return is_plugin_active_for_network( static::getBasename() );
    }

    /**
     * Get plugin class for given object.
     *
     * @param object|string $object
     * @return static
     */
    public static function getPluginFor( $object )
    {
        $class = is_object( $object ) ? get_class( $object ) : $object;

        if ( ! isset ( self::$plugin_classes[ $class ] ) ) {
            self::$plugin_classes[ $class ] = substr( $class, 0, strpos( $class, '\\' ) ) . '\Lib\Plugin';
        }

        return self::$plugin_classes[ $class ];
    }

    /**
     * Check if add-on is embedded.
     *
     * @return bool
     */
    public static function embedded()
    {
        if ( static::$embedded === null ) {
            static::$embedded = strpos( static::getDirectory(), DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR ) > 0;
        }

        return static::$embedded;
    }

    /******************************************************************************************************************
     * Protected methods                                                                                              *
     ******************************************************************************************************************/

    /**
     * Register hooks.
     */
    protected static function registerHooks()
    {
        /** @var static $plugin_class */
        $plugin_class = get_called_class();
        // Register bookly and add-ons in bookly_plugins list.
        add_filter( 'bookly_plugins', function ( array $plugins ) use ( $plugin_class ) {
            $plugins[ $plugin_class::getSlug() ] = $plugin_class;

            return $plugins;
        } );

        if ( Lib\Config::proActive() ) {
            PluginPro::registerHooks( $plugin_class );
        }
    }

    /**
     * Init plugin.
     */
    protected static function init()
    {

    }

    /**
     * Init update checker.
     */
    protected static function initUpdateChecker()
    {
        /** @var static $plugin_class */
        $plugin_class = get_called_class();
        if ( $plugin_class != 'Bookly\Lib\Plugin' && Lib\Config::proActive() ) {
            PluginPro::initPluginUpdateChecker( $plugin_class );
        }
    }

    /**
     * Run updates.
     */
    protected static function update()
    {
        $updater_class = static::getRootNamespace() . '\Lib\Updater';
        $updater = new $updater_class();
        $updater->run();
    }
}