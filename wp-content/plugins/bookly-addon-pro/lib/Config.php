<?php
namespace BooklyPro\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Config
 * @package BooklyPro\Lib
 */
abstract class Config
{
    /** @var int|bool */
    protected static $grace_remaining_days;

    /**
     * Get Google Calendar synchronization mode (null means Google Calendar integration is not configured).
     *
     * @return string|null  1-way, 1.5-way, 2-way
     */
    public static function getGoogleCalendarSyncMode()
    {
        if ( get_option( 'bookly_gc_client_id' ) == '' ) {
            return null;
        }

        return get_option( 'bookly_gc_sync_mode', '1.5-way' );
    }

    /**
     * Check whether grace period is applicable and expired.
     *
     * @param bool $use_cache
     * @return bool
     */
    public static function graceExpired( $use_cache = true )
    {
        return self::graceRemainingDays( $use_cache ) === 0;
    }

    /**
     * Get number of days until grace end or false if grace period is not applicable.
     *
     * @param bool $use_cache
     * @return int|false
     */
    public static function graceRemainingDays( $use_cache = true )
    {
        if ( ! $use_cache ) {
            self::$grace_remaining_days = null;
        }

        if ( self::$grace_remaining_days === null ) {
            // Length of grace period.
            $grace_period_days = 14;

            $today = (int) ( current_time( 'timestamp' ) / DAY_IN_SECONDS );
            $api_error_day = (int) ( get_option( 'bookly_api_server_error_time' ) / DAY_IN_SECONDS );

            if ( $api_error_day && $today - $api_error_day > 7 ) {
                self::$grace_remaining_days = max( 0, $api_error_day + $grace_period_days - $today  );
            } else {
                $addons = apply_filters( 'bookly_plugins', array() );
                unset ( $addons[ BooklyLib\Plugin::getSlug() ] );

                foreach ( $addons as $plugin_slug => $plugin_class ) {
                    /** @var BooklyLib\Base\Plugin $plugin_class */
                    if ( $plugin_class::getPurchaseCode() == '' && ! $plugin_class::embedded() ) {
                        $grace_start = (int) ( get_option( $plugin_class::getPrefix() . 'grace_start' ) / DAY_IN_SECONDS );
                        if ( $today >= $grace_start ) {
                            $remaining_days = max( 0, $grace_start + $grace_period_days - $today );
                            if ( self::$grace_remaining_days === null || $remaining_days < self::$grace_remaining_days ) {
                                self::$grace_remaining_days = $remaining_days;
                            }
                        }
                    }
                }

                if ( self::$grace_remaining_days === null ) {
                    self::$grace_remaining_days = false;
                }
            }
        }

        return self::$grace_remaining_days;
    }

    /**
     * Get minimum time (in seconds) prior to booking.
     *
     * @return integer
     */
    public static function getMinimumTimePriorBooking()
    {
        return get_option( 'bookly_gen_min_time_prior_booking' ) * 3600;
    }

    /**
     * Get minimum time (in seconds) prior to cancel.
     *
     * @return integer
     */
    public static function getMinimumTimePriorCancel()
    {
        return get_option( 'bookly_gen_min_time_prior_cancel' ) * 3600;
    }

    /**
     * Whether to show Facebook login button at the time step of booking form.
     *
     * @return bool
     */
    public static function showFacebookLoginButton()
    {
        return (bool) get_option( 'bookly_app_show_facebook_login_button' );
    }

    /**
     * Get Facebook app ID.
     *
     * @return string
     */
    public static function getFacebookAppId()
    {
        return get_option( 'bookly_fb_app_id' );
    }
}