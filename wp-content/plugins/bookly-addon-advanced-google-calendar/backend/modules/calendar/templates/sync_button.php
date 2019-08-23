<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php if ( $show_sync_button ) : ?>
    <button id="bookly-google-calendar-sync" class="btn btn-default pull-right bookly-margin-top-xs bookly-margin-right-sm ladda-button" title="<?php esc_attr_e( 'Synchronize with Google Calendar', 'bookly' ) ?>" data-spinner-size="30" data-style="zoom-in" data-spinner-color="#333"><span class="ladda-label"><i class="dashicons dashicons-update"></i> <?php esc_html_e( 'Google Calendar', 'bookly' ) ?></i></span></button>
<?php endif ?>