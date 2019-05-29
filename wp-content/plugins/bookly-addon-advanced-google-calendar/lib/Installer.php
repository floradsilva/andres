<?php
namespace BooklyAdvancedGoogleCalendar\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Installer
 * @package BooklyAdvancedGoogleCalendar\Lib
 */
class Installer extends Base\Installer
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->options = array(
            'bookly_gc_full_sync_offset_days' => '0',
            'bookly_gc_full_sync_titles'      => '1',
        );
    }
}