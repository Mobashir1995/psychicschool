<?php
/**
 * AutomateWoo variable: Booking start date/time in site timezone
 *
 * Extends Variable_Abstract_Datetime so you can use format and modify like:
 *   {{ booking.start_date_site_tz | format: 'mysql', modify: '-1 hour' }}
 *   {{ booking.start_date_site_tz | format: 'custom', custom-format: 'Y-m-d H:i', modify: '-1 day' }}
 *
 * Use in "Schedule with a variable" so the run time is in site timezone.
 *
 * @package Psychicschool_Functionalities
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Variable class: booking start datetime in site timezone (supports format & modify).
 */
class Psychicschool_AutomateWoo_Booking_Start_Date_Site_Tz extends AutomateWoo\Variable_Abstract_Datetime {

    /**
     * Load admin details (description shown in variable picker).
     */
    public function load_admin_details() {
        $this->description = __( "Booking start date and time in the site's timezone. Use in 'Schedule with a variable' with format and modify, e.g. format: 'mysql', modify: '-1 hour'.", 'psychicschool-functionalities' );
        parent::load_admin_details();
    }

    /**
     * Get the variable value: start date/time in site timezone.
     * Parent class applies format and modify from parameters.
     *
     * @param WC_Booking $booking    The booking object.
     * @param array      $parameters Variable parameters (format, modify, etc.).
     * @return string|false Formatted datetime string, or false if no start.
     */
    public function get_value( $booking, $parameters ) {
        if ( ! is_a( $booking, 'WC_Booking' ) ) {
            return false;
        }
        $start = $booking->get_start();
        if ( $start === null || $start === '' ) {
            return false;
        }
        $site_tz = wp_timezone();
        if ( ! $site_tz ) {
            return false;
        }
        try {
            if ( is_numeric( $start ) ) {
                $dt = new DateTime( '@' . (int) $start );
            } else {
                $dt = new DateTime( (string) $start );
            }
            $dt->setTimezone( $site_tz );
            return $this->format_datetime( $dt, $parameters );
        } catch ( Exception $e ) {
            return false;
        }
    }
}

return new Psychicschool_AutomateWoo_Booking_Start_Date_Site_Tz();
