<?php
namespace Bookly\Backend\Components\Dialogs\Appointment\Edit;

use Bookly\Lib;

/**
 * Class Edit
 * @package Bookly\Backend\Components\Dialogs\Appointment\Edit
 */
class Dialog extends Lib\Base\Component
{
    /**
     * Render create/edit appointment dialog.
     */
    public static function render()
    {
        self::enqueueStyles( array(
            'backend'  => array( 'css/jquery-ui-theme/jquery-ui.min.css', 'css/select2.min.css', 'css/fontawesome-all.min.css' ),
            'frontend' => array( 'css/ladda.min.css', ),
        ) );

        self::enqueueScripts( array(
            'backend' => array(
                'js/angular.min.js'           => array( 'jquery-ui-datepicker' ),
                'js/angular-ui-date-0.0.8.js' => array( 'bookly-angular.min.js' ),
                'js/moment.min.js'            => array( 'jquery' ),
                'js/select2.full.min.js'      => array( 'jquery' ),
                'js/help.js'                  => array( 'jquery' ),
            ),
            'frontend' => array(
                'js/spin.min.js'  => array( 'jquery' ),
                'js/ladda.min.js' => array( 'jquery' ),
            ),
            'module' => array(
                'js/ng-appointment.js' => array( 'bookly-angular-ui-date-0.0.8.js' ),
            )
        ) );

        wp_localize_script( 'bookly-ng-appointment.js', 'BooklyL10nAppDialog', array(
            'csrf_token'      => Lib\Utils\Common::getCsrfToken(),
            'datePicker'      => Lib\Utils\DateTime::datePickerOptions(),
            'cf_per_service'  => (int) Lib\Config::customFieldsPerService(),
            'no_result_found' => __( 'No result found', 'bookly' ),
            'staff_any'       => get_option( 'bookly_l10n_option_employee' ),
            'title'           => array(
                'edit_appointment' => __( 'Edit appointment', 'bookly' ),
                'new_appointment'  => __( 'New appointment',  'bookly' ),
            ),
        ) );

        Proxy\Shared::enqueueAssets();

        self::renderTemplate( 'edit' );
    }
}