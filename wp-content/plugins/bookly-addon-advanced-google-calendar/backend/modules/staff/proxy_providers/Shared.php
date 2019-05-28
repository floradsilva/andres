<?php
namespace BooklyAdvancedGoogleCalendar\Backend\Modules\Staff\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Staff\Proxy;
use BooklyPro\Lib\Google;

/**
 * Class Shared
 * @package BooklyAdvancedGoogleCalendar\Backend\Modules\Staff\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function updateStaff( BooklyLib\Entities\Staff $staff, array $params )
    {
        if ( BooklyLib\Proxy\Pro::getGoogleCalendarSyncMode() == '2-way' ) {
            $google = new Google\Client();
            if ( $google->auth( $staff ) ) {
                $google->calendar()->sync();
                // Register new notification channel.
                if ( ! $google->calendar()->watch() ) {
                    wp_send_json_error( array( 'error' => implode( '<br>', $google->getErrors() ) ) );
                }
            }
        }
    }
}