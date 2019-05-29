<?php
namespace Bookly\Backend\Modules\Notifications;

use Bookly\Lib;

/**
 * Class Ajax
 * @package Bookly\Backend\Modules\Notifications
 */
class Ajax extends Lib\Base\Ajax
{
    /**
     * Test email notifications.
     */
    public static function testEmailNotifications()
    {
        $to_email      = self::parameter( 'to_email' );
        $sender_name   = self::parameter( 'bookly_email_sender_name' );
        $sender_email  = self::parameter( 'bookly_email_sender' );
        $send_as       = self::parameter( 'bookly_email_send_as' );
        $notification_ids   = self::parameter( 'notification_ids' );
        $reply_to_customers = self::parameter( 'bookly_email_reply_to_customers' );

        Lib\Notifications\Test\Sender::send( $to_email, $sender_name, $sender_email, $send_as, $reply_to_customers, $notification_ids );

        wp_send_json_success();
    }

    /**
     * Save general settings for notifications.
     */
    public static function saveGeneralSettingsForNotifications()
    {
        update_option( 'bookly_email_send_as', self::parameter( 'bookly_email_send_as' ) );
        update_option( 'bookly_email_reply_to_customers', self::parameter( 'bookly_email_reply_to_customers' ) );
        update_option( 'bookly_email_sender', self::parameter( 'bookly_email_sender' ) );
        update_option( 'bookly_email_sender_name', self::parameter( 'bookly_email_sender_name' ) );
        update_option( 'bookly_ntf_processing_interval', (int) self::parameter( 'bookly_ntf_processing_interval' ) );

        wp_send_json_success();
    }

}