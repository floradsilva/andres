<?php
namespace BooklyAdvancedGoogleCalendar\Backend\Modules\Settings\ProxyProviders;

use Bookly\Backend\Components\Settings\Inputs;
use Bookly\Backend\Components\Settings\Selects;
use Bookly\Backend\Modules\Settings\Proxy;
use Bookly\Lib as BooklyLib;
use BooklyPro\Lib\Google;

/**
 * Class Local
 * @package BooklyAdvancedGoogleCalendar\Backend\Modules\Settings\ProxyProviders
 */
class Local extends Proxy\AdvancedGoogleCalendar
{
    /**
     * @inheritdoc
     */
    public static function preSaveSettings( array $alert, array $params )
    {
        $gc_client_id     = $params['bookly_gc_client_id'];
        $gc_client_secret = $params['bookly_gc_client_secret'];
        $gc_sync_mode     = $params['bookly_gc_sync_mode'];
        $google           = new Google\Client();
        if (
            $gc_client_id != get_option( 'bookly_gc_client_id' ) ||
            $gc_client_secret != get_option( 'bookly_gc_client_secret' ) ||
            $gc_sync_mode != '2-way'
        ) {
            // Clean up channels.
            foreach ( BooklyLib\Entities\Staff::query()->whereNot( 'google_data', null )->find() as $staff ) {
                if ( $google->auth( $staff ) ) {
                    $google->calendar()->clearSyncToken()->stopWatching();
                }
            }
        }

        return $alert;
    }

    /**
     * @inheritdoc
     */
    public static function renderSettings()
    {
        Selects::renderSingle(
            'bookly_gc_sync_mode',
            __( 'Synchronization mode', 'bookly' ),
            __( 'With "One-way" sync Bookly pushes new appointments and any further changes to Google Calendar. With "Two-way front-end only" sync Bookly will additionally fetch events from Google Calendar and remove corresponding time slots before displaying the Time step of the booking form (this may lead to a delay when users click Next to get to the Time step). With "Two-way" sync all bookings created in Bookly Calendar will be automatically copied to Google Calendar and vice versa. Important: your website must use HTTPS. Google Calendar API will be able to send notifications only if there is a valid SSL certificate installed on your web server.', 'bookly' ),
            array(
                array( '1-way', __( 'One-way', 'bookly' ) ),
                array( '1.5-way', __( 'Two-way front-end only', 'bookly' ) ),
                array( '2-way', __( 'Two-way', 'bookly' ) )
            )
        );

        Inputs::renderNumber(
            'bookly_gc_full_sync_offset_days',
            __( 'Sync appointments history', 'bookly' ),
            __( 'Specify how many days of past calendar data you wish to sync at the time of initial sync. If you enter 0, synchronization of past events will not be performed.', 'bookly' ),
            0,
            1
        );

        Selects::renderSingle(
            'bookly_gc_full_sync_titles',
            __( 'Copy Google Calendar event titles', 'bookly' ),
            __( 'If enabled then titles of Google Calendar events will be copied to Bookly appointments. If disabled, a standard title "Google Calendar event" will be used.', 'bookly' )
        );
    }
}