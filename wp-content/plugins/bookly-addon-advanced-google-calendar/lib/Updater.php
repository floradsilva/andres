<?php
namespace BooklyAdvancedGoogleCalendar\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Updates
 * @package BooklyAdvancedGoogleCalendar\Lib
 */
class Updater extends BooklyLib\Base\Updater
{
    function update_1_1()
    {
        delete_option( 'bookly_advanced_google_calendar_enabled' );
    }
}